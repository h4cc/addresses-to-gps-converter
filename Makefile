.PHONY: hello install convert

hello:
	@echo "Hi, see targets install and convert"

install:
	php composer.phar install

convert:
	php convert.php from.csv to.csv
