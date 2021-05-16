#!/bin/sh

k3d cluster delete local-cluster
k3d registry delete k3d-registry.internal
docker rm -f postgres
docker system prune -f --volumes