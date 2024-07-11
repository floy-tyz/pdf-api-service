FROM node:16 AS build

WORKDIR /app

COPY . /app

FROM nginx:alpine

RUN apk --update --no-cache add \
    vim \
    bash

COPY --from=build --chown=www-data:www-data /app/public /var/www/html/public

CMD ["nginx", "-g", "daemon off;"]