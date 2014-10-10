#!/bin/sh

mysql -u root -p < mysql-wordnet21-createdb-1.0.0.sql
mysql -u root -p < mysql-wordnet21-schema-1.0.0.sql wordnet21
mysql -u root -p < mysql-wordnet21-data-1.0.0.sql wordnet21
mysql -u root -p < mysql-wordnet20-constraints-1.0.0.sql wordnet20
