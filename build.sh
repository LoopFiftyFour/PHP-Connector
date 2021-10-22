#!/bin/sh


do_publish=${1:-}
no_confirm=${2:-}

if [ -n "$do_publish" -a "$do_publish" != "publish" ]; then
    echo "Usage: $0 [publish [-f]]"
    echo ""
    echo "If executed with no arguments, runs a plain build without publishing."
    echo ""
    exit 1
fi

current_branch=$(git rev-parse --abbrev-ref HEAD)
if [ "$do_publish" = "publish" -a "$current_branch" != "master" ]; then
    echo "Expected to publish master branch, not $current_branch"
    exit 1
fi

composer >/dev/null 2>&1
if [ "$?" -eq 127 ]; then
    echo "Command 'composer' required, but not found. Is it installed?"
    exit 1
fi

if git log -1 --pretty=%B | head -n 1 | grep -E "^Release v[0-9.]+$"; then
    echo "Latest commit was already a release commit."
    exit 0
fi

if [ "$do_publish" = "publish" ]; then
    current_version=$(grep -Po '(?<="version": ")[0-9.]+(?=")' composer.json)
    major_minor=$(echo "$current_version" | cut -d. -f1-2)
    patch=$(echo "$current_version" | cut -d. -f3)
    next_patch=$(dc -e "$patch 1 + p")
    next_version="$major_minor.$next_patch"

    echo "Updating version from $current_version to $next_version in composer.json"
    sed -Ei 's/"version": "'"$current_version"'"/"version": "'"$next_version"'"/' composer.json
    echo "Updating version from $current_version to $next_version in lib/Client.php"
    sed -Ei "s/LIBVERSION = 'php:V3:$current_version'/LIBVERSION = 'php:V3:$next_version'/" lib/Client.php

    modified=$(git status --porcelain | grep -E ' M (composer.json|lib/Client.php)' | wc -l)
    if [ "$modified" -ne 2 ]; then
        echo "Expected substitutions to have affected composer.json and lib/Client.php, but this does not seem to have happened."
        echo "Maybe check git status to figure out what happened – and why the regexes in build.sh are incorrect."
        exit 1
    fi

    echo "Making a release commit"
    git add composer.json lib/Client.php
    git commit -m "Release v$next_version"
fi

echo "Installing dependencies to be able to run tests"
composer install

echo "Running tests"
./vendor/bin/phpunit test

if [ "$do_publish" = "publish" ]; then
    if [ "$?" -ne 0 ]; then
        echo "Some tests failed. Refusing to go on publishing"
        exit 1
    fi

    tag_name="v$next_version"
    echo "Tagging release commit"
    git tag "$tag_name" HEAD

    if [ "$no_confirm" = '-f' ]; then
        confirmation='y'
    else
        echo 'About to push tagged release commit to remote. This is final.'
        echo 'Are you sure you want to do this? y/N'
        echo '(Pass -f to script to not be asked.)'
        read confirmation
    fi

    if [ -n "$confirmation" -a $confirmation = 'y' ]; then
        git push origin HEAD
        git push origin "$tag_name"
    else
        echo 'Failed to get confirmation. Not pushing.'
        exit 0
    fi

fi
