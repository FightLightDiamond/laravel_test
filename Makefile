ifndef u
u:=l9
endif

ifndef env
env:=dev
endif

OS:=$(shell uname)

docker-start:
	./vendor/bin/sail up

docker-restart:
	./vendor/bin/sail down
	make docker-start
	make docker-init-db-full
	make docker-link-storage

docker-connect:
	docker exec -it laravel_test_laravel.test_1 bash

init-app:
	cp .env.example .env
	composer install
	php artisan key:generate --force
	php artisan passport:keys --force
	php artisan migrate
	php artisan db:seed
	php artisan storage:link

docker-init:
	docker exec -it laravel_test_laravel.test_1 make init-app
	rm -rf node_modules
	npm install

init-db-full:
	make autoload
	php artisan migrate:fresh
	make update-master
	php artisan db:seed

docker-init-db-full:
	docker exec -it laravel_test_laravel.test_1 make init-db-full

docker-link-storage:
	docker exec -it laravel_test_laravel.test_1 php artisan storage:link

init-db:
	make autoload
	php artisan migrate:fresh

start:
	php artisan serve

log:
	tail -f storage/logs/laravel.log

test-js:
	npm test

test-php:
	vendor/bin/phpcs --standard=phpcs.xml && vendor/bin/phpmd app text phpmd.xml

build:
	npm run dev

watch:
	npm run watch

docker-watch:
	docker exec -it laravel_test_laravel.test_1 make watch

autoload:
	composer dump-autoload

cache:
	php artisan cache:clear && php artisan view:clear

docker-cache:
	docker exec laravel_test_laravel.test_1 make cache

route:
	php artisan route:list

generate-master:
	php bin/generate_master.php $(lang)

update-master:
	php artisan master:update $(lang)
	make cache

as:
	alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
