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
 * Isosceles Page Not Found (404) Controller
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

class IsoscelesPageNotFoundController extends Controller {
    public function control() {
        $this->setViewTemplate('isosceles-page-not-found-controller.tpl');
        if (isset($_SERVER["SERVER_PROTOCOL"])) {
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 400);
        }
       return $this->generateView();
    }
}