#!/bin/bash
# if RUN_MIGRATION exist and si true, then run the migration
if [[ -n "${RUN_MIGRATION}" ]] && [[ $RUN_MIGRATION == "true" ]]; then
  echo "RUN_MIGRATION is set"
  cd /var/www || exit;
  if [[ $(id -u) == 0 ]]; then
    su www-data -s /bin/bash -c "/usr/local/bin/php bin/console doctrine:migrations:migrate --no-interaction"
  else
    /usr/local/bin/php bin/console doctrine:migrations:migrate --no-interaction
  fi
fi
if [[ -n "${RUN_CACHE}" ]] && [[ $RUN_CACHE == "true" ]]; then
  echo "RUN_CACHE is set"
  # run as www-data user
  if [[ $(id -u) == 0 ]]; then
    cd /var/www && su www-data -s /bin/bash -c "/usr/local/bin/php bin/console cache:clear"
  else
    cd /var/www && /usr/local/bin/php bin/console cache:clear
  fi
fi
if [[ -n "${RUN_COMPOSER}" ]] && [[ $RUN_COMPOSER == "true" ]]; then
  echo "RUN_COMPOSER is set"
  if [[ $(id -u) == 0 ]]; then
    cd /var/www && su www-data -s /bin/bash -c "/usr/bin/composer install --no-dev --no-interaction --no-progress --no-suggest"
    cd /var/www && su www-data -s /bin/bash -c "/usr/bin/composer dump-autoload --no-dev --classmap-authoritative"
  else
    cd /var/www && /usr/bin/composer install --no-dev --no-interaction --no-progress --no-suggest
    cd /var/www && /usr/bin/composer dump-autoload --no-dev --classmap-authoritative
  fi
fi

if [[ "$RUN_SUPERVISOR" == 'true' ]]
then
  echo "Starting supervisord"
  supervisord
else
  echo "Starting php-fpm"
  php-fpm
fi
