#!/bin/bash

set -e

function cleanup() {
    echo -e "\033[0;31m[ERROR]\033[0m something went wrong, cleaning up.."
    exec ./down.sh
}
trap cleanup ERR INT

# Create a local cluster
k3d registry create registry.internal --port 5000
k3d cluster create local-cluster \
    --volume ${PWD}/api:/app/api \
    --volume ${PWD}/src/backend/admin:/app/admin \
    --volume ${PWD}/frontend:/app/frontend \
    --registry-use k3d-registry.internal \
    --k3s-server-arg "--no-deploy=traefik" \
    --agents 3 \
    --port 80:80@loadbalancer \
    --port 443:443@loadbalancer
kubectl config use-context k3d-local-cluster
kubectl cluster-info
kubectl create ns blog && kubectl config set-context --current --namespace=blog

# Build php system image
docker build -t php-common:latest -f ops/on-premises/docker/php/Dockerfile .

# Build container images
docker build --target development -t k3d-registry.internal:5000/api:latest -f api/Dockerfile .
docker build --target development -t k3d-registry.internal:5000/admin:latest -f ops/on-premises/docker/backend/admin/Dockerfile .
docker build --target development -t k3d-registry.internal:5000/frontend:latest -f frontend/Dockerfile .

# Push container images to local registry
docker push k3d-registry.internal:5000/api:latest
docker push k3d-registry.internal:5000/admin:latest
docker push k3d-registry.internal:5000/frontend:latest

# Create external postgres database
postgresContainerId=$(docker run -d \
    --name postgres \
    -e POSTGRES_PASSWORD=secret \
    -v ${PWD}/local/db/:/docker-entrypoint-initdb.d \
    -p 5432:5432 \
    --network k3d-local-cluster \
    --restart unless-stopped \
    postgres)
postgresIP=$(docker inspect $postgresContainerId --format '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}')
POSTGRES_IP=$postgresIP envsubst < ./local/db/service.yaml | kubectl apply -f - 

# Install node_modules and composer artifacts
docker run --rm -it --user 1000:1000 -v ${PWD}/api:/app -w /app php-common:latest /bin/sh -c "composer install"
docker run --rm -it --user 1000:1000 -v ${PWD}/src/backend/admin:/app -w /app php-common:latest /bin/sh -c "composer install"
docker run --rm -it --user 1000:1000 -v ${PWD}/src/backend/admin:/app -w /app node:15.2.1-buster-slim /bin/sh -c "yarn install && yarn dev"
docker run --rm -it --user 1000:1000 -v ${PWD}/frontend:/app -w /app node:15.2.1-buster-slim /bin/sh -c "yarn install"

# Deploy api, admin and frontend
for manifest in `ls api/*.yaml | xargs`;
do
    echo "[INFO] Applying manifest ${manifest}"
    VERSION=latest \
    REPOSITORY=k3d-registry.internal:5000/api \
    APP_ENV=development \
    APP_SECRET=`echo -n '875e1d50e3365aa7f4445fe71c0de8f3' | base64 -w0` \
    DATABASE_URL=`echo -n 'postgresql://postgres:secret@postgres:5432/blog_api?serverVersion=13&charset=utf8' | base64 -w0` \
    TEST_DATABASE_URL=`echo -n 'postgresql://postgres:secret@postgres:5432/blog_api_test?serverVersion=13&charset=utf8' | base64 -w0` \
    JWT_PUBLIC_KEY=`echo -n '%kernel.project_dir%/config/jwt/public.pem' | base64 -w0` \
    JWT_SECRET_KEY=`echo -n '%kernel.project_dir%/config/jwt/private.pem' | base64 -w0` \
    GOOGLE_APPLICATION_CREDENTIALS=`echo -n '/run/secrets/service_account.json' | base64 -w0` \
    envsubst < $manifest | kubectl apply -f -
done
# kubectl apply -f local/admin/
for manifest in `ls frontend/*.yaml | xargs`;
do
    echo "[INFO] Applying manifest ${manifest}"
    VERSION=latest \
    REPOSITORY=k3d-registry.internal:5000/frontend \
    APP_ENV=development \
    API_USERNAME=`echo -n admin@gmail.com | base64 -w0` \
    API_PASSWORD=`echo -n admin | base64 -w0` \
    MAILGUN_API_KEY=`echo -n test | base64 -w0`  \
    MAILGUN_DOMAIN=`echo -n mg.eden-reich.com | base64 -w0` \
    envsubst < $manifest | kubectl apply -f -
done

# Mount volumes for local development
kubectl patch deploy api-latest -p '{"metadata":{"name":"api-latest"},"spec":{"template":{"spec":{"containers":[{"name":"api","volumeMounts":[{"name":"api-volume","mountPath":"/app"}]}],"volumes":[{"name":"api-volume","hostPath":{"path":"/app/api"}}]}}}}'
kubectl patch deploy frontend-latest -p '{"metadata":{"name":"frontend-latest"},"spec":{"template":{"spec":{"containers":[{"name":"frontend","volumeMounts":[{"name":"frontend-volume","mountPath":"/app"}]}],"volumes":[{"name":"frontend-volume","hostPath":{"path":"/app/frontend"}}]}}}}'

# Deploy Nginx Ingress
helm repo add ingress-nginx https://kubernetes.github.io/ingress-nginx
helm repo update
helm -n kube-system install ingress-nginx ingress-nginx/ingress-nginx --set controller.service.enableHttps=false
kubectl -n kube-system rollout status deploy/ingress-nginx-controller
kubectl apply -f local/ingress.yaml
