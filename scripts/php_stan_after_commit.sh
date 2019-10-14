#!/bin/bash
set -eu
if [ $(git --no-pager diff --name-only --diff-filter=ACM HEAD -- | grep '.php$' | xargs -n1 | wc -w) -ne 0 ]
  then
    FILES=$(git --no-pager diff --name-only --diff-filter=ACM HEAD -- | grep '.php$')
    docker-compose -f docker/docker-compose.yml exec -T php vendor/bin/phpstan analyse --level 5 $FILES
fi
