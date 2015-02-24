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
 * @license http://www.gnu.org/licenses/gpl.html
 */

class IsoscelesSignInController extends Controller {

    public function control() {
        $this->setViewTemplate('isosceles-signin-controller.tpl');

        // Child classes should check user against the database here
        if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['passwd'])) {
            Session::completeLogin($_POST['email']);
            $this->addSuccessMessage($_POST['email'].' has signed in.');
            if (isset($_GET['redirect'])) {
                if (!$this->redirect($_GET['redirect'])) {
                    $this->generateView(); //for testing
                }
            }
        }
        if (Session::isLoggedIn()) {
            $logged_in_user = Session::getLoggedInUser();
            $this->addToView('logged_in_user', $logged_in_user);
        }
        return $this->generateView();
    }
}
