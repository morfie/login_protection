Install guide
========================

requires: mysql, mongodb

### install dependencies:
 
```
composer install
```

### create database schema:
```
bin/console doctrine:schema:update --force 
bin/console doctrine:mongodb:schema:update
```

### start webserver:

```
bin/console server:run
```