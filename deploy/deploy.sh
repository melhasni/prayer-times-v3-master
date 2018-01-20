#!/bin/bash

# Exit on first error
set -e

if [ $# -lt 2 ]; then
    echo "env and tag are mandatory"
    exit;
fi

env=$1
tag=$2

echo -n "Are you sur you want to deploy $tag to $env (y/n)? "
read answer

if echo "$answer" | grep -iq "^y" ;then
    server="137.74.45.69"
    port="1983"

    git tag $tag -m "new release $2" || true
    git push
    git push origin $tag

    ssh -p $port mawaqit@$server 'bash -s' < deploy/install.sh $env $tag
fi