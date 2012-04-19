<?php
require_once dirname(__FILE__).'/init.tests.php';
require_once ISOSCELES_PATH.'extlibs/simpletest/autorun.php';
require_once ISOSCELES_PATH.'libs/config.inc.php';

class TestOfRouter extends IsoscelesBasicUnitTestCase {

    public function setUp(){
        parent::setUp();
    }

    public function tearDown(){
        parent::tearDown();
    }

    public function testConstructor() {
        $router = new Router();
        $this->assertTrue(isset($router), 'constructor test');
    }

    public function testAddRoute() {
        $router = new Router();
        $router->addRoute('test', 'TestController');
        $this->assertEqual(array('test'=>'TestController'), Router::$routes);
        $this->assertEqual(1, sizeof(Router::$routes));

        $router->addRoute('test2', 'Test2Controller');
        $this->assertEqual(array('test'=>'TestController', 'test2'=>'Test2Controller'), Router::$routes);
        $this->assertEqual(2, sizeof(Router::$routes));
    }

    public function testRouteNoParameters() {
        $router = new Router();
        $router->addRoute('test', 'TestController');
        $router->addRoute('index', 'TestController');

        $_SERVER['REQUEST_URI'] = "/";
        $results = $router->route(true);
        $this->assertPattern('/My Isosceles Application/', $results);


        $_SERVER['REQUEST_URI'] = "/test/user/ginatrapani";
        $results = $router->route(true);
        $this->assertPattern('/My Isosceles Application/', $results);

        $_SERVER['REQUEST_URI'] = "/nonexistent/user/ginatrapani";
        $results = $router->route(true);
        $this->assertPattern('/404 route not found: nonexistent/', $results);
    }

    public function testRouteWithParameters() {
        $router = new Router();
        $router->addRoute('user', 'TestController', array('username', 'network'));

        $_SERVER['REQUEST_URI'] = "/user/twitter/username";
        $results = $router->route(true);
        $this->assertPattern('/My Isosceles Application/', $results);
    }
}
