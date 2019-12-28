#!/bin/bash

PHPCS_BIN=./vendor/bin/phpcs

if [ ! -x $PHPCS_BIN ];
then
    echo "PHP CodeSniffer bin not found or executable -> $PHPCS_BIN"
    echo "Run 'composer update' first"
    exit 1
fi

$PHPCS_BIN -p --encoding=utf-8
RETVAL=$?

if [ $RETVAL -ne 0 ];
then
    echo "Code sniffer return code '"$RETVAL"'; check your code"
    echo "You can try to run './vendor/bin/phpcbf' to fix errors automatically"
    echo ""
    echo "Do not forget run 'git add'"
fi

exit $RETVAL
