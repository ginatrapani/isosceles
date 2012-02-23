<?php
putenv("MODE=TESTS");
require_once 'config.tests.inc.php';

//set up required constants
if ( !defined('ISOSCELES_PATH') ) {
    define('ISOSCELES_PATH', str_replace("\\",'/', dirname(dirname(__FILE__))) .'/');
}

if ( !defined('TESTS_RUNNING') ) {
    define('TESTS_RUNNING', true);
}
//Register our lazy class loader
require_once ISOSCELES_PATH.'libs/model/class.Loader.php';

Loader::register(array(
ISOSCELES_PATH . 'tests/',
ISOSCELES_PATH . 'tests/classes/',
ISOSCELES_PATH . 'extlibs/fixtures/'
));
