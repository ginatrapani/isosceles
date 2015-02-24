<?php
require_once dirname(dirname(__FILE__)).'/libs/model/class.Loader.php';
Loader::register();

$router = new Router();
$router->addRoute('example', 'IsoscelesExampleController', array('network', 'username'));
$router->addRoute('index', 'IsoscelesExampleController');
$router->addRoute('private', 'IsoscelesExampleAuthController');
$router->addRoute('signin', 'IsoscelesSignInController');
$router->addRoute('signout', 'IsoscelesSignOutController');
echo $router->route();
