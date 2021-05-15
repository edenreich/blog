#!/bin/sh

# Create a local cluster
k3d registry create registry.internal --port 5000
k3d cluster create local-cluster \
    --volume ${PWD}/src/frontend:/app/frontend \
    --volume ${PWD}/src/backend/api:/app/api \
    --volume ${PWD}/src/backend/admin:/app/admin \
    --registry-use k3d-registry.internal \
    --k3s-server-arg "--no-deploy=traefik" \
    --agents 3 \
    --port 80:80@loadbalancer
kubectl config use-context k3d-local-cluster
kubectl cluster-info
kubectl create ns blog && kubectl config set-context --current --namespace=blog

# Build container images
docker build --target development -t k3d-registry.internal:5000/api:latest -f ops/on-premises/docker/backend/api/Dockerfile .
docker build --target development -t k3d-registry.internal:5000/admin:latest -f ops/on-premises/docker/backend/admin/Dockerfile .
docker build --target development -t k3d-registry.internal:5000/frontend:latest -f ops/on-premises/docker/frontend/Dockerfile .

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

# Install artifacts
docker run --rm -it --user 1000:1000 -v ${PWD}/src/backend/api:/app -w /app composer:1.9 /bin/sh -c "composer install"
docker run --rm -it --user 1000:1000 -v ${PWD}/src/backend/admin:/app -w /app composer:1.9 /bin/sh -c "composer install"
docker run --rm -it --user 1000:1000 -v ${PWD}/src/frontend:/app -w /app node:15.2.1-buster-slim /bin/sh -c "yarn install"

# Deploy api, admin and frontend
kubectl apply -f local/api/
kubectl apply -f local/admin/
kubectl apply -f local/frontend/

# Deploy Nginx Ingress
helm repo add ingress-nginx https://kubernetes.github.io/ingress-nginx
helm repo update
helm -n kube-system install ingress-nginx ingress-nginx/ingress-nginx --set controller.service.enableHttps=false
kubectl -n kube-system rollout status deploy/ingress-nginx-controller
kubectl apply -f local/ingress.yaml
