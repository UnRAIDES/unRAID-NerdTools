#!/bin/bash

BASEDIR=$PWD


cd ../source/NerdPack 
./pkg_build.sh $1


version=$(date +"%Y.%m.%d")$1

echo "VERSION: $version"

echo "${BASH_SOURCE[0]}"


sed -i "s/ENTITY version   .*/ENTITY version   \"$version\">"/ "$BASEDIR/../plugin/NerdTools.plg"