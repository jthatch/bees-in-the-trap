# Bees In The Trap - PHP7 Solution
A php7.4 solution to the Bees in the Trap technical test by [James Thatcher](https://github.com/jthatch) 2020.   
Uses Domain-driven and factory design patterns.

## Requirements
##### non-docker:
- PHP 7.4 or later, [composer](https://getcomposer.org/)   
##### docker:
- [Make](https://www.gnu.org/software/make/) and [Docker](https://www.docker.com)

## Installation (non-docker):
- `git clone git@github.com:jthatch/bees-in-the-trap.git`
- `cd bees-in-the-trap && composer install`

## Usage
##### non-docker:
- `./beesinthetrap`
##### docker:
- `make build`
- `make run`

## Example output
*Playing a small game* 
![example-output](resources/bees-output.png)

## Tests     
##### non-docker:
- `./bin/phpunit -c phpunit.xml.dist`
##### docker:
- `make test`
