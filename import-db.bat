@echo off
docker exec bestphone4you_mariadb-server_1 "/docker-entrypoint-initdb.d/import-db.sh"
echo Import del DB completato.
pause