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
 * You should have received a copy of the GNU General Public License along with Isosceles.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * Controller
 *
 * The parent class of all webapp controllers.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

if ( version_compare(PHP_VERSION, '5.2', '<') ) {
    exit("ERROR: Isosceles requires PHP 5.2 or greater. The current version of PHP is ".PHP_VERSION.".");
}

//Define path globals
if (file_exists(str_replace("\\",'/', dirname(dirname(__FILE__))) .'/' . 'webapp')) { // source repo
    define('ROOT_PATH', str_replace("\\",'/', dirname(dirname(__FILE__))) .'/');
    define('WEBAPP_PATH', ROOT_PATH . 'webapp/');
} else { // distro package
    define('ROOT_PATH', str_replace("\\",'/', dirname(__FILE__)) .'/');
    define('WEBAPP_PATH', ROOT_PATH);
}

//Register our lazy class loader
require_once '_lib/model/class.Loader.php';

Loader::register();
