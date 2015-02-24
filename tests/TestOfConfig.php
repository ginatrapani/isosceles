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
require_once ISOSCELES_PATH.'extlibs/simpletest/autorun.php';
require_once ISOSCELES_PATH.'libs/config.inc.php';

class TestOfConfig extends IsoscelesBasicUnitTestCase {

    public function setUp() {
        parent::setUp();
        $this->config = Config::getInstance();
        //        $option_dao = new OptionMySQLDAO();
        //        $this->pdo = $option_dao->connect();
    }

    public function tearDown() {
        parent::tearDown();
    }

    /**
     * Test config singleton instantiation
     */
    public function testConfigSingleton() {
        $config = Config::getInstance();
        $this->assertIsA($config, 'Config');
    }

    public function testGetValuesArray() {
        require ISOSCELES_PATH.'libs/config.inc.php';
        //        require ISOSCELES_PATH.'install/version.php';
        $config = Config::getInstance();
        //tests assume profiler and caching is off
        $ISOSCELES_CFG['cache_pages']=false;
        //        $ISOSCELES_CFG['ISOSCELES_VERSION'] = $ISOSCELES_VERSION;
        //        $ISOSCELES_CFG['ISOSCELES_VERSION_REQUIRED'] =
        //        array('php' => $ISOSCELES_VERSION_REQUIRED['php'], 'mysql' => $ISOSCELES_VERSION_REQUIRED['mysql']);
        $ISOSCELES_CFG['enable_profiler']=false;
        $values_array = $config->getValuesArray();
        $this->assertIdentical($ISOSCELES_CFG, $values_array);
    }

    public function testPassInArray() {
        Config::destroyInstance();
        $cfg_values = array("table_prefix"=>"isoscelesyo", "db_host"=>"myserver.com");
        $config = Config::getInstance($cfg_values);
        $this->assertEqual($config->getValue("table_prefix"), "isoscelesyo");
        $this->assertEqual($config->getValue("db_host"), "myserver.com");
    }

    public function testNoConfigFileArray() {
        Config::destroyInstance();
        $this->removeConfigFile();
        $cfg_values = array("table_prefix"=>"isoscelesyo", "db_host"=>"myserver.com");
        $config = Config::getInstance($cfg_values);
        $this->assertEqual($config->getValue("table_prefix"), "isoscelesyo");
        $this->assertEqual($config->getValue("db_host"), "myserver.com");
        $this->restoreConfigFile();
    }

    public function testNoConfigFileNoArray() {
        Config::destroyInstance();
        $this->removeConfigFile();
        try {
            $config = Config::getInstance();
            $this->assertNull($config->getValue('table_prefix'));
        } catch(Exception $e) {
            $this->assertPattern("/Isosceles\' configuration file does not exist!/", $e->getMessage());
        }
        $this->restoreConfigFile();
    }

    //    public function testDBConfigValues() {
    //        Config::destroyInstance();
    //        $config = Config::getInstance();
    //        $this->assertEqual($config->getValue('is_registration_open'), '', "uses default app config value");
    //        $this->assertFalse($config->getValue('recaptcha_enable'), "uses default app config value");
    //        $this->assertEqual($config->getValue('recaptcha_private_key'), '', "uses default app config value");
    //        $this->assertEqual($config->getValue('recaptcha_public_key'), '', "uses default app config value");
    //
    //        if (isset($_SESSION)) {
    //            $this->unsetArray($_SESSION);
    //        }
    //
    //        $bvalue = array('namespace' => OptionDAO::APP_OPTIONS, 'option_name' => 'recaptcha_enable',
    //        'option_value' => 'false');
    //        $bdata = FixtureBuilder::build('options', $bvalue);
    //        $this->assertFalse($config->getValue('is_registration_open'), "uses default app config value");
    //        $this->assertFalse($config->getValue('recaptcha_enable'), "uses db config value");
    //        $this->assertEqual($config->getValue('recaptcha_private_key'), '', "uses default app config value");
    //        $this->assertEqual($config->getValue('recaptcha_public_key'), '', "uses default app config value");
    //
    //        if (isset($_SESSION)) {
    //            $this->unsetArray($_SESSION);
    //        }
    //        FixtureBuilder::truncateTable('options');
    //        $bvalue['option_value'] = 'true';
    //        $bvalue2 = array('namespace' => OptionDAO::APP_OPTIONS, 'option_name' => 'recaptcha_private_key',
    //        'option_value' => 'abc123');
    //        $bvalue3 = array('namespace' => OptionDAO::APP_OPTIONS, 'option_name' => 'recaptcha_public_key',
    //        'option_value' => 'abc123public');
    //        $bvalue4 = array('namespace' => OptionDAO::APP_OPTIONS, 'option_name' => 'is_registration_open',
    //        'option_value' => 'true');
    //        $bdata2 = FixtureBuilder::build('options', $bvalue);
    //        $bdata3 = FixtureBuilder::build('options', $bvalue2);
    //        $bdata4 = FixtureBuilder::build('options', $bvalue3);
    //        $bdata5 = FixtureBuilder::build('options', $bvalue4);
    //        $this->assertTrue($config->getValue('recaptcha_enable'), "uses db config value");
    //        $this->assertEqual($config->getValue('recaptcha_private_key'), 'abc123', "uses db config value");
    //        $this->assertEqual($config->getValue('is_registration_open'), true, "uses db config value");
    //    }

    public function testGetGMTOffset() {
        Config::destroyInstance();
        $this->removeConfigFile();
        $config = Config::getInstance(array('timezone' => 'America/Los_Angeles'));
        $this->assertEqual($config->getGMTOffset('January 1, 2010'), -8);
        $this->assertEqual($config->getGMTOffset('August 1, 2010'), -7);

        Config::destroyInstance();
        $this->removeConfigFile();
        $config = Config::getInstance(array('timezone' => 'America/New_York'));
        $this->assertEqual($config->getGMTOffset('January 1, 2010'), -5);
        $this->assertEqual($config->getGMTOffset('August 1, 2010'), -4);

        $this->restoreConfigFile();
    }
}
