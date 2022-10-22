# Voipiran Call Stats Developer Version
voipiran call center stats

## Setup
```
$ cd /var/www
$ mkdir voipiran //ignore ig voipiran dir exists
$ git clone git@github.com:voipiran/call-stats-dev call-stats
$ cd call-stats
$ cp .env.example .env
$ composer install // install the composer if is not installed
$ mkdir /var/www/html/voipiran // ignore if /html/voipiran exists
$ mkdir /var/www/html/voipiran/call-stats
$ cd public 
$ mv ./.[!.]* /var/www/html/voipiran/call-stats
$ cd ..
$ rm -rf public 
$ chmod -R 777 storage
$ php artisan cache:clear
$ composer dump-autoload
$ vim /etc/httpd/conf.d/issabel-htaccess.conf 
   // add folowing command top of the issabel-htaccess.conf  file
   <Directory "/var/www/html/voipiran/call-stats">
        AllowOverride All
   </Directory>
$ systemctl restart httpd
$ done
```