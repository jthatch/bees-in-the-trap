.PHONY: all

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

# basic vars
current-dir   := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
image-name    :=$(shell basename $(PWD))
image-version :=$(shell git describe --abbrev=0 --tags --exact-match 2>/dev/null || git rev-parse --short HEAD)

build: ## Build docker image
	docker build \
		--file=Dockerfile \
		--tag="$(image-name):$(image-version)" .

run: ## Run the game
	docker run -it --rm \
		--name $(image-name) \
		"$(image-name):$(image-version)"

test: ## Run the testsuite
	docker run -it --rm \
		--name $(image-name) \
		-w /app \
		-e "SIMULATIONS=${SIMULATIONS}" \
		"$(image-name):$(image-version)" \
		php bin/phpspec run --format pretty

run-verbose: ## Run in container using verbose
	docker run -it --rm \
		--name $(image-name) \
		"$(image-name):$(image-version)" \
		/bin/ash beesinthetrap.sh -v

run-dev: ## Run using local workspace
	docker run -it --rm \
		--name $(image-name) \
		-v "$(PWD)":/app \
		-w /app \
		"$(image-name):$(image-version)" \
		/bin/ash beesinthetrap.sh

test-dev: ## Run tests using local workspace
	docker run -it --rm \
		--name $(image-name) \
		-v "$(PWD)":/app \
		"$(image-name):$(image-version)" \
		php bin/phpspec run --format pretty


clean: ## Clean old docker images not attached
	docker rm $(docker ps -a -q);docker rmi $(docker images | grep "^<none>" | awk '{print $3}')