FROM node:16 AS build

WORKDIR /app

COPY . /app

RUN yarn install && yarn encore production

FROM nginx:alpine

RUN apk --update --no-cache add \
    vim \
    bash

COPY --from=build /app/public /var/www/html/public

CMD ["nginx", "-g", "daemon off;"]