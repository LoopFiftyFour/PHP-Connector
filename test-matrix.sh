#!/usr/bin/bash

test () {
    echo -e "\e[47;30mRunning tests for PHP version $1\e[0m"
    docker run --rm -it $(docker build -q --build-arg PHP_VERSION=$1 .)
    exit_code=$?
    if [ $exit_code -ne 0 ]; then
        echo -e "\e[41mTests failed for PHP version $1\e[0m"
        exit $exit_code
    fi
    echo -e "\e[42;30mTests finished for PHP version $1\e[0m"
}

test 7.4
test 8.0
test 8.1
test 8.2
test 8.3
test 8.4
