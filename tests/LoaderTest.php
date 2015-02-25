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

class LoaderTest extends IsoscelesBasicUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testLoaderRegisterDefault() {
        $loader = Loader::register();

        // check if Loader is registered to spl autoload
        $this->assertTrue($loader, 'Loader is registered to spl autoload');

        // check default lookup path without additionalPath
        $this->assertEquals( Loader::getLookupPath(), array(
        ISOSCELES_PATH . 'libs/',
        ISOSCELES_PATH . 'libs/model/',
        ISOSCELES_PATH . 'libs/dao/',
        ISOSCELES_PATH . 'libs/controller/',
        ISOSCELES_PATH . 'libs/exceptions/'
        ));

        // check special classes
        $this->assertEquals( Loader::getSpecialClasses(),
        array('Smarty'=>ISOSCELES_PATH . 'libs/extlibs/Smarty-3.1.21/libs/Smarty.class.php'));
    }

    public function testLoaderRegisterWithStringAdditionalPath() {
        // Loader with string of path as additional path
        $loader = Loader::register(array(ISOSCELES_PATH . 'tests/classes'));

        // check if Loader is registered to spl autoload
        $this->assertTrue($loader, 'Loader is registered to spl autoload');

        // check lookup path with single additionalPath
        $this->assertEquals( Loader::getLookupPath(), array(
        ISOSCELES_PATH . 'libs/',
        ISOSCELES_PATH . 'libs/model/',
        ISOSCELES_PATH . 'libs/dao/',
        ISOSCELES_PATH . 'libs/controller/',
        ISOSCELES_PATH . 'libs/exceptions/',
        ISOSCELES_PATH . 'tests/classes'
        ));
    }

    public function testLoaderRegisterWithArrayAdditionalPaths() {
        // Loader with array of path as additional path
        $loader = Loader::register(array(
        ISOSCELES_PATH . 'tests',
        ISOSCELES_PATH . 'tests/classes'
        ));

        // check if Loader is registered to spl autoload
        $this->assertTrue($loader, 'Loader is registered to spl autoload');

        // check lookup path with array additionalPath
        $this->assertEquals( Loader::getLookupPath(), array(
        ISOSCELES_PATH . 'libs/',
        ISOSCELES_PATH . 'libs/model/',
        ISOSCELES_PATH . 'libs/dao/',
        ISOSCELES_PATH . 'libs/controller/',
        ISOSCELES_PATH . 'libs/exceptions/',
        ISOSCELES_PATH . 'tests',
        ISOSCELES_PATH . 'tests/classes'
        ));
    }

    public function testLoaderUnregister() {
        Loader::register();
        $unreg = Loader::unregister();

        // check if Loader is succesfully unregistered
        $this->assertTrue($unreg, 'Unregister Loader');

        // make sure lookup path and special classes are null
        $this->assertNull(Loader::getLookupPath());
        $this->assertNull(Loader::getSpecialClasses());
    }

    public function testLoaderInstantiateClasses() {
        Loader::register();

        $this->assertInstanceOf('Profiler', new Profiler());
        $this->assertInstanceOf('Utils', new Utils());

        $this->assertInstanceOf('Config', Config::getInstance());
    }

    public function testAdditionalPathAfterInitialRegister() {
        Loader::register();
        $this->assertEquals( Loader::getLookupPath(), array(
        ISOSCELES_PATH . 'libs/',
        ISOSCELES_PATH . 'libs/model/',
        ISOSCELES_PATH . 'libs/dao/',
        ISOSCELES_PATH . 'libs/controller/',
        ISOSCELES_PATH . 'libs/exceptions/'
        ));

        Loader::addPath(ISOSCELES_PATH . 'tests/classes');
        $this->assertEquals( Loader::getLookupPath(), array(
        ISOSCELES_PATH . 'libs/',
        ISOSCELES_PATH . 'libs/model/',
        ISOSCELES_PATH . 'libs/dao/',
        ISOSCELES_PATH . 'libs/controller/',
        ISOSCELES_PATH . 'libs/exceptions/',
        ISOSCELES_PATH . 'tests/classes'
        ));
    }

    public function testDefinePathConstants() {
        Loader::definePathConstants();

        $this->assertTrue( defined('ISOSCELES_PATH') );
        $this->assertTrue( is_readable(ISOSCELES_PATH) );
        $this->debug(ISOSCELES_PATH);
    }
}
