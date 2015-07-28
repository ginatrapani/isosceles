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
 * Isosceles Internal Server Error (500) Controller
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

// @TODO Support JSON and txt responses, not just HTML
class IsoscelesInternalServerErrorController extends Controller {
    public function control() {
        $this->setViewTemplate('isosceles.500.tpl');
        if (isset($_SERVER["SERVER_PROTOCOL"])) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        }
        return $this->generateView();
    }
}