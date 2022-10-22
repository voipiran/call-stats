#!/bin/bash

rootpw=$(sed -ne 's/.*mysqlrootpwd=//gp' /etc/issabel.conf)

mkdir -p /usr/src/callcenter-stats

##cp -rf html /usr/src/callcenter-stats
cp -rf queue-parser/parselog /usr/src/callcenter-stats
cp -rf queue-parser/sql /usr/src/callcenter-stats

##mysqladmin -u root -p$rootpw create qstats

mysql -uroot -p$rootpw -e "DROP DATABASE IF EXISTS qstatslite;"
mysql -uroot -p$rootpw -e "CREATE DATABASE qstatslite DEFAULT CHARACTER SET utf8 COLLATE utf8_persian_ci;"
mysql -uroot -p$rootpw -e "GRANT ALL PRIVILEGES ON qstatslite.* TO 'root'@'localhost';"
mysql -u root -p$rootpw qstatslite < /usr/src/callcenter-stats/sql/qstats.sql 

##sed -i "s/123456/${rootpw}/g" /usr/src/callcenter-stats/html/config.php
sed -i "s/123456/${rootpw}/g" /usr/src/callcenter-stats/parselog/config.php

##mkdir -p /var/www/html/callcenter-stats   > /dev/null
##cp -rf /usr/src/callcenter-stats/html/* /var/www/html/callcenter-stats
##rm -rf /usr/src/callcenter-stats/html

cp -rf /usr/src/callcenter-stats/parselog /usr/local
rm -rf /usr/src/callcenter-stats/parselog


#yes | mv -f /usr/src/callcenter-stats/html /var/www/html/callcenter-stats
#yes | mv -f /usr/src/callcenter-stats/parselog /usr/local

(crontab -l && echo "* * * * * php -q /usr/local/parselog/parselog.php convertlocal") | crontab -

php /usr/local/parselog/parselog.php convertlocal
