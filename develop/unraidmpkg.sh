#!/bin/bash

BASEDIR=$PWD


cd ../source/NerdTools 
./pkg_build.sh $1


version=$(date +"%Y.%m.%d")$1

echo "VERSION: $version"

echo "${BASH_SOURCE[0]}"

# CALCULATE MD5
md5=($(md5sum ${BASEDIR}/../archive/NerdTools-${version}-x86_64-1.txz))
echo ${md5}

sed -i "s/ENTITY version   .*/ENTITY version   \"$version\">"/ "$BASEDIR/../plugin/NerdTools.plg"
sed -i "s/ENTITY md5       .*/ENTITY md5       \"$md5\">"/ "$BASEDIR/../plugin/NerdTools.plg"

