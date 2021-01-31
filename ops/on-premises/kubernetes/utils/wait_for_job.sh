#!/bin/sh

if [ -z $1 ]
then
    echo "Missing argument - Job Name"
    exit 1
fi

kubectl wait --for=condition=ContainersReady --timeout=60s pod --selector job-name=${1}
kubectl logs --follow job/${1} &
kubectl wait --for=condition=complete --timeout=180s job/${1}
