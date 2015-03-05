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

class JSONDecoderTest extends IsoscelesBasicUnitTestCase {

    public function testDecodeValidJSON() {
        //Valid JSON
        $result = JSONDecoder::decode('{"Organization": "ThinkUp Documentation Team"}');
        $this->assertNotNull($result);
        $this->assertEquals($result->Organization, "ThinkUp Documentation Team");

        //Valid JSON returned as associative array
        $result = JSONDecoder::decode('{"Organization": "ThinkUp Documentation Team"}', $assoc=true);
        $this->assertNotNull($result);
        $this->assertEquals($result["Organization"], "ThinkUp Documentation Team");
    }
    /**
     * @expectedException JSONDecoderException
     */
    public function testDecodeInvalidJSON() {
        //$this->expectException("JSONDecoderException");
        $result = JSONDecoder::decode("{'Organization': 'ThinkUp Documentation Team'}");
        $this->assertNull($result);
    }
}
