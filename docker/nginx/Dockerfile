FROM public.ecr.aws/nginx/nginx:1.25 as base

COPY ./docker/nginx/conf.d /etc/nginx/conf.d

FROM base as prod
COPY ./public /var/www/public
