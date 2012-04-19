<?php
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

        $slug = $path_components[1];
        $slug = ($slug=='')?'index':$slug;
        if (isset(self::$routes[$slug])) {
            if (isset(self::$route_parameters[$slug])) {
                foreach (self::$route_parameters[$slug] as $index=>$parameter) {
                    $_GET[$parameter] = $path_components[$index+2];
                }
            }
            $controller = new self::$routes[$slug]($session_started);
            return $controller->go();
        } else {
            return "404 route not found: ".$slug;
        }
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