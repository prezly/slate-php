test: vendor
	vendor/bin/phpunit

vendor:
	composer install
