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
 * IsoscelesBasicUnitTestCase
 *
 * The parent class of all unit tests, without database interaction.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

require_once ISOSCELES_PATH.'libs/class.Loader.php';

class IsoscelesBasicUnitTestCase extends UnitTestCase {

    public function setUp() {
        parent::setUp();
        Loader::register(array(
        ISOSCELES_PATH . 'tests/',
        ISOSCELES_PATH . 'tests/classes/'
        ));

        $config = Config::getInstance();
        if ($config->getValue('timezone')) {
            date_default_timezone_set($config->getValue('timezone'));
        }
        $this->DEBUG = (getenv('TEST_DEBUG')!==false) ? true : false;
        self::isTestEnvironmentReady();
    }

    public function tearDown() {
        Config::destroyInstance();
        if (isset($_SESSION)) {
            $this->unsetArray($_SESSION);
        }
        $this->unsetArray($_POST);
        $this->unsetArray($_GET);
        $this->unsetArray($_REQUEST);
        $this->unsetArray($_SERVER);
        $this->unsetArray($_FILES);
        Loader::unregister();
        parent::tearDown();
    }

    /**
     * Unset all the values for every key in an array
     * @param array $array
     */
    protected function unsetArray(array &$array) {
        $keys = array_keys($array);
        foreach ($keys as $key) {
            unset($array[$key]);
        }
    }

    /**
     * Move webapp/config.inc.php to webapp/config.inc.bak.php for tests with no config file
     */
    protected function removeConfigFile() {
        if (file_exists(ISOSCELES_PATH . 'libs/config.inc.php')) {
            $cmd = 'mv '.ISOSCELES_PATH . 'libs/config.inc.php ' .ISOSCELES_PATH . 'libs/config.inc.bak.php';
            exec($cmd, $output, $return_val);
            if ($return_val != 0) {
                echo "Could not ".$cmd;
            }
        }
    }

    /**
     * Move webapp/config.inc.bak.php to webapp/config.inc.php
     */
    protected function restoreConfigFile() {
        if (file_exists(ISOSCELES_PATH . 'libs/config.inc.bak.php')) {
            $cmd = 'mv '.ISOSCELES_PATH . 'libs/config.inc.bak.php ' .ISOSCELES_PATH . 'libs/config.inc.php';
            exec($cmd, $output, $return_val);
            if ($return_val != 0) {
                echo "Could not ".$cmd;
            }
        }
    }

    public function __destruct() {
        $this->restoreConfigFile();
    }

    public function debug($message) {
        if($this->DEBUG) {
            $bt = debug_backtrace();
            print get_class($this) . ": line " . $bt[0]['line'] . " - " . $message . "\n";
        }
    }

    /**
     * Preemptively halt test run if testing environment requirement isn't met.
     * Prevents unnecessary/inexplicable failures and data loss.
     */
    public static function isTestEnvironmentReady() {
        require ISOSCELES_PATH.'libs/config.inc.php';

        $datadir_path = FileDataManager::getDataPath();
        if (!is_writable($datadir_path)) {
            $message = "In order to test your application, $datadir_path must be writable.";
        }

        global $TEST_DATABASE;

        if ($ISOSCELES_CFG['db_name'] != $TEST_DATABASE) {
            $message = "The database name in libs/config.inc.php does not match \$TEST_DATABASE in ".
            "tests/config.tests.inc.php.
In order to test your Isosceles installation without losing data, these database names must both point to the same ".
"empty test database.";
        }

        if ($ISOSCELES_CFG['cache_pages']) {
            $message = "In order to test your Isosceles installation, \$ISOSCELES_CFG['cache_pages'] must be set to false.";
        }

        if (isset($message)) {
            die("Stopping tests...Test environment isn't ready.
".$message."
Please try again.
");
        }
    }
}
