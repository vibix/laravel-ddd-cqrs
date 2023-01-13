git-update-dev:
	@echo fetching data from git
	-git fetch --all
	-git reset --hard origin/develop
	-git pull origin develop

git-update-master:
	-git fetch --all && git reset --hard origin/master && git pull origin master

init-composer-packages:
	@echo starting docker image to install composer packages
	-docker run --rm -u "$(shell id -u):$(shell id -g)" -v $(shell pwd):/opt -w /opt escolasoft/php:8.0-composer composer install --ignore-platform-reqs

envs-dev:
	@echo copying envs
	-cp .env.example .env

docker-up:
	@echo starting docker
	-./vendor/bin/sail up -d

docker-stop:
	@echo stoping docker images
	-./vendor/bin/sail stop

docker-down:
	@echo stoping and removing docker images
	-./vendor/bin/sail down

docker-build:
	@echo building docker images
	-./vendor/bin/sail build

docker-build-no-cache:
	@echo rebuilding docker images
	-./vendor/bin/sail build --no-cache

run-mailhog:
	@echo starting docker
	-./vendor/bin/sail up -d mailhog

restart-redis:
	-docker-compose restart redis

horizon-terminate:
	@echo restarting horizon
	-docker-compose exec -T -u sail backend bash -c "php artisan horizon:terminate"

horizon-start:
	@echo restarting horizon
	-docker-compose exec -T -u sail backend bash -c "php artisan horizon"

bash:
	@echo going to bash
	-./vendor/bin/sail shell

bash-root:
	@echo going to bash as root
	-./vendor/bin/sail root-shell

npm-install:
	@echo running npm install
	-docker-compose exec -T -u sail backend bash -c "npm install"

npm-install-prod:
	@echo running npm install
	-docker-compose exec -T -u sail backend bash -c "npm install --production"

npm-build-dev:
	@echo building npm dev package
	-docker-compose exec -T -u sail backend bash -c "npm run dev"

npm-build-prod:
	@echo building npm prod package
	-docker-compose exec -T -u sail backend bash -c "npm run prod"

clear-cache:
	@echo clearing backend cache
	-docker-compose exec -T -u sail backend bash -c "php artisan clear-compiled"

optimize:
	@echo optimizing
	-docker-compose exec -T -u sail backend bash -c "php artisan optimize:clear"

db-fresh:
	@echo refreshing database
	-docker-compose exec -T -u sail backend bash -c "php artisan migrate:fresh"

db-migrate:
	@echo migrating database
	-docker-compose exec -T -u sail backend bash -c "php artisan migrate --no-interaction --force"

db-seed:
	@echo seeding database
	-docker-compose exec -T -u sail backend bash -c "php artisan db:seed"

db-backup:
	-docker-compose exec -T pgsql pg_dumpall -c -U simple > ../release-db-backups/dump_`date +%d-%m-%Y"_"%H_%M_%S`.sql

entity-annotations:
	@echo updating entity annotations
	-docker-compose exec -T -u sail backend bash -c "php artisan ide-helper:model"

keys:
	@echo generating keys
	-docker-compose exec -T -u sail backend bash -c "php artisan key:generate"

create-personal-token:
	@echo create-personal-token
	-docker-compose exec -T -u sail backend bash -c "php artisan passport:client --personal"

composer-install:
	@echo installing composer packages
	-docker-compose exec -T -u sail backend bash -c "composer install --no-interaction"

api-doc-update:
	@echo api documentation
	-./vendor/bin/sail artisan scribe:generate

code-coverage:
	@echo code coverage
	-docker-compose exec -T -u sail backend bash -c "./vendor/bin/phpunit --coverage-html coverage"

test-unit:
	@echo run unit test
	-docker-compose exec -T -u root backend bash -c "./vendor/bin/phpstan --ansi"

test-phpstan:
	@echo run phpstan test
	-docker-compose exec -T -u root backend bash -c "./vendor/bin/phpstan --ansi"

test-insight:
	@echo run phpinsight test
	-docker-compose exec -T -u sail backend bash -c "php artisan insights --no-interaction --ansi"

test: test-unit test-phpstan test-insight

init-dev: envs-dev docker-up db-migrate keys create-personal-token npm-install npm-build-dev run-mailhog

refresh-development: docker-up composer-install npm-install db-migrate keys npm-build-dev clear-cache optimize horizon-terminate restart-redis horizon-start run-mailhog

refresh-development-with-db: docker-up composer-install npm-install db-fresh db-seed keys npm-build-dev clear-cache optimize horizon-terminate restart-redis horizon-start run-mailhog
