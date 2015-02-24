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
 * IsoscelesUnitTestCase
 *
 * The parent class of database-driven unit tests.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

class IsoscelesUnitTestCase extends IsoscelesBasicUnitTestCase {
    /**
     * @var IsoscelesTestDatabaseHelper
     */
    var $testdb_helper;
    /**
     * @var str
     */
    var $test_database_name;
    /**
     * @var str
     */
    var $table_prefix;
    /**
     * Create a clean copy of the Isosceles database structure
     */
    public function setUp() {
        parent::setUp();

        require ISOSCELES_PATH.'libs/config.inc.php';
        require ISOSCELES_PATH .'tests/config.tests.inc.php';
        $this->test_database_name = $TEST_DATABASE;
        $config = Config::getInstance();

        if (! self::ramDiskTestMode() ) {
            //Override default CFG values
            $ISOSCELES_CFG['db_name'] = $this->test_database_name;
            $config->setValue('db_name', $this->test_database_name);
        } else {
            $this->test_database_name = $ISOSCELES_CFG['db_name'];
        }
        $this->testdb_helper = new IsoscelesTestDatabaseHelper();


        $this->testdb_helper->drop($this->test_database_name);

        $this->table_prefix = $config->getValue('table_prefix');
        PDODAO::$prefix = $this->table_prefix;

        $this->testdb_helper->create(ISOSCELES_PATH."install/sql/build-db_mysql.sql");
    }

    /**
     * Drop the database and kill the connection
     */
    public function tearDown() {
        if (isset(IsoscelesTestDatabaseHelper::$PDO)) {
            $this->testdb_helper->drop($this->test_database_name);
        }
        parent::tearDown();
    }

    /**
     * Returns an xml/xhtml document element by id
     * @param $doc an xml/xhtml document pobject
     * @param $id element id
     * @return Element
     */
    public function getElementById($doc, $id) {
        $xpath = new DOMXPath($doc);
        return $xpath->query("//*[@id='$id']")->item(0);
    }

    /**
     * Check if we in RAM disk test mode
     * @return bool
     */
    public static function ramDiskTestMode() {
        if (getenv("RD_MODE")=="1") {
            return true;
        }
        return false;
    }
}
