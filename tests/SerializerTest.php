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

class SerializerTest extends IsoscelesBasicUnitTestCase {

    public function testUnserializeValidString() {
        //Valid string
        $result = Serializer::unserializeString('O:1:"a":1:{s:5:"value";s:3:"100";}');
        $this->assertNotNull($result);
    }
    /**
     * @expectedException SerializerException
     */
    public function testUnserializeInvalidString() {
        //$this->expectException("SerializerException");
        $result = Serializer::unserializeString("{'Organization': 'ThinkUp Documentation Team'}");
        $this->assertNull($result);
    }
}
