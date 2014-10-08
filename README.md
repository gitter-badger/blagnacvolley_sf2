blagnacvolley_sf2
=================

Symfony 2 Website for Blagnac Volley

## Installation

Install Composer:

```
php -r "readfile('https://getcomposer.org/installer');" | php
```

Install vendors:

```
php composer.phar install
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
