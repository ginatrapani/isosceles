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

class TestOfDAOFactory extends IsoscelesUnitTestCase {

    public function setUp() {
        parent::setUp();
        $this->builders = self::buildData();
    }

    protected function buildData() {
        $builders = array();

        // test table for our test dao
        $test_table_sql = 'CREATE TABLE iso_test_table(' .
            'id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,' .
            'test_name varchar(20),' .
            'test_id int(11),' .
            'unique key test_id_idx (test_id)' .
            ')';
        if (PDODAO::$prefix != 'iso_') {
            $test_table_sql = str_replace('iso_', PDODAO::$prefix, $test_table_sql);
        }
        $this->testdb_helper->runSQL($test_table_sql);

        //some test data as well
        for($i = 1; $i <= 20; $i++) {
            $builders[] = FixtureBuilder::build('test_table', array('test_name'=>'name'.$i, 'test_id'=>$i));
        }
        return $builders;
    }

    public function tearDown() {
        $this->builders = null;
        parent::tearDown();
        //make sure our db_type is set to the default...
        Config::getInstance()->setValue('db_type', 'mysql');
    }

    /*
     * test fetching the proper db_type
     */
    public function testDAODBType() {
        Config::getInstance()->setValue('db_type', null);
        $type = DAOFactory::getDBType();
        $this->assertEqual($type, 'mysql', 'should default to mysql');

        Config::getInstance()->setValue('db_type', 'some_sql_server');
        $type = DAOFactory::getDBType();
        $this->assertEqual($type, 'some_sql_server', 'is set to some_sql_server');
    }

    /*
     * test init DAOs, bad params and all...
     */
    public function testGetTestDAO() {
        // no map for this DAO
        try {
            DAOFactory::getDAO('NoSuchDAO');
            $this->fail('should throw an exception');
        } catch(Exception $e) {
            $this->assertPattern('/No DAO mapping defined for: NoSuchDAO/', $e->getMessage(), 'no dao mapping');
        }

        // invalid db type for this dao
        Config::getInstance()->setValue('db_type', 'nodb');
        try {
            DAOFactory::getDAO('TestDAO');
            $this->fail('should throw an exception');
        } catch(Exception $e) {
            $this->assertPattern("/No db mapping defined for 'TestDAO'/", $e->getMessage(), 'no dao db_type mapping');
        }

        // valid mysql test dao
        Config::getInstance()->setValue('db_type', 'mysql');
        $test_dao = DAOFactory::getDAO('TestDAO');
        $this->assertIsA($test_dao, 'TestMySQLDAO', 'we are a mysql dao');
        $data_obj = $test_dao->selectRecord(1);
        $this->assertNotNull($data_obj);
        $this->assertEqual($data_obj->test_name, 'name1');
        $this->assertEqual($data_obj->test_id, 1);

        // valid fuax test dao
        Config::getInstance()->setValue('db_type', 'faux');
        $test_dao = DAOFactory::getDAO('TestDAO');
        $this->assertIsA($test_dao, 'TestFauxDAO', 'we are a mysql dao');
        $data_obj = $test_dao->selectRecord(1);
        $this->assertNotNull($data_obj);
        $this->assertEqual($data_obj->test_name, 'Mojo Jojo');
        $this->assertEqual($data_obj->test_id, 2001);
    }

    //    public function testGetInstanceDAO(){
    //        $dao = DAOFactory::getDAO('InstanceDAO');
    //        $this->assertTrue(isset($dao));
    //        $this->assertIsA($dao, 'InstanceMySQLDAO');
    //    }
}
