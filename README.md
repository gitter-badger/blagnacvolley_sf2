blagnacvolley_sf2
=================

Symfony 2 Website for Blagnac Volley.

# New Installation

## Install node

```
npm install
bower install
grunt less
```

## Install Bower Globally

```
npm install
bower install
grunt less
```

Install Composer:

```
php -r "readfile('https://getcomposer.org/installer');" | php
```

Install vendors:

```
php composer.phar install
```

# Php extensions required : APC
## Install APC

Download from here http://dev.freshsite.pl/php-accelerators/apc.html the nts or not nts lib, depending of your version of php installed (default not nts)
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

Create database:

```
php app/console doctrine:migrations:migrate
```

Create super user:

```
php app/console fos:user:create --super-admin
```

## Update

```
npm install
bower install
grunt less
php composer.phar install
composer self-update
php app/console doctrine:migrations:migrate
php app/console assets:install
```

## Deployment

```
git push test
```