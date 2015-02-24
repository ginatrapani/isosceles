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
 * Auth Controller
 *
 * Controllers that require the user is signed in.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

abstract class AuthController extends Controller {
    /**
     * The web app URL this controller maps to.
     * @var str
     */
    var $url_mapping = null;
    /**
     * Redirect destination.
     * @var str
     */
    var $redirect_destination;
    /**
     * Constructor
     * @param boolean $session_started
     */
    public function __construct($session_started=false) {
        parent::__construct($session_started);
        if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'logout.php') === false ) {
            $this->url_mapping = $_SERVER['REQUEST_URI'];
        }
    }
    /**
     * Main control method
     * @return str $response
     */
    public function control() {
        $response = $this->preAuthControl();
        if (!$response) {
            if (Session::isLoggedIn()) {
                return $this->authControl();
            } else {
                return $this->bounce();
            }
        } else {
            return $response;
        }
    }
    /**
     * A child class can override this method to define other auth mechanisms.
     * If the return is not false it assumes the child class has validated the user and has called authControl()
     * @return boolean PreAuthed
     */
    protected function preAuthControl() {
        return false;
    }
    /**
     * Bounce user to log in.
     * A child class can override this method to define different bounce behavior.
     */
    protected function bounce() {
        if ($this->url_mapping != null ) {
            $this->redirect(Config::getInstance()->getValue('site_root_path').'signin/?redirect='.$this->url_mapping);
        } else {
            $controller = new IsoscelesSignInController(true);
            return $controller->go();
        }
    }
}