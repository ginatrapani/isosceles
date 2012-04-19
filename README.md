# Isosceles: Starter kit for PHP5 webapps [![Build Status](https://secure.travis-ci.org/ginatrapani/isosceles.png)](http://travis-ci.org/ginatrapani/isosceles)

Isosceles is an object-oriented, PHP5 web application starter kit based on [ThinkUp](http://thinkupapp.com/)'s
underpinnings. Isosceles provides a simple MVC framework, database agnosticism, caching, dynamic class loading,
single-file configuration, URL routing and (eventually) common actions like user registration, login, and logging.

Isosceles' name was inspired by the architecture of New York City's 
[One World Trade Center](http://en.wikipedia.org/wiki/One_World_Trade_Center).

## Features

Isosceles is the PHP framework that runs [ThinkUp](http://thinkupapp.com/), extracted and abstracted for reuse. It is
nowhere near done. Its current feature set includes:

* [Model View Controller](http://en.wikipedia.org/wiki/Model_view_controller) framework, using
[Smarty](http://smarty.net) and [Bootstrap](http://twitter.github.com/bootstrap/) for the view
* [Data Access Object](http://en.wikipedia.org/wiki/Data_access_object)-based data layer using
[PDO](http://us.php.net/manual/en/book.pdo.php)
* Dynamic configuration through single config file
* Caching to disk (via [Smarty](http://smarty.net))
* URL routing
* A simple database query profiler
* Testing framework (using [SimpleTest](http://www.simpletest.org/)) with Travis CI hooks

Isosceles is in beta and incomplete. In future releases it will get more features which demonstrate its use, like user
registration, login, application settings, and logging.

## License

Isosceles' source code is licensed under the [GNU General Public License](http://www.gnu.org/licenses/gpl.html), except
for the  external libraries listed below.

## External libraries

The following libraries are included in Isosceles:

* [SimpleTest](http://www.simpletest.org/)
* [Smarty](http://smarty.net)
* [Bootstrap](http://twitter.github.com/bootstrap/)
