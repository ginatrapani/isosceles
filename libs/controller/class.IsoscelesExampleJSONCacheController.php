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
 * Example JSON Cache Controller
 *
 * Example controller to demonstrate an implementation of the Controller abstract class which returns JSON cached.
 * To test this in-browser, turn on caching and the profiler
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

class IsoscelesExampleJSONCacheController extends Controller {

    public function control() {
        $this->setUpJsonResponse();

        if ($this->shouldRefreshCache() ) {
            $test_json_data = array('Testing'=>'yes', "yadda"=>'indeed');
            $this->setJsonData($test_json_data);
        }
        return $this->generateView();
    }
}
