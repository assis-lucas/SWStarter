FROM node:22-alpine AS builder

WORKDIR /app
COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

FROM nginx:1.25-alpine

COPY --from=builder /app/dist /usr/share/nginx/html
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

RUN addgroup -g 1001 -S nodejs && adduser -S www-data -u 1001

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]