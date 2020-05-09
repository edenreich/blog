FROM alpine:latest

WORKDIR /root

RUN apk --update --no-cache add openssl

COPY ops/docker/helpers/openssl/generate_ssl.sh /root/generate_ssl.sh
RUN chmod +x generate_ssl.sh

CMD [ "./generate_ssl.sh" ]