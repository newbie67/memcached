### Memcached library
This library must implements add/delete/set command for memcached web-server https://github.com/memcached/memcached/blob/master/doc/protocol.txt


Install dependencies
```bash
composer install
```

### Development env

#### PHP code sniffer

For check code style on git commit automatically:
```bash

# Third: check phpcs for working
./vendor/bin/phpcs -p

# And finally copy git hook
cp resources/phpcs-pre-commit.sh .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit
```

If you use docker, you shoud enter this commands inside container