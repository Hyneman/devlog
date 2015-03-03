.PHONY: npm bower composer grunt build serve

all: build

build: npm bower composer grunt

npm:
	npm install

bower:
	bower install

composer:
	composer --working-dir="./app/api" install

grunt:
	grunt build

serve:
	grunt serve
