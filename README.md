![admin](https://github.com/edenreich/blog/workflows/admin/badge.svg)
![api](https://github.com/edenreich/blog/workflows/api/badge.svg)
![frontend](https://github.com/edenreich/blog/workflows/frontend/badge.svg)
![cloud-infrastructure](https://github.com/edenreich/blog/workflows/cloud-infrastructure/badge.svg)
![on-premises-infrastructure](https://github.com/edenreich/blog/workflows/on-premises-infrastructure/badge.svg)

## Blog

My blog for posting interesting content.


### Prerequisite

* docker
* helm
* kubectl
* k3d

### Quick Start

Generate API RSA keys without passphrase for local development:
```sh
mkdir -p src/backend/api/config/jwt
openssl genrsa -out src/backend/api/config/jwt/private.pem 4096
openssl rsa -in src/backend/api/config/jwt/private.pem -out src/backend/api/config/jwt/public.pem -pubout
```

To startup a cluster run:
```sh
./up.sh
```

To cleanup the local cluster, run:
```sh
./down.sh
```

Enter one of the pods of the api deployment and run:
```sh
bin/console doctrine:migration:migrate
bin/console doctrine:fixtures:load
```

### Long Start

1. Create container image registry:
```sh
// add 127.0.0.1 k3d-registry.internal to /etc/hosts
k3d registry create registry.internal --port 5000
```
2. Create development cluster:
```sh
k3d cluster create local-cluster \
    --volume ${PWD}/src/frontend:/app/frontend \
    --volume ${PWD}/src/backend/api:/app/api \
    --volume ${PWD}/src/backend/admin:/app/admin \
    --registry-use k3d-registry.internal \
    --k3s-server-arg "--no-deploy=traefik" \
    --agents 3 \
    --port 80:80@loadbalancer
```
3. Create namespace blog and switch current context:
```sh
kubectl create ns blog && kubectl config set-context --current --namespace=blog
```
4. Build container images:
```sh
docker build --target development -t k3d-registry.internal:5000/api:latest -f ops/on-premises/docker/backend/api/Dockerfile .
docker build --target development -t k3d-registry.internal:5000/admin:latest -f ops/on-premises/docker/backend/admin/Dockerfile .
docker build --target development -t k3d-registry.internal:5000/frontend:latest -f ops/on-premises/docker/frontend/Dockerfile .
```
5. Push container images to local registry:
```sh
docker push k3d-registry.internal:5000/api:latest
docker push k3d-registry.internal:5000/admin:latest
docker push k3d-registry.internal:5000/frontend:latest
```
6. Create local database:
```sh
docker run -d \
    --name postgres \
    -e POSTGRES_PASSWORD=secret \
    -v ${PWD}/local/db/:/docker-entrypoint-initdb.d \
    -p 5432:5432 \
    --network k3d-local-cluster \
    postgres
```
7. Deploy NGINX-Ingress:
```sh
helm repo add ingress-nginx https://kubernetes.github.io/ingress-nginx
helm repo update
helm -n kube-system install ingress-nginx ingress-nginx/ingress-nginx --set controller.service.enableHttps=false
```

8. Deploy the services to the local cluster:
```sh
// Ingress
kubectl apply -f local/ingress.yaml

// API
kubectl apply -f local/api/

// Admin
kubectl apply -f local/admin/

// Frontend
kubectl apply -f local/frontend/
```

Cleanup:
```sh
k3d cluster delete local-cluster
k3d registry delete k3d-registry.internal
docker rm -f postgres
docker system prune -f --volumes
sudo rm -rf data
```