blagnacvolley_sf2
=================

Symfony 2 Website for Blagnac Volley

Serveur de Test :

```
test.blagnacvolley.fr --> http://test-blagnacvolley.rhcloud.com/
```

Serveur de Prod :

```
blagnacvolley.fr --> http://prod-blagnacvolley.rhcloud.com/
```

## Installation

Install node, then install dependencies and compile less files

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