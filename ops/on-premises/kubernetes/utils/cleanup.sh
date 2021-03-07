#!/bin/bash

#######################################
# script for cleaning up worker nodes #
#######################################

echo "==> Removing all unused images on $(hostname)." 

docker system prune -f || true
