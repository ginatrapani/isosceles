# Isosceles: Starter kit for PHP5 webapps [![Build Status](https://secure.travis-ci.org/ginatrapani/isosceles.png)](http://travis-ci.org/ginatrapani/isosceles)

Isosceles is an object-oriented, PHP5 web application starter kit based on [ThinkUp](https://thinkup.com/)'s
underpinnings. Isosceles provides a simple MVC framework, database agnosticism, caching, dynamic class loading,
single-file configuration, URL routing and (eventually) common actions like user registration, login, and logging.

Isosceles' name refers to the architecture of New York City's
[One World Trade Center](http://en.wikipedia.org/wiki/One_World_Trade_Center).

## Features

Isosceles is the PHP framework that runs [ThinkUp](https://thinkup.com/), extracted and abstracted for reuse. It is
nowhere near done. Its current feature set includes:

* [Model View Controller](http://en.wikipedia.org/wiki/Model_view_controller) framework, using
[Smarty](http://smarty.net) for the view
* [Data Access Object](http://en.wikipedia.org/wiki/Data_access_object)-based data layer using
[PDO](http://us.php.net/manual/en/book.pdo.php)
* Dynamic configuration through single config file
* Caching to disk (via [Smarty](http://smarty.net)) (included)
* URL routing
* A simple database query profiler
* Test suite using [PHPUnit](https://phpunit.de/) with Travis CI hooks

Isosceles is in beta and incomplete. In future releases it will get more features which demonstrate its use, like user
registration, login, application settings, and logging.

## License

Isosceles' source code is licensed under the [GNU General Public License](http://www.gnu.org/licenses/gpl.html), except
for the external libraries listed below.

## Develop Locally via Vagrant Virtual Machine

### Requirements

* [Vagrant](https://vagrantup.com)
* [VirtualBox](https://www.virtualbox.org/)
* ```vagrant plugin install vagrant-bindfs```

### Install

Clone the repository:

    $ git clone git@github.com:ginatrapani/isosceles.git

Get required submodules:

    $ cd isosceles; git submodule init; git submodule update --recursive

### Run Development Environment

Spin up virtual machine: (first run takes awhile)

    $ vagrant up

All done? Congratulations!

SSH in and run the tests

    [host] $ vagrant ssh
    [guest] $ cd /var/www/; sudo php tests/all_tests.php

### Use

See Isosceles example web app in your browser:

* http://isosceles.dev/

Note: If isosceles.dev doesn't resolve, make sure the following line is in your host computer's /etc/hosts file:

    192.168.56.101 default isosceles.dev www.isosceles.dev

Use the code editor and git client of your choice on your host machine. Edit files in the isosceles directory.

### Tools

Adminer database admin:

* http://192.168.56.101/adminer/
* isosceles / nice2bnice

MailCatcher

* http://192.168.56.101:1080/

SSH in:

    $ vagrant ssh

Destroy virtual machine:

    $ vagrant destroy

Note:  This does not delete setup files or the contents of the default directory.

### Modify

This Vagrant virtual machine was built with [PuPHPet](http://puphpet.com). To modify it for your own purposes, drag and drop puphpet/config.yaml onto (http://puphpet.com) and regenerate.

