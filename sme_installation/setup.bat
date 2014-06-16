@echo off

echo initialize smeInventory database...
psql -U postgres -c "CREATE USER smesys WITH PASSWORD 'Inventory#sys' NOCREATEUSER;"
psql -U postgres -c "CREATE DATABASE smeinventory WITH OWNER = smesys ENCODING = 'UTF8';"
psql -U postgres -d smeinventory -f db_init.sql
