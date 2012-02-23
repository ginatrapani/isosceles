<?php
/**
 * LICENSE:
 *
 * This file is part of Isosceles (http://isosceleskit.org/).
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

class TestOfFileDataManager extends IsoscelesBasicUnitTestCase {

    public function testGetDataPathNoConfigFile() {
        Config::destroyInstance();
        $this->removeConfigFile();

        //test just path
        $path = FileDataManager::getDataPath();
        $this->assertEqual($path, ISOSCELES_PATH.'data/');

        //test path with file
        $path = FileDataManager::getDataPath('myfile.txt');
        $this->assertEqual($path, ISOSCELES_PATH.'data/myfile.txt');
        $this->restoreConfigFile();
    }

    public function testGetDataPathConfigExistsWithoutDataDirValue() {
        Config::destroyInstance();
        $this->removeConfigFile();
        $cfg_values = array("table_prefix"=>"isoscelesyo", "db_host"=>"myserver.com");
        $config = Config::getInstance($cfg_values);

        //test just path
        $path = FileDataManager::getDataPath();
        $this->assertEqual($path, ISOSCELES_PATH.'data/');

        //test path with file
        $path = FileDataManager::getDataPath('myfile.txt');
        $this->assertEqual($path, ISOSCELES_PATH.'data/myfile.txt');
        $this->restoreConfigFile();
    }

    public function testGetDataPathConfigExistsWithDataDirValue() {
        require ISOSCELES_PATH.'libs/config.inc.php';

        //if test fails here, the config file doesn't have datadir_path set
        $this->assertNotNull($ISOSCELES_CFG['datadir_path']);

        //test just path
        $path = FileDataManager::getDataPath();
        $this->assertEqual($path, $ISOSCELES_CFG['datadir_path']);

        //test path with file
        $path = FileDataManager::getDataPath('myfile.txt');
        $this->assertEqual($path, $ISOSCELES_CFG['datadir_path'].'myfile.txt');
    }

    public function testGetBackupPath() {
        require ISOSCELES_PATH.'libs/config.inc.php';

        //if test fails here, the config file doesn't have datadir_path set
        $this->assertNotNull($ISOSCELES_CFG['datadir_path']);

        //test just path
        $path = FileDataManager::getBackupPath();
        $this->assertEqual($path, $ISOSCELES_CFG['datadir_path'].'backup/');

        //test just path
        $path = FileDataManager::getBackupPath('README.txt');
        $this->assertEqual($path, $ISOSCELES_CFG['datadir_path'].'backup/README.txt');
    }
}