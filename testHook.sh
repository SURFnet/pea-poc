#!/bin/bash
path=$1

if [ -z "$path" ] || [ ! -d "$path" ]; then
    echo "usage: ./testHook.sh pathProjectRoot"
    echo "for example: ./testHook.sh \$PWD"
    exit
fi

if [ -f ~/.nvm/nvm.sh ]; then
    . ~/.nvm/nvm.sh
fi

echo "====== Running tests ======="
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

        echo "= test ="
        npm run test --no-ansi --no-color
        if [ $? != 0 ]; then
            exit 1
        fi
    else
        echo "Package.json doesn't exist"
    fi
done

nvm use default
echo "================================="
