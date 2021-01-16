#!/bin/sh
mysql -uroot -pdocker bp4ydb < /docker-entrypoint-initdb.d/bp4y-db.sql