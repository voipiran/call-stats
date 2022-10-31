# Voipiran Call Stats Developer Version
voipiran call center stats

## Setup
```
$ cd /var/www
$ mkdir voipiran //ignore ig voipiran dir exists
$ cd voipiran
$ add your ssh key to authorized_ssh in ~/.ssh
$ git clone git@github.com:voipiran/call-stats-dev stats
$ cd stats
$ cp .env.example .env
$ composer install // install the composer if is not installed
$ php artisan migrate // If there is no `settings` table
$ mkdir /var/www/html/voipiran // ignore if /html/voipiran exists
$ mkdir /var/www/html/voipiran/stats
$ cd  /var/www/voipiran/stats/public 
$ mv ./.[!.]* /var/www/html/voipiran/stats
$ cd ..
$ rm -rf public 
$ chmod -R 777 storage
$ php artisan cache:clear
$ composer dump-autoload
$ vim /etc/httpd/conf.d/issabel-htaccess.conf 
   // add folowing command top of the issabel-htaccess.conf  file
   <Directory "/var/www/html/voipiran/stats">
        AllowOverride All
   </Directory>
$ systemctl restart httpd
$ install node 16 and yarn
$ go to `/www/var/www/voipiran/stats`
$ yarn
$ yarn watch
$ done

## Production
- go to `/var/www/voipiran/stats`
- `yarn production`

```
## manage language And Setting
- If there is no `settings` table, use the following command
```
$ php artisan migrate
```

## change content page about me
- path file en is `/resource/lang/en/aboutMe.php`  // for english language
- path file fa is `/resource/lang/fa/aboutMe.php`  // for farsi language
 ```
   after change content please run below command
   $ php artisan cache:clear
   $ php artisan config:clear
 ```
 
## Setup Qpanel service python!
```
$ cd /etc/systemd/system
$ nano qpanel.service
$ past the below content
   [Unit]
   Description=Qpanel
   After=network.target

   [Service]
   Type=simple
   ExecStart=/usr/bin/python app.py
   Restart=always
   # Consider creating a dedicated user for Wiki.js here:
   User=asterisk
   WorkingDirectory=/var/www/html/qpanel

   [Install]
   WantedBy=multi-user.target
```
> Becarefull  
> Check the `python --version` if it's 2.x.x you need version 3, try `python3 --version` and replace the `python3 app.py` insted `python app.py` in the top content.

```
$ systemctl start qpanel.service
$ systemctl enabel qpanel.service
```

It's done, you should see the qpanel on `serverip:5000` and it will reload on server boot.

## for development
```
$ sudo yum install nodejs // if node not installed
$ yarn
$ yarn watch
```