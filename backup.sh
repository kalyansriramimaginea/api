#!/bin/bash

FILE_NAME=$1
MONGO_PORT=$2
MONGO_DB=$3
MONGO_USER=$4
MONGO_PW=$5

# Create backup
mongodump --db=$MONGO_DB --port=$MONGO_PORT -u=$MONGO_USER -p=$MONGO_PW

# Add timestamp to backup
mv dump storage/app/dump
cd storage/app
mv dump $FILE_NAME
tar -zcvf $FILE_NAME.tar.gz $FILE_NAME
rm -rf "$FILE_NAME"
