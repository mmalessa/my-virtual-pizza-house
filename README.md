# My Virtual Pizza House
A space to explore modular asynchrony in PHP   
...on the example of a pizza house.

## Kickstart
```shell
make up
make console

# inside console
composer install
```

## Start the machinery
```shell
# inside 1th docker console
./bin/console messenger:consume order_manager_transport

# inside 2nd docker console
./bin/console messenger:consume waiter_transport

# inside 3rd docker console
./bin/console app:waiter:place-order
```
Nothing spectacular, but... it works!

You can watch RabbitMQ queues: http://localhost:1567 (user/user)

## Tests
Inside console
```shell
make tests-unit
make tests-coverage
make tests-mutation # see var/infection-logs.html
```

## Tools
Inside console
#### Rector instantly upgrades and refactors the PHP code of your application
https://github.com/rectorphp/rector
```shell
make rector
```
