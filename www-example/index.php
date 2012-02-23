<?php
require_once dirname(dirname(__FILE__)).'/libs/model/class.Loader.php';
Loader::register();
$controller = new TestController();
echo $controller->control();
