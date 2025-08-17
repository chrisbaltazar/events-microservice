SHELL:=/bin/bash

run:
	docker build -t fever-app .
	docker run --rm -d -p 10000:10000 --name fever-app fever-app

stop:
	docker stop fever-app

plans:
	docker exec fever-app php artisan app:plans:update

cache:
	docker exec fever-app php artisan cache:clear

enter:
	docker exec -it fever-app bash

logs:
	docker exec fever-app tail -f storage/logs/laravel.log

test:
	docker exec fever-app composer install && php artisan test
