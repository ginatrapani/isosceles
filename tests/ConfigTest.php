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

class ConfigTest extends IsoscelesBasicUnitTestCase {

    public function setUp() {
        parent::setUp();
        $this->config = Config::getInstance();
    }

    public function tearDown() {
        parent::tearDown();
    }

    /**
     * Test config singleton instantiation
     */
    public function testConfigSingleton() {
        $config = Config::getInstance();
        $this->assertInstanceOf('Config', $config);
    }

    public function testGetValuesArray() {
        require ISOSCELES_PATH.'libs/config.inc.php';
        $config = Config::getInstance();
        //tests assume profiler and caching is off
        $ISOSCELES_CFG['cache_pages']=false;
        $ISOSCELES_CFG['enable_profiler']=false;
        $values_array = $config->getValuesArray();
        $this->assertEquals($ISOSCELES_CFG, $values_array);
    }

    public function testPassInArray() {
        Config::destroyInstance();
        $cfg_values = array("table_prefix"=>"isoscelesyo", "db_host"=>"myserver.com");
        $config = Config::getInstance($cfg_values);
        $this->assertEquals($config->getValue("table_prefix"), "isoscelesyo");
        $this->assertEquals($config->getValue("db_host"), "myserver.com");
    }

    public function testNoConfigFileArray() {
        Config::destroyInstance();
        $this->removeConfigFile();
        $cfg_values = array("table_prefix"=>"isoscelesyo", "db_host"=>"myserver.com");
        $config = Config::getInstance($cfg_values);
        $this->assertEquals($config->getValue("table_prefix"), "isoscelesyo");
        $this->assertEquals($config->getValue("db_host"), "myserver.com");
        $this->restoreConfigFile();
    }

    public function testNoConfigFileNoArray() {
        Config::destroyInstance();
        $this->removeConfigFile();
        try {
            $config = Config::getInstance();
            $this->assertNull($config->getValue('table_prefix'));
        } catch (Exception $e) {
            $this->assertRegExp("/Isosceles\' configuration file does not exist!/", $e->getMessage());
        }
        $this->restoreConfigFile();
    }

    public function testGetGMTOffset() {
        Config::destroyInstance();
        $this->removeConfigFile();
        $config = Config::getInstance(array('timezone' => 'America/Los_Angeles'));
        $this->assertEquals($config->getGMTOffset('January 1, 2010'), -8);
        $this->assertEquals($config->getGMTOffset('August 1, 2010'), -7);

        Config::destroyInstance();
        $this->removeConfigFile();
        $config = Config::getInstance(array('timezone' => 'America/New_York'));
        $this->assertEquals($config->getGMTOffset('January 1, 2010'), -5);
        $this->assertEquals($config->getGMTOffset('August 1, 2010'), -4);

        $this->restoreConfigFile();
    }
}
