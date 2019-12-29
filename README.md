[![Build Status](https://travis-ci.org/newbie67/memcached.svg?branch=master)](https://travis-ci.org/newbie67/memcached)

# Memcached library
This library must implements add/delete/set command for memcached web-server https://github.com/memcached/memcached/blob/master/doc/protocol.txt


Install dependencies
```bash
composer install
```

### Development env

If you use docker, you should enter this commands inside container

#### PHP code sniffer

For check code style on git commit automatically:
```bash

# Third: check phpcs for working
./vendor/bin/phpcs -p

# And finally copy git hook
cp resources/phpcs-pre-commit.sh .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit
```

If you use dev env or don't have php, you can manually edit it for
running inside docker container.

#### Test

For running tests:
```bash
vendor/bin/phpunit tests
```

# Комментарий
Мемкеш - это реальный сторадж.
Его невозможно тестировать, если клиент обращается к фейковому серверу.
Я понимаю, что в какой-то идеальной системе я должен написать имитацию
сервера, прокинуть её в мемкеш и тестировать с имитацией. Но таким
образом я ничего не проверю - т.к. реальный сервер может оказаться
совсем другой.


Могу немного подтвердить свои слова ссылками:
    - https://github.com/php-memcached-dev/php-memcached/blob/master/tests/config.inc Разработчики php-memcached думают так же
    - https://github.com/phpredis/phpredis/blob/develop/tests/make-cluster.sh Разработчики php-redis думают так же