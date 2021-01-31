FROM nginx:alpine

COPY ops/on-premises/docker/ingress/nginx.conf /etc/nginx/nginx.conf
COPY ops/on-premises/docker/ingress/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80 443