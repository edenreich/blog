#!/bin/bash

term=$1;

if [ -z $1 ]
then
    echo "Missing first argument, image name!"
    exit 1
fi

echo "==> Removing ${term} images. Keeping 5 versions";

#docker rmi -f $(docker images | grep $term | sort -r | tail -n +6 | awk '{ printf "%s\t", $3 }' | xargs) || true
sudo k3s crictl rmi --prune $(sudo k3s crictl images ls | grep $term | sort -r | tail -n +6 | awk '{ printf "%s\t", $3 }' | xargs) || true
