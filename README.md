![admin](https://github.com/edenreich/blog/workflows/admin/badge.svg)
![api](https://github.com/edenreich/blog/workflows/api/badge.svg)
![frontend](https://github.com/edenreich/blog/workflows/frontend/badge.svg)
![cloud-infrastructure](https://github.com/edenreich/blog/workflows/cloud-infrastructure/badge.svg)
![on-premises-infrastructure](https://github.com/edenreich/blog/workflows/on-premises-infrastructure/badge.svg)

## Blog

My blog for posting interesting content.


### Quick Start

1. Create container image registry:
```sh
// add 127.0.0.1 k3d-registry.internal to /etc/hosts
k3d registry create registry.internal --port 5000
```
2. Create development cluster:
```sh
k3d cluster create local-cluster \
    --volume ${PWD}/src/frontend:/app/frontend@agent\[\*\] \
    --volume ${PWD}/src/backend/api:/app/api@agent\[\*\] \
    --volume ${PWD}/src/backend/admin:/app/admin@agent\[\*\] \
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
docker build --target development -t k3d-registry.internal:5000/frontend:latest -f ops/on-premises/docker/frontend/Dockerfile .
docker build --target development -t k3d-registry.internal:5000/api:latest -f ops/on-premises/docker/backend/api/Dockerfile .
docker build --target development -t k3d-registry.internal:5000/admin:latest -f ops/on-premises/docker/backend/admin/Dockerfile .
```
5. Push container images to local registry:
```sh
docker push k3d-registry.internal:5000/frontend:latest
docker push k3d-registry.internal:5000/api:latest
docker push k3d-registry.internal:5000/admin:latest
```
6. Create local database:
```sh
docker run -d \
    --name postgres \
    --network app-net \
    -e POSTGRES_PASSWORD=secret \
    -v $(PWD)/data/:/var/lib/postgresql/data \
    postgres
```
7. Deploy NGINX-ingress:
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
```