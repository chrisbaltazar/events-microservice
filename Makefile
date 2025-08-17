SHELL:=/bin/bash

run:
	docker build -t events-micro-app .
	docker run --rm -d -p 10000:10000 --name events-micro-app events-micro-app

stop:
	docker stop events-micro-app

plans:
	docker exec events-micro-app php artisan app:plans:update

cache:
	docker exec events-micro-app php artisan cache:clear

enter:
	docker exec -it events-micro-app bash

logs:
	docker exec events-micro-app tail -f storage/logs/laravel.log

test:
	docker exec events-micro-app composer install && php artisan test
