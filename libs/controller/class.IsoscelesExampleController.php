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
 * You should have received a copy of the GNU General Public License along with ThinkUp.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * Example Controller
 *
 * Example controller to demonstrate an implementation of the Controller abstract class.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

class IsoscelesExampleController extends Controller {

    public function control() {
        //Set logged_in_user if there is one
        if (Session::isLoggedIn()) {
            $logged_in_user = Session::getLoggedInUser();
            $this->addToView('logged_in_user', $logged_in_user);
        }

        //Demonstrate content types
        if (isset($_GET['json'])) {
            $this->setJsonData( array( 'aname' => 'a value', 'alist' => array('apple', 'pear', 'banana'), 'alink' =>
            'http://isosceleskit.org'));
        } else if (isset($_GET['text'])) {
            $this->setContentType('text/plain');
        } else if (isset($_GET['png'])) {
            $this->setContentType('image/png');
        } else if (isset($_GET['css'])) {
            $this->addHeaderCSS('assets/css/bla.css');
            $this->setViewTemplate('isosceles-example-controller.tpl');
        }

        //Demonstrate exceptions
        if (isset($_GET['throwexception'])) {
            throw new Exception("Testing exception handling!");
        } else if (!isset($_GET['json']) && !isset($_GET['css'])) {
            $this->setViewTemplate('isosceles-example-controller.tpl');
        }

        $this->addToView('test', 'Testing, testing, 123');

        //Demonstrate parameters via router
        if (isset($_GET['username'])) {
            $this->addToView('username', $_GET['username']);
        }
        if (isset($_GET['network'])) {
            $this->addToView('network', $_GET['network']);
        }

        //Demonstrate user messaging
        if (isset($_GET['success'])) {
            $this->addSuccessMessage($_GET['success']);
        }
        if (isset($_GET['error'])) {
            $this->addErrorMessage($_GET['error']);
        }
        if (isset($_GET['info'])) {
            $this->addInfoMessage($_GET['info']);
        }

        //Demonstrate custom config values
        $custom_config_1 = Config::getInstance()->getValue('my_custom_config_1');
        $custom_config_2 = Config::getInstance()->getValue('my_custom_config_2');
        $this->addToView('custom_config_1', $custom_config_1);
        $this->addToView('custom_config_2', $custom_config_2);

        return $this->generateView();
    }
}
