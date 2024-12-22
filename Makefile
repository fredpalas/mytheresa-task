current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
docker-dir := $(current-dir)docker/

.PHONY: onboard
onboard: | setup docker-start composer-install migrations fixtures phpstorm-coverage-folder
	@echo "🎉🎉 Onboarding complete!"

.PHONY: setup
setup: | init docker-pull docker-build
	@echo "🎉🎉 Setup complete!"

.PHONY: init
init:
	@echo "⚙️ Initializing repository"
	@$(current-dir)/exec init
	@echo "👌 repository initialized!"
	@echo "";

.PHONY: docker-build
docker-build:
	@echo "🐳👷 building docker images, hold tight, this is gonna take a while..."
	@$(current-dir)/exec build
	@echo "🐳👷 docker images built!"
	@echo "";

.PHONY: docker-pull
docker-pull:
	@echo "🐳👷 pulling docker images, hold tight, this is gonna take a while..."
	@$(current-dir)/exec pull
	@echo "🐳👷 docker images pulled!"
	@echo "";

.PHONY: docker-start
docker-start:
	@echo "🐳🚀 starting docker services"
	@$(current-dir)/exec start
	@echo "👌 docker services started!"
	@echo "";

.PHONY: docker-stop
docker-stop:
	@echo "🐳🛑 stopping docker services"
	@$(current-dir)/exec stop
	@echo "👌 docker services stopped!"
	@echo "";

.PHONY: docker-restart
docker-restart:
	@echo "🐳🔄 restarting docker services"
	@$(current-dir)/exec restart
	@echo "👌 docker services restarted!"
	@echo "";

.PHONY: docker-down
docker-down:
	@echo "🐳🔥 tearing down docker services"
	@$(current-dir)/exec down
	@echo "👌 docker services torn down!"
	@echo "";

.PHONY: phpunit
phpunit:
	@echo "🐘🧪 running phpunit tests"
	@$(current-dir)/exec test
	@echo "👌 phpunit tests passed!"
	@echo "";

.PHONY: composer-install
composer-install:
	@echo "🐘📦 Installing composer dependencies"
	@$(current-dir)/exec composer install
	@echo "👌 dependencies installed!"
	@echo "";

.PHONY: php-bash
php-bash:
	@echo "🐘🐚 starting php bash"
	@$(current-dir)/exec exec php-fpm bash
	@echo "👌 php bash started!"
	@echo "";

.PHONY: phpstorm-coverage-folder
phpstorm-coverage-folder:
	@echo "🐘📦 creating coverage folder for phpstorm"
	@$(current-dir)/exec exec -u root php-fpm bash -c "mkdir -p /opt/phpstorm-coverage"
	@$(current-dir)/exec exec -u root php-fpm bash -c "chown -R www-data:www-data /opt/phpstorm-coverage"
	@echo "👌 coverage folder created!"
	@echo "";

.PHONY: migrations
migrations:
	@echo "🐘📦 running migrations"
	@$(current-dir)/exec php bin/console doctrine:migrations:migrate --no-interaction
	@echo "👌 migrations ran!"
	@echo "";

.PHONY: php-cs-check
php-cs-check:
	@echo "🐘👷 checking code style"
	@$(current-dir)/exec composer cs-check
	@echo "👌 code style checked!"
	@echo "";

.PHONY: php-run-worker
php-run-worker:
	@echo "🐘👷 running worker"
	@$(current-dir)/exec php bin/console messenger:consume ebsqs
	@echo "👌 worker ran!"
	@echo "";

.PHONY: php-api-doc
php-api-doc:
	@echo "🐘👷 generating api documentation"
	@$(current-dir)/exec php bin/console nelmio:apidoc:dump --format=json > apidoc/swagger_api.json
	@echo "👌 documentation ready!"
	@echo "";

.PHONY: fixtures
fixtures:
	@echo "🐘📦 loading fixtures"
	@$(current-dir)/exec php bin/console doctrine:fixtures:load --no-interaction --group=init
	@echo "👌 fixtures loaded!"
	@echo "";
