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

class PDODAOTest extends IsoscelesUnitTestCase {

    public function setUp() {
        parent::setUp();
    }

    protected function buildData() {
        $config = Config::getInstance();
        $config_array = $config->getValuesArray();
        $builders = array();

        $test_table_sql = 'CREATE TABLE ' . $config_array['table_prefix'] . 'test_table(' .
            'id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,' .
            'test_name varchar(20),' .
            'test_id int(11),' .
            'unique key test_id_idx (test_id)' .
            ')';
        $this->testdb_helper->runSQL($test_table_sql);

        for($i = 1; $i <= 20; $i++) {
            $builders[] = FixtureBuilder::build('test_table', array('test_name'=>'name'.$i, 'test_id'=>$i));
        }

        // Insert test data into test user table
        $builders[] = FixtureBuilder::build('users', array('user_id'=>12, 'user_name'=>'mary',
        'full_name'=>'Mary Jane', 'avatar'=>'avatar.jpg'));
        $builders[] = FixtureBuilder::build('users', array('user_id'=>13, 'user_name'=>'sweetmary',
        'full_name'=>'Sweet Mary Jane', 'avatar'=>'avatar.jpg'));
        return $builders;
    }

    /*
     * Test whether the database supports time zones or only offsets
     */
    private function isTimeZoneSupported() {
        $testdao = DAOFactory::getDAO('TestDAO');
        try {
            TestMySQLDAO::$PDO->exec("SET time_zone = 'America/Los_Angeles'");
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function tearDown() {
        $this->builders = null;
        parent::tearDown();
    }

    public function testInitDAO() {
        $testdao = DAOFactory::getDAO('TestDAO');
        $this->assertNotNull(TestMySQLDAO::$PDO);
        $this->assertNotNull($testdao->config);
    }

    public function testConvertBetweenBoolAndDB() {
        $testdao = DAOFactory::getDAO('TestDAO');
        $this->assertEquals(0, $testdao->testBoolToDB(null), 'should be 0');
        $this->assertEquals(0, $testdao->testBoolToDB(''), 'should be 0');
        $this->assertEquals(0, $testdao->testBoolToDB(false), 'should be 0');
        $this->assertEquals(0, $testdao->testBoolToDB(0), 'should be 0');
        $this->assertEquals(1, $testdao->testBoolToDB('a'), 'should be 1');
        $this->assertEquals(1, $testdao->testBoolToDB(true), 'should be 1');
        $this->assertEquals(1, $testdao->testBoolToDB(new TestData()), 'should be 1');
        $this->assertEquals(1, $testdao->testBoolToDB(1), 'should be 1');

        $this->assertEquals(false, $testdao->convertDBToBool(0), 'should be true');
        $this->assertEquals(true, $testdao->convertDBToBool(1), 'should be false');
    }

    public function testTwoObjectsOneConnection() {
        DAOFactory::getDAO('TestDAO');
        $this->assertNotNull(TestMySQLDAO::$PDO);
        TestMySQLDAO::$PDO->tu_testing = "testing";
        $testdao2 = DAOFactory::getDAO('TestDAO');
        $this->assertEquals(TestMySQLDAO::$PDO->tu_testing, "testing");
    }

    public function testBasicSelectUsingStatementHandleDirectly() {
        $this->builders = self::buildData();
        $testdao = DAOFactory::getDAO('TestDAO');
        $users = $testdao->getUserCount(0, 'mary');
        $this->assertTrue(is_array($users));
        $this->assertEquals(count($users), 2);
        $this->assertEquals($users[0]['user_name'], 'mary');
        $this->assertEquals($users[1]['user_name'], 'sweetmary');
    }

    public function testBadSql() {
        $testdao = DAOFactory::getDAO('TestDAO');
        try {
            $testdao->badSql();
        } catch(PDOException $e) {
            $this->assertRegExp('/Syntax error/', $e->getMessage(), 'should get a Syntax error message');
        }
    }

    public function testBadBinds() {
        $testdao = DAOFactory::getDAO('TestDAO');
        try {
            $testdao->badBinds();
        } catch(PDOException $e) {
            $this->assertRegExp('/Invalid parameter number/', $e->getMessage(),
            'should get an Invalid parameter number error message');
        }
    }

    public function testInsertData() {
        $this->builders = self::buildData();
        $testdao = DAOFactory::getDAO('TestDAO');

        // ad a test_user with a test_id get insert count
        $cnt = $testdao->insertDataGetCount('test_user', '1000');
        $this->assertEquals(1, $cnt, "should have inserted 1 record");

        // add test_naem and id get last insert id
        $cnt = $testdao->insertDataGetId('test_user', '1001');
        $this->assertEquals(22, $cnt, "should get an insert id of 22");

        // add multiple records and get count
        $cnt = $testdao->insertMultiDataGetCount(array( array( 'test_user23', '23'), array( 'test_user24', '24') ));
        $this->assertEquals(2, $cnt, "should get an insert count of 2");

        // test duplicate key err, check for message?
        try {
            $cnt = $testdao->insertDataGetCount('test_user', '1000');
            $this->fail('should throw a PDOException');
        } catch(PDOException $e) {
            $this->assertRegExp('/Duplicate entry/', $e->getMessage(), 'should get a dup key message');
        }
    }

    public function testUpdateData() {
        $this->builders = self::buildData();
        $testdao = DAOFactory::getDAO('TestDAO');

        // update a with a bad test_id get 0 insert count
        $cnt = $testdao->update( 'sally', 9999);
        $this->assertEquals(0, $cnt, "updated 0 records");

        // update a test_user with a test_id get insert count
        $cnt = $testdao->update('harry', 1);
        $this->assertEquals(1, $cnt, "updated 1 record");

        // update multiple test_users
        $cnt = $testdao->updateMulti('nick', 1);
        $this->assertEquals(19, $cnt, "updated 19 records");
    }

    public function testSelectSingleRecord() {
        $this->builders = self::buildData();
        $testdao = DAOFactory::getDAO('TestDAO');

        $data_obj = $testdao->selectRecord(999);
        $this->assertNull($data_obj);

        $data_obj = $testdao->selectRecord(2);
        $this->assertEquals($data_obj->id, '2');
        $this->assertEquals($data_obj->test_name, 'name2');
        $this->assertEquals($data_obj->test_id, '2');
    }

    public function testSelectSingleRecordAsArray() {
        $this->builders = self::buildData();
        $testdao = DAOFactory::getDAO('TestDAO');
        $data_obj = $testdao->selectRecordAsArray(999);
        $this->assertNull($data_obj);

        $data_obj = $testdao->selectRecordAsArray(3);
        $this->assertEquals($data_obj['id'], '3');
        $this->assertEquals($data_obj['test_name'], 'name3');
        $this->assertEquals($data_obj['test_id'], '3');
    }

    public function testSelectRecordsAsArrayWithLimit() {
        $this->builders = self::buildData();
        $testdao = DAOFactory::getDAO('TestDAO');
        $data_obj = $testdao->selectRecordsWithLimit(2);
        $this->assertEquals(count($data_obj), 2, 'should have limited to two records');
    }

    public function testSelectRecords() {
        $this->builders = self::buildData();
        $testdao = DAOFactory::getDAO('TestDAO');

        //this should return no records
        $data_array = $testdao->selectRecords(999);
        $this->assertTrue(is_array($data_array), "array should be returned");
        $this->assertEquals(count($data_array), 0, "Empty array should be returned");

        // get all data with a test_id greater than 5
        $data_array = $testdao->selectRecords(5);
        $this->assertTrue(is_array($data_array), "array should be returned");
        $this->assertEquals(count($data_array), 16, 'we have 16 records');
        for( $i = 0; $i < 16; $i++ ) {
            $data_obj = $data_array[$i];
            $this->assertInstanceOf("TestData", $data_obj);
            $id = $i + 5;
            $this->assertEquals($data_obj->test_id, $id, "we have test_id $i");
        }
    }

    public function testSelectRecordsAsArray() {
        $this->builders = self::buildData();
        $testdao = DAOFactory::getDAO('TestDAO');

        //this should return no records
        $data_array = $testdao->selectRecordsAsArrays(999);
        $this->assertTrue(is_array($data_array), "array should be returned");
        $this->assertEquals(count($data_array), 0, "Empty array should be returned");

        // get all data with a test_id greater than 8
        $data_array = $testdao->selectRecordsAsArrays(8);
        $this->assertTrue(is_array($data_array), "array should be returned");
        $this->assertEquals(count($data_array), 13, 'we have 13 records');
        for( $i = 0; $i < 13; $i++ ) {
            $data_obj = $data_array[$i];
            $this->assertTrue(is_array($data_obj), "array");
            $id = $i + 8;
            $this->assertEquals($data_obj['test_id'], $id, "we have test_id $i");
        }
    }

    public function testDeleteData() {
        $this->builders = self::buildData();
        $testdao = DAOFactory::getDAO('TestDAO');

        // nothing deleted
        $cnt = $testdao->delete(9999);
        $this->assertEquals(0, $cnt, "deleted 0 records");

        // one record deleted
        $cnt = $testdao->delete(19);
        $this->assertEquals(1, $cnt, "deleted 1 record");

        // delete multiple test_users
        $cnt = $testdao->delete(5);
        $this->assertEquals(14, $cnt, "deleted 14 records");
    }

    public function testIsPattern() {
        $this->builders = self::buildData();
        $testdao = DAOFactory::getDAO('TestDAO');

        // nothing deleted
        $cnt = $testdao->isExisting(9999);
        $this->assertFalse($cnt);

        // one record deleted
        $cnt = $testdao->isExisting(19);
        $this->assertTrue($cnt);
    }

    public function testInstantiateDaoWithoutConfigFile() {
        $config = Config::getInstance();
        $config_array = $config->getValuesArray();
        $this->builders = self::buildData();
        try {
            $this->removeConfigFile();
            Config::destroyInstance();
            $cfg_values = array("table_prefix"=> $config_array['table_prefix'], "db_host"=>"localhost");
            $config = Config::getInstance($cfg_values);
            $test_dao = new TestMySQLDAO($cfg_values);
            $users = $test_dao->getUserCount(0, 'mary');
            $this->assertTrue(is_array($users), "array");
            $this->assertEquals(count($users), 2);
            $this->assertEquals($users[0]['user_name'], 'mary');
            $this->assertEquals($users[1]['user_name'], 'sweetmary');
        } catch (Exception $e) {
            // restore config file if something goes wrong
            $this->restoreConfigFile();
            throw $e;
        }
        $this->restoreConfigFile();
    }

    public function testGetConnectString() {
        $this->removeConfigFile();
        Config::destroyInstance();
        $cfg_values = array("db_host"=>"localhost");
        $config = Config::getInstance($cfg_values);
        $this->assertEquals(PDODAO::getConnectString($config), "mysql:dbname=;host=localhost");

        $this->removeConfigFile();
        Config::destroyInstance();
        $cfg_values = array("db_type" => "mysql", "db_host"=>"localhost", "db_name" => "isosceles");
        $config = Config::getInstance($cfg_values);
        $this->assertEquals(PDODAO::getConnectString($config), "mysql:dbname=isosceles;host=localhost");

        $this->removeConfigFile();
        Config::destroyInstance();
        $cfg_values = array("db_host"=>"localhost", "db_name" => "isosceles", "db_port" => "3306");
        $config = Config::getInstance($cfg_values);
        $this->assertEquals(PDODAO::getConnectString($config), "mysql:dbname=isosceles;host=localhost;port=3306");

        $this->removeConfigFile();
        Config::destroyInstance();
        $cfg_values = array("db_host"=>"localhost", "db_name" => "isosceles", "db_socket" => "/var/mysql");
        $config = Config::getInstance($cfg_values);
        $this->assertEquals(PDODAO::getConnectString($config),
        "mysql:dbname=isosceles;host=localhost;unix_socket=/var/mysql");
        $this->restoreConfigFile();
    }

    public function testCompareTimezoneOffsets() {
        $config = Config::getInstance();
        $config->setValue('timezone', 'Europe/London');
        $timezone = $config->getValue('timezone');
        $time = new DateTime("now", new DateTimeZone($timezone) );
        $tz_config = $time->format('P');

        //destroy the existing PDO connection which gets established in setUp to start with a clean slate
        TestMySQLDAO::destroyPDO();

        // this should return the same timezone offset as the config value
        $test_dao = new TestMySQLDAO();
        $tz_server = $test_dao->getTimezoneOffset();

        if ($this->isTimeZoneSupported()) {
            $this->assertEquals('Europe/London', $tz_server['tz_offset']);
        } else {
            $this->assertEquals($tz_config, $tz_server['tz_offset']);
        }
        Config::destroyInstance();
    }

    /*
     * To fully test this, you need to run the test with the timezone tables both empty and populated, and during DST
     * and outside of DST.
     *
     * Truncate the timezone tables.
     *
     * $ echo "TRUNCATE TABLE time_zone; TRUNCATE TABLE time_zone_name; TRUNCATE TABLE time_zone_transition;
     * TRUNCATE TABLE time_zone_transition_type;" | sudo mysql mysql
     * $ sudo /etc/init.d/mysql restart
     * Stopping MySQL database server: mysqld.
     * Starting MySQL database server: mysqld.
     * Checking for tables which need an upgrade, are corrupt or were not closed cleanly..
     *
     * Tests should fail because we can't set the time_zone session variable correctly
     *
     * $ faketime '2011-11-01' php tests/TestOfPDODAO.php -t testCompareMySQLAndPHPTimezoneOffsets
     * TestOfPDODAO.php
     * 1) Equal expectation fails at character 6 with [1293865200] and [1293868800] at
     * [/home/cwarden/git/ThinkUp/tests/TestOfPDODAO.php line 351]
     * in testCompareMySQLAndPHPTimezoneOffsets
     * in TestOfPDODAO
     * FAILURES!!!
     *
     * Test cases run: 1/3, Passes: 1, Failures: 1, Exceptions: 0
     * $ faketime '2011-01-01' php tests/TestOfPDODAO.php -t testCompareMySQLAndPHPTimezoneOffsets
     * TestOfPDODAO.php
     * 1) Equal expectation fails at character 6 with [1314864000] and [1314860400] at
     * [/home/cwarden/git/ThinkUp/tests/TestOfPDODAO.php line 358]
     * in testCompareMySQLAndPHPTimezoneOffsets
     * in TestOfPDODAO
     * FAILURES!!!
     * Test cases run: 1/3, Passes: 1, Failures: 1, Exceptions: 0
     *
     * Populate the timezone tables
     *
     * $ mysql_tzinfo_to_sql /usr/share/zoneinfo | sudo mysql mysql
     *
     * Now the tests will succeed
     *
     * $ faketime '2011-11-01' php tests/TestOfPDODAO.php -t testCompareMySQLAndPHPTimezoneOffsets
     * TestOfPDODAO.php
     * OK
     * Test cases run: 1/3, Passes: 2, Failures: 0, Exceptions: 0
     * $ faketime '2011-01-01' php tests/TestOfPDODAO.php -t testCompareMySQLAndPHPTimezoneOffsets
     * TestOfPDODAO.php
     * OK
     * Test cases run: 1/3, Passes: 2, Failures: 0, Exceptions: 0
     */
    public function testCompareMySQLAndPHPTimezoneOffsets() {
        if (!$this->isTimeZoneSupported()) {
            return;
        }
        // These tests will only be run if the time_zone tables are populated in MySQL.
        // See http://dev.mysql.com/doc/refman/5.1/en/mysql-tzinfo-to-sql.html
        $config = Config::getInstance();
        // set timezones the same for MySQL and PHP
        $config->setValue('timezone', 'America/Los_Angeles');
        date_default_timezone_set('America/Los_Angeles');
        $timezone = $config->getValue('timezone');

        TestMySQLDAO::destroyPDO();
        $testdao = DAOFactory::getDAO('TestDAO');

        // test time outside of daylight saving time
        $stmt = TestMySQLDAO::$PDO->query('SELECT UNIX_TIMESTAMP("2011-01-01 00:00:00") AS time');
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $mysql_time = $row['time'];
        $php_time = strtotime('2011-01-01 00:00:00');
        $this->assertEquals($mysql_time, $php_time);

        // test time during daylight saving time
        $stmt = TestMySQLDAO::$PDO->query('SELECT UNIX_TIMESTAMP("2011-09-01 00:00:00") AS time');
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $mysql_time = $row['time'];
        $php_time = strtotime('2011-09-01 00:00:00');
        $this->assertEquals($mysql_time, $php_time);
    }
}
