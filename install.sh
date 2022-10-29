#!/bin/bash
echo "Install voipiran CallStats"
echo "VOIPIRAN.io"
sleep 1

#echo "------------START-----------------"
#echo "Install sourceguardian Files"
#echo "------------Copy SourceGaurd-----------------"
#yes | cp -rf installation/sourceguardian/ixed.5.4.lin /usr/lib64/php/modules
#yes | cp -rf installation/sourceguardian/ixed.5.4ts.lin /usr/lib64/php/modules
#yes | cp -rf /etc/php.ini /etc/php-old.ini
#yes | cp -rf installation/sourceguardian/php.ini /etc
#echo "SourceGuardian Files have Moved Sucsessfully"
#sleep 1

echo "-------------Installing Composer----------------"
##yum -y -q install php-cli php-zip wget unzip  > /dev/null
#php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
#php composer-setup.php --install-dir=/usr/local/bin --filename=composer  > /dev/null
yes | composer install
echo "Installing Composer Sucsessfully"
sleep 1

###Fetch DB root PASSWORD
rootpw=$(sed -ne 's/.*mysqlrootpwd=//gp' /etc/issabel.conf)

#Change DB Password
cp .env.example .env
sed -i "s/93Voipiran/${rootpw}/g" .env

###Install Database
echo "Install CallStats Mysql DataBase"
echo "------------Create DB-----------------"
mysql -uroot -p$rootpw < installation/voipiran_stats-database.sql
mysql -uroot -p$rootpw -e "GRANT ALL PRIVILEGES ON voipiran_stats.* TO 'root'@'localhost';"


echo "Copy Files"
echo "------------Files-----------------"
###Copy Files
mkdir -p /var/www/voipiran
mkdir -p /var/www/html/voipiran

rm -rf /var/www/html/voipiran/call-stats
mkdir /var/www/html/voipiran/call-stats

rm -rf /var/www/voipiran/stats
mkdir /var/www/voipiran/stats

yes | cp -avr public/* public/.htaccess /var/www/html/voipiran/call-stats > /dev/null
yes | cp -avr * .env /var/www/voipiran/stats > /dev/null
yes | rm -rf /var/www/voipiran/stats/public > /dev/null


###Add Permisions
chown -R asterisk:asterisk /var/www/voipiran/stats
#chown -R asterisk:asterisk /var/www/voipiran
#chown -R asterisk:asterisk /var/www/html/voipiran

echo '<Directory "/var/www/html/stats">' >> /etc/httpd/conf.d/issabel-htaccess.conf
echo 'AllowOverride All' >> /etc/httpd/conf.d/issabel-htaccess.conf
echo '</Directory>' >> /etc/httpd/conf.d/issabel-htaccess.conf

#Create Database
php /var/www/voipiran/stats/artisan cache:clear
yes | composer dump-autoload

#Asterisk Queue Adaptive
sed -i '/\[options\]/a queue\_adaptive\_realtime\=no' /etc/asterisk/asterisk.conf
sed -i '/\[options\]/a log\_membername\_as\_agent\=no' /etc/asterisk/asterisk.conf

### Add ODBC 
echo "-------------odbc.ini----------------"
echo "" >> /etc/odbc.ini
echo "[voipiran_stats]" >> /etc/odbc.ini
echo "driver=MySQL ODBC 5.3 ANSI Driver" >> /etc/odbc.ini
echo "server=localhost" >> /etc/odbc.ini
echo "database=voipiran_stats" >> /etc/odbc.ini
echo "Port=3306" >> /etc/odbc.ini
echo "Socket=/var/lib/mysql/mysql.sock" >> /etc/odbc.ini
echo "option=3" >> /etc/odbc.ini
echo "charset=utf8" >> /etc/odbc.ini

### Add ODBC 
echo "-------------res_odbc_custom.conf----------------"
echo "" >> /etc/asterisk/res_odbc_custom.conf
echo "[voipiran_stats]" >> /etc/asterisk/res_odbc_custom.conf
echo "enabled=>yes" >> /etc/asterisk/res_odbc_custom.conf
echo "dsn=>voipiran_stats" >> /etc/asterisk/res_odbc_custom.conf
echo "pooling=>no" >> /etc/asterisk/res_odbc_custom.conf
echo "limit=>1" >> /etc/asterisk/res_odbc_custom.conf
echo "pre-connect=>yes" >> /etc/asterisk/res_odbc_custom.conf
echo "username=>root" >> /etc/asterisk/res_odbc_custom.conf
echo "password=>${rootpw}" >> /etc/asterisk/res_odbc_custom.conf

### Add ODBC 
echo "-------------extconfig.conf----------------"
echo "" >> /etc/asterisk/extconfig.conf
echo "[settings]" >> /etc/asterisk/extconfig.conf
echo "queue_log => odbc,voipiran_stats,queue_stats" >> /etc/asterisk/extconfig.conf

###Restart Asterisk Service
service asterisk reload

###Issabel Menu
echo "-------------Issabel Menu----------------"
issabel-menumerge installation/menu.xml
echo "Issabel Menu is Created Sucsessfully"
sleep 1

echo "-------------Apache Restart----------------"
service httpd restart
echo "Apache has Restarted Sucsessfully"
sleep 1

echo "-----------FINISHED (voipiran.io)-----------"

