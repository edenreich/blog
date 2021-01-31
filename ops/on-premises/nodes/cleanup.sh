#!/bin/bash

echo "==> Removing all unused images on $(hostname)."

sudo k3s crictl rmi --prune || true
