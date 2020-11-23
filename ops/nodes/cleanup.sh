#!/bin/bash

term=$1

if [ -z $1 ]
then
    echo "Missing first argument, image name!"
    exit 1
fi

echo "==> Removing all unused images on $(hostname)."

sudo k3s crictl rmi --prune || true
