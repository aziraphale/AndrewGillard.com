#!/bin/bash

cd /tmp

rm -rf ../public/cached/*

wget -r -p -l 0 -nd --delete-after http://www.andrewgillard.com/


