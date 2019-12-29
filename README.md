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

Memcached is a REAL storage. So, to test it you should use real
memcached server. It means that tests should be running inside docker
container and connect to real memcached server,
or edit your tests/config.php.