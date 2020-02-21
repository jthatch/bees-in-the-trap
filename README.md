# bees-in-the-trap
A php7/phpspec solution to the Bees in the Trap technical test

## Requirements
##### non-docker:
- PHP 7.1 or later, [composer](https://getcomposer.org/)   
##### docker:
- [Make](https://www.gnu.org/software/make/) and [Docker](https://www.docker.com)

## Installation (non-docker):
- `git clone git@github.com:jthatch/bees-in-the-trap.git`
- `cd bees-in-the-trap && composer install`

## Usage
##### non-docker:
- `./beesinthetrap.sh`
##### docker:
- `make build`
- `make run`

## Example output
![example-output](resources/bees-output.png)

## Tests
Tests are written in BDD style using `phpspec`
##### non-docker:
- `./beesinthetrap.sh`
##### docker:
- `make test`

## Make commands
I've added a few extra `make` commands to make local dev and testing easier.

```bash
help                           This help.
build                          Build docker image
run                            Run the game
test                           Run the testsuite
run-dev                        Run using local workspace
test-dev                       Run tests using local workspace
clean                          Clean old docker images not attached
```