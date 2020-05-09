FROM nginx:alpine

COPY ops/docker/ingress/nginx.conf /etc/nginx/nginx.conf
COPY ops/docker/ingress/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80 443