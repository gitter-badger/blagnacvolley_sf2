blagnacvolley_sf2
=================

Symfony 2 Website for Blagnac Volley

http://bvb-tle.rhcloud.com/app_dev.php
http://bvb-tle.rhcloud.com/

## Installation

Install Composer:

```
php -r "readfile('https://getcomposer.org/installer');" | php
```

Install vendors:

```
php composer.phar install
```

Si erreurs composer : "The system cannot find the path specified" (Windows)#

Open regedit.
Search for an AutoRun key inside HKEY_LOCAL_MACHINE\Software\Microsoft\Command Processor or HKEY_CURRENT_USER\Software\Microsoft\Command Processor.
Check if it contains any path to non-existent file, if it's the case, just remove them.


Configure local parameters: go to http://hostname/config.php

Create database:

```
php app/console doctrine:migrations:migrate
```

Create super user:

```
php app/console fos:user:create --super-admin
```

Install node, then bower, then install dependencies

```
npm install
bower install
```
