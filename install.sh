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
#cp .env.example .env
sed -i "s/93Voipiran/${rootpw}/g" .env

###Install Database
echo "Install iRouting Mysql DataBase"
echo "------------Create DB-----------------"
mysql -uroot -p$rootpw < installation/voipiran_stats-database.sql
mysql -uroot -p$rootpw -e "GRANT ALL PRIVILEGES ON voipiran_stats.* TO 'root'@'localhost';"


echo "Copy Files"
echo "------------Files-----------------"
###Copy Files
mkdir -p /var/www/voipiran
mkdir -p /var/www/html/voipiran

rm -rf /var/www/html/voipiran/irouting
mkdir -p /var/www/html/voipiran/irouting

rm -rf /var/www/voipiran/irouting
mkdir -p /var/www/voipiran/irouting

yes | cp -avr public/* public/.htaccess /var/www/html/voipiran/irouting > /dev/null
yes | cp -avr * .env /var/www/voipiran/irouting > /dev/null
yes | rm -rf /var/www/voipiran/irouting/public > /dev/null




ln -s /var/www/voipiran/irouting/storage/app /var/www/html/voipiran/irouting/storage


yes | cp -rf -rf installation/vi-irouting.php /var/lib/asterisk/agi-bin

###Add Permisions
#chmod -R 777 /var/www/voipiran
#chmod -R 777 /var/www/html/voipiran
chmod -R 777 /var/www/voipiran/irouting/storage/app
chmod -R 777 /var/www/voipiran/irouting/storage
chmod 777 /var/lib/asterisk/agi-bin/vi-irouting.php

chown -R asterisk:asterisk /var/www/voipiran/irouting
#chown -R asterisk:asterisk /var/www/voipiran
#chown -R asterisk:asterisk /var/www/html/voipiran
chown -R asterisk:root /var/www/voipiran/irouting/storage
chmod -R 777 /var/www/voipiran/irouting/storage
chown -R asterisk:root /var/www/voipiran/irouting/storage/app
chmod -R 777 /var/www/voipiran/irouting/storage/app




echo '<Directory "/var/www/html/voipiran/irouting">' >> /etc/httpd/conf.d/issabel-htaccess.conf
echo 'AllowOverride All' >> /etc/httpd/conf.d/issabel-htaccess.conf
echo '</Directory>' >> /etc/httpd/conf.d/issabel-htaccess.conf


#Create Database
php /var/www/voipiran/irouting/artisan cache:clear
yes | composer dump-autoload

### Add from-pstn Context
echo "-------------Extension Custom----------------"
echo "" >> /etc/asterisk/extensions_custom.conf
echo "[from-pstn]" >> /etc/asterisk/extensions_custom.conf
echo "exten => _.,1,Answer()" >> /etc/asterisk/extensions_custom.conf
echo "exten => _.,n,AGI(vi-irouting.php)" >> /etc/asterisk/extensions_custom.conf
echo "exten => _.,n,Goto(ext-did,s,1)" >> /etc/asterisk/extensions_custom.conf

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

