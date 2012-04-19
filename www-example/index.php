<?php
require_once dirname(dirname(__FILE__)).'/libs/model/class.Loader.php';
Loader::register();

$router = new Router();
$router->addRoute('test', 'TestController', array('network', 'username'));
$router->addRoute('index', 'TestController');
echo $router->route();
