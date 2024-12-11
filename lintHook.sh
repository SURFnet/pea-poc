#!/bin/bash
path=$1

#
# Use the optional --continue flag to run Gulp without breaking on warnings/errors
#
continueFlag=$2

if [ -z "$path" ] || [ ! -d "$path" ]; then
    echo "usage: ./lintHook.sh pathProjectRoot"
    echo "for example: ./lintHook.sh \$PWD"
    exit
fi

if [ -f ~/.nvm/nvm.sh ]; then
    . ~/.nvm/nvm.sh
fi

echo "====== Running linters ======="
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

        echo "= Lint ="
        npm run lint --no-ansi --no-color
        if [ $? != 0 ]; then
            exit 1
        fi

        if [ $app == admin ]; then
            echo "= Copy paste detector ="
            npm run cpd --no-ansi --no-color
        fi
    else
        echo "Package.json doesn't exist"
    fi
done

nvm use default
echo "================================="
