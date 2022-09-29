#!/bin/bash

cd source/NerdPack 


./pkg_build.sh $1


version=$(date +"%Y.%m.%d")$1

echo "VERSION: $version"