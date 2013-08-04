
.PHONY: php-fileops tests vendors

php-fileops: vendors
	php bin/build-phar.php

vendors:
	php bin/composer.phar update

tests: vendors
	phpunit

