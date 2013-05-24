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
    echo "Updating .ghpages"
    cd .gh-pages
    git pull
fi

if [ -n "$(git status --porcelain)" ]; then
    echo ".gh-pages is not clean" >&2
    exit 1
fi

cp ../README.md _includes/README.md
apigen --quiet --source ../src --destination doc/$TAG

git add doc/
git commit -m "Updated for $TAG"

read -e -p "Push to origin? " SHOULD_PUSH

if [ "$SHOULD_PUSH" != "y" ]; then
    echo "Aborting"
    exit 1
fi

git push
cd ..
git tag $TAG
git push
git push --tags
