COMPOSER_PHAR_VERSION = 1.8.5
COMPOSER_PHAR_CHECKSUM = 4e4c1cd74b54a26618699f3190e6f5fc63bb308b13fa660f71f2a2df047c0e17

test: vendor
	vendor/bin/phpunit

vendor: composer.phar
	php composer.phar install

composer.phar: Makefile
	curl https://getcomposer.org/download/$(COMPOSER_PHAR_VERSION)/composer.phar -o composer.phar.download
	echo "$(COMPOSER_PHAR_CHECKSUM)  composer.phar.download" | shasum -a 256 -c
	mv composer.phar.download -f composer.phar
