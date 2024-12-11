#!/bin/bash
path=$1

#
# Use the optional --continue flag to run Webpack without breaking on warnings/errors
#
continueFlag=$2

if [ -z "$path" ] || [ ! -d "$path" ]; then
    echo "usage: ./buildHook.sh pathProjectRoot"
    echo "for example: ./buildHook.sh \$PWD"
    exit
fi

if [ -f ~/.nvm/nvm.sh ]; then
    . ~/.nvm/nvm.sh
fi

echo "====== Running build hook ======="
nvm use 20

for app in admin
do
    echo "=== $app start ==="
    cd $path/resources/assets/$app

    if [ -f package.json ]; then
        find . -maxdepth 1 -name package.json | grep package > /dev/null 2>&1
        if [ $? == 0 ]; then
            echo "= PNPM install ="
            pnpm install --frozen-lockfile --no-color
            if [ $? != 0 ]; then
                exit 1
            fi
        fi

        if [ $continueFlag ]; then
            echo "= Build dev ="
            pnpm run build-dev --no-color
        else
            echo "= Build production ="
            pnpm run build --no-color
        fi
        if [ $? != 0 ]; then
            exit 1
        fi
    else
        echo "Package.json doesn't exist"
    fi
done

nvm use default
echo "================================="
