yarn.install:
	docker run -it --rm -v $(shell pwd)/../..:/app -w /app/packages/icon-nova-field node:16-alpine yarn install

yarn.watch:
	docker run -it --rm --user $(shell id -u):$(shell id -g) -v $(shell pwd)/../..:/app -w /app/packages/icon-nova-field node:16-alpine yarn watch

yarn.dev:
	docker run -it --rm --user $(shell id -u):$(shell id -g) -v $(shell pwd)/../..:/app -w /app/packages/icon-nova-field node:16-alpine yarn dev

yarn.prod:
	docker run -it --rm --user $(shell id -u):$(shell id -g) -v $(shell pwd)/../..:/app -w /app/packages/icon-nova-field node:16-alpine yarn prod
