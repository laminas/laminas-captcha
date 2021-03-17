#!/bin/bash

set -e

# Install ircmaxell/random-lib on PHP 5.6 lowest
PATTERN="PHP 5"
if [[ "$(php --version)" =~ ${PATTERN} ]];then
    PATTERN="2.7"
    if [[ "$(composer show --no-ansi | grep laminas-math)" =~ ${PATTERN} ]];then
        composer require "ircmaxell/random-lib:~1.1.0"
    fi
fi
