<?php
putenv("MODE=TESTS");
require_once 'config.tests.inc.php';

//set up 3 required constants
if ( !defined('ROOT_PATH') ) {
    define('ROOT_PATH', str_replace("\\",'/', dirname(dirname(__FILE__))) .'/');
}

if ( !defined('WEBAPP_PATH') ) {
    define('WEBAPP_PATH', ROOT_PATH . 'webapp/');
}

if ( !defined('TESTS_RUNNING') ) {
    define('TESTS_RUNNING', true);
}

//Register our lazy class loader
require_once ROOT_PATH.'webapp/_lib/model/class.Loader.php';

Loader::register(array(
ROOT_PATH . 'tests/',
ROOT_PATH . 'tests/classes/',
ROOT_PATH . 'tests/fixtures/'
));
