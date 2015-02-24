<?php
/**
 * LICENSE:
 *
 * This file is part of Isosceles (http://ginatrapani.github.io/isosceles/).
 *
 * Isosceles is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any
 * later version.
 *
 * Isosceles is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with Isosceles.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

require_once dirname(__FILE__).'/init.tests.php';
require_once ISOSCELES_PATH.'libs/config.inc.php';

class RouterTest extends IsoscelesBasicUnitTestCase {

    public function setUp(){
        parent::setUp();
        Router::$routes = array();
    }

    public function tearDown(){
        parent::tearDown();
    }

    public function testConstructor() {
        $router = new Router();
        $this->assertInstanceOf('Router', $router);
    }

    public function testAddRoute() {
        $router = new Router();
        $router->addRoute('test', 'IsoscelesExampleController');
        $this->assertEquals(array('test'=>'IsoscelesExampleController'), Router::$routes);
        $this->assertEquals(1, sizeof(Router::$routes));

        $router->addRoute('test2', 'Test2Controller');
        $this->assertEquals(array('test'=>'IsoscelesExampleController', 'test2'=>'Test2Controller'), Router::$routes);
        $this->assertEquals(2, sizeof(Router::$routes));
    }

    public function testRouteNoParameters() {
        $router = new Router();
        $router->addRoute('test', 'IsoscelesExampleController');
        $router->addRoute('index', 'IsoscelesExampleController');

        $_SERVER['REQUEST_URI'] = "/";
        $results = $router->route(true);
        $this->assertRegExp('/My Web App/', $results);

        $_SERVER['REQUEST_URI'] = "/test/user/ginatrapani";
        $results = $router->route(true);
        $this->assertRegExp('/My Web App/', $results);

        $_SERVER['REQUEST_URI'] = "/nonexistent/user/ginatrapani";
        $results = $router->route(true);
        $this->assertRegExp('/404 route not found: nonexistent/', $results);
    }

    public function testRouteWithParameters() {
        $router = new Router();
        $router->addRoute('user', 'IsoscelesExampleController', array('username', 'network'));

        $_SERVER['REQUEST_URI'] = "/user/twitter/username";
        $results = $router->route(true);
        $this->assertRegExp('/My Web App/', $results);
    }
}
