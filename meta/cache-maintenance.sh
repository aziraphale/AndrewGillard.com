#!/bin/bash

rm -rf ../public/cached/*

cd /tmp
wget -r -p -l 0 -nd --delete-after http://www.andrewgillard.com/


