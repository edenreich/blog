#!/bin/sh

if [ ! -z "$(ls /root/certs)" ]; then
    echo "self signed certificate already exists..skipping"
    exit 0
fi

openssl genrsa -out ./certs/server.key 4096

openssl req -new -x509 -sha256 -days 3650 -key ./certs/server.key -config ./openssl.cnf -out ./certs/server.crt

openssl x509 -in ./certs/server.crt -text -noout
