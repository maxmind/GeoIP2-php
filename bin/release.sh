#!/bin/bash

TAG=$1

if [ -z $TAG ]; then
    echo "Please specify a tag"
    exit 1
fi

if [ -n "$(git status --porcelain)" ]; then
    echo ". is not clean." >&2
    exit 1
fi

if [ ! -d .gh-pages ]; then
    echo "Checking out gh-pages in .gh-pages"
    git clone -b gh-pages git@git.maxmind.com:GeoIP2-php .gh-pages
    cd .gh-pages
else
    echo "Updating .gh-pages"
    cd .gh-pages
    git pull
fi

if [ -n "$(git status --porcelain)" ]; then
    echo ".gh-pages is not clean" >&2
    exit 1
fi

../vendor/bin/apigen --quiet --download --title "GeoIP2 PHP API $TAG" --source ../src --destination doc/$TAG

PAGE=index.md
cat <<EOF > $PAGE
---
layout: default
title: MaxMind GeoIP2 PHP API
language: php
version: $TAG
---

EOF

cat ../README.md >> $PAGE

git add doc/
git commit -m "Updated for $TAG" -a

read -e -p "Push to origin? " SHOULD_PUSH

if [ "$SHOULD_PUSH" != "y" ]; then
    echo "Aborting"
    exit 1
fi

# If we don't push directly to github, the page doesn't get built for some
# reason.
git push git@github.com:maxmind/GeoIP2-php.git
git push

cd ..
git tag -a $TAG
git push
git push --tags
