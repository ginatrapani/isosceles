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

class IsoscelesExampleControllerTest extends IsoscelesBasicUnitTestCase {

    public function setUp(){
        parent::setUp();
        $config = Config::getInstance();
        $config->setValue('debug', true);
    }

    public function tearDown(){
        parent::tearDown();
    }

    /**
     * Test constructor
     */
    public function testConstructor() {
        $controller = new IsoscelesExampleController(true);
        $this->assertTrue(isset($controller), 'constructor test');
    }

    /**
     * Test controller
     * @TODO Possibly load the resulting markup as a DOM object and test various children in it;
     * this would enforce valid markup
     */
    public function testControl() {
        $config = Config::getInstance();
        $controller = new IsoscelesExampleController(true);
        $results = $controller->go();

        $this->assertEquals('text/html; charset=UTF-8', $controller->getContentType());
        //test if view variables were set correctly
        $v_mgr = $controller->getViewManager();
        $this->assertEquals($v_mgr->getTemplateDataItem('test'), 'Testing, testing, 123');
        $this->assertEquals($v_mgr->getTemplateDataItem('app_title'), "My Web App");
        $this->debug($results);
        $this->assertRegExp('/My Web App/', $results);
    }

    /**
     * Test cache key, no params
     * @TODO Possibly load the resulting markup as a DOM object and test various children in it;
     * this would enforce valid markup
     */
    public function testCacheKeyNoRequestParams() {
        $config = Config::getInstance();
        $config->setValue('cache_pages', true);
        $controller = new IsoscelesExampleController(true);
        $results = $controller->go();

        $this->assertEquals($controller->getCacheKeyString(), '.htisosceles-example-controller.tpl-');
    }

    /**
     * Test json output
     */
    public function testJsonOutput() {
        $config = Config::getInstance();
        $controller = new IsoscelesExampleController(true);
        $_GET['json'] = true;
        $results = $controller->go();
        unset($_GET['json']);
        $this->debug($results);
        $obj = json_decode($results);
        //$this->assertInstanceOf('stdClass', $obj);
        $this->assertEquals($obj->aname, 'a value');
        //$this->assertInstanceOf('Array', $obj->alist);
        $this->assertEquals($obj->alink, 'http://isosceleskit.org');
        $this->assertEquals( $controller->getContentType(),'application/json; charset=UTF-8');
    }

    /**
     * Test adding script to header
     */
    public function testAddJsScript() {
        $config = Config::getInstance();
        $controller = new IsoscelesExampleController(true);
        $controller->addHeaderJavaScript('plugins/hellothinkup/assets/js/test.js');
        $results = $controller->go();

        //test if view javascript variable is set correctly
        $v_mgr = $controller->getViewManager();
        $scripts = $v_mgr->getTemplateDataItem('header_scripts');
        $this->assertEquals($scripts[0], 'plugins/hellothinkup/assets/js/test.js');
    }

    /**
     * Test setting content type header
     */
    public function testAddHeader() {
        $config = Config::getInstance();
        $controller = new IsoscelesExampleController(true);
        $_GET['text'] = true;

        $results = $controller->go();
        $this->assertEquals( $controller->getContentType(),'text/plain; charset=UTF-8');
    }

    /**
     * Test setting content type header
     */
    public function testAddImageContentTypeHeader() {
        $config = Config::getInstance();
        $controller = new IsoscelesExampleController(true);
        $_GET['png'] = true;

        $results = $controller->go();
        $this->assertEquals( $controller->getContentType(),'image/png');
    }

    /**
     * Test add CSS 2 header
     */
    public function testAddCSS2Header() {
        $config = Config::getInstance();
        $controller = new IsoscelesExampleController(true);
        $_GET['css'] = true;
        $results = $controller->go();
        $this->debug($results);
        $this->assertEquals(count($controller->getHeaderCSS()), 1);
        $css = $controller->getHeaderCSS();
        $this->assertEquals($css[0], 'assets/css/bla.css');
        $this->assertRegExp('/assets\/css\/bla\.css"/', $results);
    }

    /**
     * Test exception handling
     */
    public function testExceptionHandlingNoRouter() {
        $_GET['throwexception'] = 'yesindeedy';
        $controller = new IsoscelesExampleController(true);
        $results = $controller->go();

        $v_mgr = $controller->getViewManager();
        $config = Config::getInstance();
        $this->assertEquals('Testing exception handling!', $v_mgr->getTemplateDataItem('error_msg'));
        $this->assertRegExp('/<html/', $results);

        $_GET['json'] = true;
        $results = $controller->go();
        $this->debug($results);
        $this->assertFalse(strpos($results, '<html'));
        $this->assertRegExp('/{/', $results);
        $this->assertRegExp('/Testing exception handling/', $results);
        $this->assertEquals('Exception', $v_mgr->getTemplateDataItem('error_type'));
        unset($_GET['json']);

        $_GET['text'] = true;
        $results = $controller->go();
        $this->assertFalse(strpos($results, '<html'));
        $this->assertFalse(strpos($results, '{'));
        $this->assertRegExp('/Testing exception handling/', $results);
        $this->assertEquals('Exception', $v_mgr->getTemplateDataItem('error_type'));
        unset($_GET['text']);
    }

    /**
     * Test exception handling with Router 500 controller
     */
    public function testExceptionHandlingWithRouter() {
        $_GET['throwexception'] = 'yesindeedy';
        $router = new Router();
        $controller = new IsoscelesExampleController(true);
        $results = $controller->go();

        $this->assertRegExp('/Testing exception handling/', $results);
        $this->assertRegExp('/<html/', $results);

        // @TODO Support JSON and txt responses in the Router's 500 controller
        // $_GET['json'] = true;
        // $results = $controller->go();
        // $this->debug($results);
        // $this->assertFalse(strpos($results, '<html'));
        // $this->assertRegExp('/{/', $results);
        // $this->assertRegExp('/Testing exception handling/', $results);
        // $this->assertEquals('Exception', $v_mgr->getTemplateDataItem('error_type'));
        // unset($_GET['json']);

        // $_GET['text'] = true;
        // $results = $controller->go();
        // $this->assertFalse(strpos($results, '<html'));
        // $this->assertFalse(strpos($results, '{'));
        // $this->assertRegExp('/Testing exception handling/', $results);
        // $this->assertEquals('Exception', $v_mgr->getTemplateDataItem('error_type'));
        // unset($_GET['text']);
        // $router = null;
    }
}
