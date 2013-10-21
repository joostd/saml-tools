composer.phar:
	curl -sS https://getcomposer.org/installer | php

install: composer.phar
	./composer.phar install
