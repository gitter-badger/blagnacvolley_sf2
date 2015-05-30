blagnacvolley_sf2
=================

Symfony 2 Website for Blagnac Volley.

# New Installation

## Install node
Download from here https://nodejs.org/download/

## Install Bower

```
npm install -g bower
```

## Install Grunt

```
npm install -g grunt-cli
npm install -g grunt
```

## Install Composer:

```
php -r "readfile('https://getcomposer.org/installer');" | php
```

# Php extensions required
## Install APC

Download from here http://dev.freshsite.pl/php-accelerators/apc.html the nts or not nts lib, depending of your version of php installed (default not nts), VC9 X86. Check from phpinfo()
copy into php extensions directory (C:\xampp\php\ext) and rename php_apc.dll
Edit php.ini (C:\xampp\php\php.ini) and add :

```
extension=php_apc.dll

[APC]
apc.enabled = 1
apc.shm_segments = 1
apc.shm_size = 512M
apc.max_file_size = 10M
apc.stat = 1
```

## Install Intl

Download from here : https://pecl.php.net/package/intl
Edit php.ini (C:\xampp\php\php.ini) and add :

```
extension=php_intl.dll
```

## Install XDebug

Download from here : http://xdebug.org/download.php
Edit php.ini (C:\xampp\php\php.ini) and add :

```
[Xdebug]
zend_extension=C:\xampp\php\ext\php_xdebug.dll
xdebug.remote_enable=1
xdebug.remote_host=localhost
xdebug.remote_port=9000
```

## Install PHP_FILEINFO

```
extension=php_fileinfo.dll
```

# Finalize Installation setup
Run all following commands to download required libraries, install assets, generate css files, copy images...

```
npm install
bower install
grunt build
php composer.phar self-update
php composer.phar update
php app/console doctrine:migrations:migrate
php app/console assets:install
```

You can load Fixtures, when no ref Database :

```
php app/console doctrine:fixtures:load
```

#Troubleshouting

Si erreurs composer : "The system cannot find the path specified" (Windows)#

Open regedit.
Search for an AutoRun key inside HKEY_LOCAL_MACHINE\Software\Microsoft\Command Processor or HKEY_CURRENT_USER\Software\Microsoft\Command Processor.
Check if it contains any path to non-existent file, if it's the case, just remove them.

# Serveur de Test :

```
test.blagnacvolley.fr --> http://test-blagnacvolley.rhcloud.com/
```

Serveur de Prod :

```
blagnacvolley.fr --> http://prod-blagnacvolley.rhcloud.com/
```

Configure local parameters: go to http://hostname/config.php

# Deployment

```
git push test
```