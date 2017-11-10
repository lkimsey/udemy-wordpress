
DB_CONTAINER=udemy-wp-db-container
CGI_CONTAINER=udemy-wp-cgi-container
DB_PASSWORD ?=udemy-wp

lamp: db cgi http


.PHONY: db cgi http lamp

db:
	-docker kill ${DB_CONTAINER}
	-docker rm -f ${DB_CONTAINER}

	docker run \
		-d \
		--name=${DB_CONTAINER} \
		-e MYSQL_ROOT_PASSWORD=${DB_PASSWORD} \
		-v ${HOME}/opt/mysql/udemy-wordpress:/var/lib/mysql \
		mariadb

cgi:
	-docker kill ${CGI_CONTAINER}
	-docker rm -f ${CGI_CONTAINER}

	docker run \
		-d \
		--name=${CGI_CONTAINER} \
		-v ${PWD}:${PWD} \
		-v /tmp:/tmp \
		udemy-wp:latest \
			php-fpm \
				-y ${PWD}/php-fpm.conf

http:
	-pkill caddy
	caddy
