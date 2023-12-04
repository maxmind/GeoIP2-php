#!/bin/bash

set -eu -o pipefail

phar='geoip2.phar'

changelog=$(cat CHANGELOG.md)

regex='
([0-9]+\.[0-9]+\.[0-9]+) \(([0-9]{4}-[0-9]{2}-[0-9]{2})\)
-*

((.|
)*)
'

if [[ ! $changelog =~ $regex ]]; then
      echo "Could not find date line in change log!"
      exit 1
fi

version="${BASH_REMATCH[1]}"
date="${BASH_REMATCH[2]}"
notes="$(echo "${BASH_REMATCH[3]}" | sed -n -e '/^[0-9]\+\.[0-9]\+\.[0-9]\+/,$!p')"

if [[ "$date" !=  $(date +"%Y-%m-%d") ]]; then
    echo "$date is not today!"
    exit 1
fi

tag="v$version"

rm -f "$phar"

if [ -n "$(git status --porcelain)" ]; then
    echo ". is not clean." >&2
    exit 1
fi

rm -fr vendor

php composer.phar self-update
php composer.phar update --no-dev

perl -pi -e "s/(?<=const VERSION = ').+?(?=';)/$tag/g" src/WebService/Client.php


box_phar_hash='c24c400c424a68041d7af146c71943bf1acc0c5abafa45297c503b832b9c6b16  box.phar'

if ! echo "$box_phar_hash" | sha256sum -c; then
    wget -O box.phar "https://github.com/box-project/box/releases/download/4.5.1/box.phar"
fi

echo "$box_phar_hash" | sha256sum -c

php box.phar compile

phar_test=$(./dev-bin/phar-test.php)
if [[ -n $phar_test ]]; then
    echo "Phar test outputed non-empty string: $phar_test"
    exit 1
fi

# Download test deps
php composer.phar update

./vendor/bin/phpunit

if [ ! -d .gh-pages ]; then
    echo "Checking out gh-pages in .gh-pages"
    git clone -b gh-pages git@github.com:maxmind/GeoIP2-php.git .gh-pages
    pushd .gh-pages
else
    echo "Updating .gh-pages"
    pushd .gh-pages
    git pull
fi

if [ ! -d .maxminddb ]; then
    echo "Cloning MaxMind-DB for docs"
    git clone git@github.com:maxmind/MaxMind-DB-Reader-php.git .maxminddb
else
    echo "Updating MaxMind-DB for docs"
    pushd .maxminddb
    git pull
    popd
fi


if [ -n "$(git status --porcelain)" ]; then
    echo ".gh-pages is not clean" >&2
    exit 1
fi

# Using Composer is possible, but they don't recommend it.
phpdocumentor_phar_hash='bad7e4b8c99e73391bb3183a127593ecd1cd66ae42b4a33efe495d193e257f04  phpDocumentor.phar'

if ! echo "$phpdocumentor_phar_hash" | sha256sum -c; then
    wget -O phpDocumentor.phar https://github.com/phpDocumentor/phpDocumentor/releases/download/v3.4.3/phpDocumentor.phar
fi

echo "$phpdocumentor_phar_hash" | sha256sum -c

# Use cache dir in /tmp as otherwise cache files get into the output directory.
cachedir="/tmp/phpdoc-$$-$RANDOM"
rm -rf "$cachedir"

php phpDocumentor.phar \
    --visibility=public \
    --cache-folder="$cachedir" \
    --title="GeoIP2 PHP API $tag" \
    run \
    -d "$PWD/../src" \
    -t "doc/$tag"
# This used to work but doesn't as of 4.5.1. They say that they are working
# on fixing it. Neither the config file nor the relative path fix work as
# suggested either.
#    -d "$PWD/.maxminddb/src" \

rm -rf "$cachedir"

page=index.md
cat <<EOF > $page
---
layout: default
title: MaxMind GeoIP2 PHP API
language: php
version: $tag
---

EOF

cat ../README.md >> $page

git add doc/

echo "Release notes for $tag:"
echo "$notes"

read -e -p "Commit changes and push to origin? " should_push

if [ "$should_push" != "y" ]; then
    echo "Aborting"
    exit 1
fi

git commit -m "Updated for $tag" -a
git push

popd

git commit -m "Update for $tag" -a

git push

gh release create --target "$(git branch --show-current)" -t "$version" -n "$notes" "$tag" "$phar"

git push --tags
