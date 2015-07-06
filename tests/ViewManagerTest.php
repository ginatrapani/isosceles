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

class ViewManagerTest extends PHPUnit_Framework_TestCase {

    /**
     * Test constructor
     */
    public function testNewViewManager() {
        $v_mgr = new ViewManager();
        $this->assertTrue(isset($v_mgr));
    }

    /**
     * Test default values
     */
    public function testViewManagerDefaultValues() {
        $cfg = Config::getInstance();
        $cfg->setValue('source_root_path', '/path/to/isosceles/');
        $cfg->setValue('cache_pages', true);
        $cfg->setValue('cache_lifetime', 600);
        $v_mgr = new ViewManager();

        $this->assertEquals(sizeof($v_mgr->template_dir), 2);
        $this->assertEquals($v_mgr->template_dir[1], '/path/to/isosceles/tests/view/');
        $this->assertEquals(sizeof($v_mgr->plugins_dir), 3);
        $this->assertEquals($v_mgr->plugins_dir[0], ISOSCELES_PATH.'libs/extlibs/Smarty-3.1.21/libs/plugins/');
        $this->assertEquals($v_mgr->plugins_dir[1], ISOSCELES_PATH.'libs/view/plugins/');
        $this->assertEquals($v_mgr->cache_dir, FileDataManager::getDataPath('compiled_view/cache/'));
        $this->assertEquals($v_mgr->cache_lifetime, $cfg->getValue('cache_lifetime'));
        $this->assertEquals($v_mgr->caching, 1);
    }

    /**
     * Test assigned variables get saved when debug is true
     */
    public function testViewManagerAssignedValuesDebugOn() {
        $cfg = Config::getInstance();
        $cfg->setValue('debug', true);
        $cfg->setValue('cache_lifetime', 1200);
        $cfg->setValue('app_title_prefix', 'Testy ');
        $cfg->setValue('site_root_path', '/my/isosceles/folder/');
        $v_mgr = new ViewManager();

        $v_mgr->assign('test_var_1', "Testing, testing, 123");
        $this->assertEquals($v_mgr->getTemplateDataItem('test_var_1'), "Testing, testing, 123");

        $this->assertEquals($v_mgr->getTemplateDataItem('app_title'), 'My Web App');
        $this->assertEquals($v_mgr->getTemplateDataItem('logo_link'), '');
        $this->assertEquals($v_mgr->getTemplateDataItem('site_root_path'), '/my/isosceles/folder/');
        $this->assertEquals($v_mgr->cache_lifetime, 1200);
    }

    /**
     * Test assigned variables don't get saved when debug is false
     */
    public function testViewManagerAssignedValuesDebugOff() {
        $cfg = Config::getInstance();
        $cfg->setValue('debug', false);
        $v_mgr = new ViewManager();

        $v_mgr->assign('test_var_1', "Testing, testing, 123");
        $this->assertEquals($v_mgr->getTemplateDataItem('test_var_1'), null);
        $test_var_1 = $v_mgr->getTemplateDataItem('test_var_1');
        $this->assertTrue(!isset($test_var_1));
    }

    /**
     * Test override config with passed-in array
     */
    public function testViewManagerPassedInArray() {
        $cfg_array = array('debug'=>true,
        'site_root_path'=>'/my/isosceles/folder/test',
        'source_root_path'=>'/Users/gina/Sites/isosceles',
        'app_title'=>'Isosceles',
        'cache_pages'=>true,
        'cache_lifetime'=>1000);
        $v_mgr = new ViewManager($cfg_array);

        $this->assertEquals($v_mgr->getTemplateDataItem('app_title'), 'Isosceles');
        $this->assertEquals($v_mgr->getTemplateDataItem('logo_link'), '');
        $this->assertEquals($v_mgr->getTemplateDataItem('site_root_path'), '/my/isosceles/folder/test');
        $this->assertEquals($v_mgr->cache_lifetime, 1000);
    }

    public function testAddHelp() {
        $cfg = Config::getInstance();
        $cfg->setValue('debug', true);
        $v_mgr = new ViewManager();

        $v_mgr->addHelp('api', 'userguide/api/posts/index');
        $v_mgr->addHelp('user_guide', 'userguide/index');

        $help_array = array('api'=>'userguide/api/posts/index', 'user_guide'=>'userguide/index');
        $this->assertEquals($v_mgr->getTemplateDataItem('help'), $help_array);
        $debug_arr = $v_mgr->getTemplateDataItem('help');
        // $this->debug(Utils::varDumpToString($debug_arr));
        // $this->debug($debug_arr['api']);
    }

    public function testAddErrorMessage() {
        $cfg = Config::getInstance();
        $cfg->setValue('debug', true);
        $v_mgr = new ViewManager();

        $v_mgr->addErrorMessage('Page level error');
        $v_mgr->addErrorMessage('Field level error', 'fieldname');

        $this->assertEquals($v_mgr->getTemplateDataItem('error_msg'), 'Page level error');
        $debug_arr = $v_mgr->getTemplateDataItem('error_msgs');
        $this->assertEquals($debug_arr['fieldname'], 'Field level error');
        //$this->debug(Utils::varDumpToString($debug_arr));
    }

    public function testAddInfoMessage() {
        $cfg = Config::getInstance();
        $cfg->setValue('debug', true);
        $v_mgr = new ViewManager();

        $v_mgr->addInfoMessage('Field level info', 'fieldname');
        $v_mgr->addInfoMessage('Page level info');

        $this->assertEquals($v_mgr->getTemplateDataItem('info_msg'), 'Page level info');
        $debug_arr = $v_mgr->getTemplateDataItem('info_msgs');
        $this->assertEquals($debug_arr['fieldname'], 'Field level info');
        //$this->debug(Utils::varDumpToString($debug_arr));
    }

    public function testAddSuccessMessage() {
        $cfg = Config::getInstance();
        $cfg->setValue('debug', true);
        $v_mgr = new ViewManager();

        $v_mgr->addSuccessMessage('Field level info 1', 'fieldname1');
        $v_mgr->addSuccessMessage('Page level info');
        $v_mgr->addSuccessMessage('Field level info 2', 'fieldname2');

        $this->assertEquals($v_mgr->getTemplateDataItem('success_msg'), 'Page level info');
        $debug_arr = $v_mgr->getTemplateDataItem('success_msgs');
        $this->assertEquals($debug_arr['fieldname1'], 'Field level info 1');
        $this->assertEquals($debug_arr['fieldname2'], 'Field level info 2');
        //$this->debug(Utils::varDumpToString($debug_arr));
    }
}
