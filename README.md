[![Build Status](https://travis-ci.org/doncem/xFrame.svg?branch=master)](https://travis-ci.org/doncem/xFrame)
[![Coverage Status](https://coveralls.io/repos/github/doncem/xFrame/badge.svg?branch=master)](https://coveralls.io/github/doncem/xFrame?branch=master)

PHP xFrame
==========

A lightweight MVC framework

Features
--------

* Incredibly fast (boot in 2.2ms)
* Simple autoloading
* Dependency injection container
* Annotation based request mapping
* Multiple view types: Twig (default), PHPTAL, pure PHP
* Inbuilt caching
* Doctrine2 integration (optional)

Installation
------------

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/doncem/xFrame"
        }
    ],
    "require": {
        "php": "^7.0",
        "linusnorton/xFrame": "~1.0"
    }
}
```

Include Doctrine2 (Optional)

```bash
$ composer require doctrine/orm
```

Setup
-----

Create directory structure

```bash
$ ./vendor/bin/xframe create
```

Getting Started
---------------

* Start hacking `src/Demo/Controller/Index.php` and `view/index.twig`
* [read about request mapping](http://www.donatasmart.lt/xFrame/request-mapping)
* [read about the dependency injection container](http://www.donatasmart.lt/xFrame/dependency-injection-container)
* [read about bootstrapping](http://www.donatasmart.lt/xFrame/bootstrap)
* [read about Doctrine2 integration](http://www.donatasmart.lt/xFrame/doctrine2-integration)
* [read about the exception mailer](http://www.donatasmart.lt/xFrame/exception-mailer)
* [read about using plugins](http://www.donatasmart.lt/xFrame/using-plugins)
* [read about using the ACL](http://www.donatasmart.lt/xFrame/using-the-acl)
