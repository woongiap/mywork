#!/bin/bash

find $1 -name $2 | xargs perl -n -e 'print unless /^\s*$/'| wc -l
