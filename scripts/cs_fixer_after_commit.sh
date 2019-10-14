#!/bin/bash
set -eu
if [ $(git --no-pager diff --name-only --diff-filter=ACM HEAD -- | grep '.php$' | xargs -n1 | wc -w) -ne 0 ]
  then
    FILES=$(git --no-pager diff --name-only --diff-filter=ACM HEAD -- | grep '.php$')
    docker-compose -f docker/docker-compose.yml exec -T php vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix --config=scripts/.php_cs.dist --dry-run --using-cache=no $FILES
fi
