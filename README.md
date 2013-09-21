Getting started with SMSVestiBundle
=============

[SMSVesti](http://smsvesti.ru/)

## Prerequisites

This version of the bundle requires Symfony 2.1+ and Doctrine ORM 2.2+

## Installation

Installation is a quick 3 step process:

1. Download KarserSMSVestiBundle using composer
2. Enable the Bundle
3. Configure the KarserSMSVestiBundle

### Step 1: Download KarserSMSVestiBundle using composer

Add KarserSMSVestiBundle in your composer.json:

```js
{
    "require": {
        "karser/smsvesti-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php ./composer.phar update
```

Composer will install the bundle to your project's `vendor/karser` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Karser\SMSVestiBundle\KarserSMSVestiBundle(),
    );
}
```

### Step 3: Configure the KarserSMSVestiBundle

Add the following configuration to your `config.yml` file according to which type
of datastore you are using.

``` yaml
# app/config/config.yml
karser_sms_vesti:
    login: "%sms_vesti_login%"
    password: "%sms_vesti_password%"
```

``` yaml
# app/config/parameters.yml
parameters:
    sms_vesti_login:    ~
    sms_vesti_password: ~
```

### Usage Steps
#### Basic usage
You can check the balance by cli command:
```
$ app/console smsvesti:balance
> Balance is 6.45
```
