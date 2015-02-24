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

class UtilsTest extends IsoscelesBasicUnitTestCase {

    public function testIndentJSON() {
        $data = array('jam', 'jelly', 'ham', 'biscuits', array ( 'cola', 'beer', 'grapefruit juice' ));

        $test_str = '[
    "jam",
    "jelly",
    "ham",
    "biscuits",
    [
        "cola",
        "beer",
        "grapefruit juice"
    ]
]';

        $json_data = json_encode($data);
        $indented_json_data = Utils::indentJSON($json_data);
        $this->assertEquals($test_str, $indented_json_data);
        //$this->assertTrue($json_data !== $indented_json_data);

        $data = new stdClass();
        $data->name = 'Dave';
        $data->job = 'Fixing stuff.';
        $data->link = 'http://thereifixedit.com';
        $data->spouse = new stdClass();
        $data->spouse->name = 'Jill';
        $data->spouse->job = 'CEO of MadeUp inc.';

        $test_str = '{
    "name":"Dave",
    "job":"Fixing stuff.",
    "link":"http:\/\/thereifixedit.com",
    "spouse":{
        "name":"Jill",
        "job":"CEO of MadeUp inc."
    }
}';

        $json_data = json_encode($data);
        $indented_json_data = Utils::indentJSON($json_data);
        $this->assertEquals($test_str, $indented_json_data);
        //$this->assertNotEqual($json_data, $indented_json_data);

        $data = new stdClass();
        $data->test1 = 'This text element should totally not wrap "just because" it ends with a :\\';
        $data->test2 = 'What if I end with double slashes!? \\\\';
        $data->test3 = 'Oh, "just because :\ ", she said';

        $test_str = '{
    "test1":"This text element should totally not wrap \"just because\" it ends with a :\\\\",
    "test2":"What if I end with double slashes!? \\\\\\\\",
    "test3":"Oh, \"just because :\\\\ \", she said"
}';
        $json_data = json_encode($data);
        $indented_json_data = Utils::indentJSON($json_data);
        $this->debug($indented_json_data);
        $this->assertEquals($test_str, $indented_json_data);
    }

    public function testConvertNumericStrings() {
        // integer
        $test_str = '"123456789"';
        $number = '123456789';
        $converted = Utils::convertNumericStrings($test_str);
        $this->assertEquals($converted, $number);

        // float
        $test_str = '"1234.56789"';
        $number = '1234.56789';
        $converted = Utils::convertNumericStrings($test_str);
        $this->assertEquals($converted, $number);

        // not a number
        $test_str = '"123456789s"';
        $number = '"123456789s"';
        $converted = Utils::convertNumericStrings($test_str);
        $this->assertEquals($converted, $number);

        // not a float
        $test_str = '"12345.6789s"';
        $number = '"12345.6789s"';
        $converted = Utils::convertNumericStrings($test_str);
        $this->assertEquals($converted, $number);

        // two dots, not a number
        $test_str = '"12345.6.789"';
        $number = '"12345.6.789"';
        $converted = Utils::convertNumericStrings($test_str);
        $this->assertEquals($converted, $number);
    }

    public function testSetDefaultTimezonePHPini() {
        // ini value present, should be set to that
        ini_set('date.timezone','America/New_York');
        Utils::setDefaultTimezonePHPini();
        $tz = ini_get('date.timezone');
        //$tz = date_default_timezone_get();
        $this->assertEquals($tz, 'America/New_York');
    }
}
