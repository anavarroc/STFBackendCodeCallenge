
PRECOMMIT_FILES := $(shell git --no-pager diff --name-only --diff-filter=ACM HEAD -- | grep .php$$)
DOCKER_COMPOSE := docker-compose -f docker/docker-compose.yml
CS_FIXER := vendor/friendsofphp/php-cs-fixer/php-cs-fixer
PHP_UNIT := ./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox
INIT_FOLDERS = sh ./scripts/folders.sh
.SILENT: cs-fix-all cs-check cs-fix

uid:
    UID := $(shell id -u)
    export UID

#Docker-compose

run:
	$(INIT_FOLDERS) \
	&& $(DOCKER_COMPOSE) up --build -d
down:
	$(DOCKER_COMPOSE) down
kill:
	$(DOCKER_COMPOSE) kill
recreate:
	$(DOCKER_COMPOSE) create --force-recreate --build

#PHP - Composer

composer-install:
	$(DOCKER_COMPOSE) exec php composer install
composer-update:
	$(DOCKER_COMPOSE) exec php composer update

#CS-Fixer

cs-fix-all:
	$(DOCKER_COMPOSE) exec php $(CS_FIXER) fix --config=scripts/.php_cs.dist --using-cache=no
cs-check:
	$(DOCKER_COMPOSE) exec php $(CS_FIXER) fix --config=scripts/.php_cs.dist --dry-run --using-cache=no
cs-fix:
	if [ $(words ${PRECOMMIT_FILES}) -gt 0 ]; then \
			$(DOCKER_COMPOSE) exec php $(CS_FIXER) fix --config=scripts/.php_cs.dist --using-cache=no $(PRECOMMIT_FILES);\
	fi

#PHPUnit:

php-unit-all:
	$(DOCKER_COMPOSE) exec php $(PHP_UNIT) --do-not-cache-result
php-unit-unit:
	$(DOCKER_COMPOSE) exec php $(PHP_UNIT) --testsuite Unit --do-not-cache-result
php-unit-api:
	$(DOCKER_COMPOSE) exec php $(PHP_UNIT) --testsuite Api --do-not-cache-result

#PHPStan:

php-stan:
	$(DOCKER_COMPOSE) exec php vendor/bin/phpstan analyse src tests --level 5

#Nginx

nginx-access-log:
	$(DOCKER_COMPOSE) exec web tail -f /var/log/nginx/access.log
nginx-error-log:
	$(DOCKER_COMPOSE) exec web tail -f /var/log/nginx/error.log