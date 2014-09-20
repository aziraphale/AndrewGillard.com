#!/bin/bash

cd /var/www/andrewgillard.com/meta/
rm -rf ../public/cached/*

cd /tmp
wget -q -r -p -l 0 -nd --delete-after http://www.andrewgillard.com/


