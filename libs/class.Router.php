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
 * Router
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

class Router {
    /**
     * Path/controller associative array
     * @var array
     */
    public static $routes = array();
    /**
     * Path/parameter associative array
     * @var array
     */
    public static $route_parameters=array();
    /**
     * Constructor with optionally-defined 404 controller.
     * @param string $not_found Name of 404 controller.
     */
    public function __construct($not_found = 'IsoscelesPageNotFoundController') {
        if (!isset(self::$routes['404'])) {
            $this->addRoute('404', $not_found);
        }
    }
    /**
     * Route request and return results of appropriate controller call.
     * @return str
     */
    public function route($session_started=false) {
        $url = explode('?',$_SERVER['REQUEST_URI']);
        $path = mb_strtolower($url[0]);
        while (substr($path, -1) == '/') {
            $path = mb_substr($path,0,(mb_strlen($path)-1));
        }
        $path_components = explode('/', $path);

        $slug = (isset($path_components[1]))?$path_components[1]:'';
        $slug = ($slug=='')?'index':$slug;
        if (isset(self::$routes[$slug])) {
            if (isset(self::$route_parameters[$slug])) {
                foreach (self::$route_parameters[$slug] as $index=>$parameter) {
                    if (isset($path_components[$index+2])) {
                        $_GET[$parameter] = $path_components[$index+2];
                    }
                }
            }
            $controller = new self::$routes[$slug]($session_started);
        } else {
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
            $controller = new self::$routes['404']($session_started);
        }
        return $controller->go();
    }
    /**
     * Add route.
     * @param $slug
     * @param $controller_name
     */
    public function addRoute($slug, $controller_name, $parameters=null) {
        self::$routes[$slug] = $controller_name;
        self::$route_parameters[$slug] = $parameters;
    }
}