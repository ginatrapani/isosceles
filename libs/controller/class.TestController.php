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
 * You should have received a copy of the GNU General Public License along with ThinkUp.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * Test Controller
 *
 * Test controller to try the Controller abstract class.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

class TestController extends Controller {

    public function control() {
        if (isset($_GET['json'])) {
            $this->setJsonData( array( 'aname' => 'a value', 'alist' => array('apple', 'pear', 'banana'), 'alink' =>
            'http://isosceleskit.org'));
        } else if (isset($_GET['text'])) {
            $this->setContentType('text/plain');
        } else if (isset($_GET['png'])) {
            $this->setContentType('image/png');
        } else if (isset($_GET['css'])) {
            $this->addHeaderCSS('assets/css/bla.css');
            $this->setViewTemplate('isosceles.testcontroller.tpl');
        }
        if (isset($_GET['throwexception'])) {
            throw new Exception("Testing exception handling!");
        } else if (!isset($_GET['json']) && !isset($_GET['css'])) {
            $this->setViewTemplate('isosceles.testcontroller.tpl');
        }
        $this->addToView('test', 'Testing, testing, 123');
        if (isset($_GET['username'])) {
            $this->addToView('username', $_GET['username']);
        }
        if (isset($_GET['network'])) {
            $this->addToView('network', $_GET['network']);
        }
        return $this->generateView();
    }
}
