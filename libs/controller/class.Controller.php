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
 * Controller
 *
 * The parent class of all webapp controllers.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

abstract class Controller {
    /**
     * @var ViewManager
     */
    protected $view_mgr;
    /**
     * @var string Smarty template filename
     */
    protected $view_template = null;
    /**
     *
     * @var string cache key separator
     */
    const KEY_SEPARATOR='-';
    /**
     *
     * @var bool
     */
    protected $profiler_enabled = false;
    /**
     *
     * @var float
     */
    private $start_time = 0;
    /**
     *
     * @var array
     */
    protected $header_scripts = array ();
    /**
     *
     * @var array
     */
    protected $header_css = array ();
    /**
     *
     * @var array
     */
    protected $json_data = null;
    /**
     *
     * @var str
     */
    protected $content_type = 'text/html; charset=UTF-8'; //default
    /**
     *
     * @var boolean if true we will pass a CSRF token to the view
     */
    protected $view_csrf_token = false; //default
    /**
     *
     * @var boolean if true we will pass a CSRF token to the view
     */
    var $redirect_destination;
    /**
     * Constructs Controller
     *  @return Controller
     */
    public function __construct($session_started=false) {
        if (!$session_started) {
            session_start();
        }
        try {
            $config = Config::getInstance();
            $this->profiler_enabled = Profiler::isEnabled();
            if ( $this->profiler_enabled) {
                $this->start_time = microtime(true);
            }
            $this->view_mgr = new ViewManager();

        } catch (Exception $e) {
            Loader::definePathConstants();
            $cfg_array =  array(
            'site_root_path'=>BASE_URL,
            'source_root_path'=>ISOSCELES_PATH,
            'debug'=>false,
            'cache_pages'=>false);
            $this->view_mgr = new ViewManager($cfg_array);
        }
    }

    /**
     * Handle request parameters for a particular resource and return view markup.
     *
     * @return str Markup which renders controller results.
     */
    abstract public function control();

    /**
     * Returns cache key as a string,
     * Preface every key with .ht to make resulting file "forbidden" by request thanks to Apache's default rule
     * <FilesMatch "^\.([Hh][Tt])">
     *    Order allow,deny
     *    Deny from all
     *    Satisfy All
     * </FilesMatch>
     *
     * Set to public for the sake of tests only.
     * @return str cache key
     */
    public function getCacheKeyString() {
        $view_cache_key = array();
        // if ($this->getLoggedInUser()) {
        //     array_push($view_cache_key, $this->getLoggedInuser());
        // }
        $keys = array_keys($_GET);
        foreach ($keys as $key) {
            array_push($view_cache_key, $_GET[$key]);
        }
        return '.ht'.$this->view_template.self::KEY_SEPARATOR.(implode($view_cache_key, self::KEY_SEPARATOR));
    }

    /**
     * Generates web page markup
     *
     * @return str view markup
     */
    protected function generateView() {
        // add header javascript if defined
        if ( count($this->header_scripts) > 0) {
            $this->addToView('header_scripts', $this->header_scripts);
        }
        // add header CSS if defined
        if ( count($this->header_css) > 0) {
            $this->addToView('header_css', $this->header_css);
        }
        // add CSRF token if enabled and defined
        if ($this->view_csrf_token) {
            $csrf_token = Session::getCSRFToken();
            if (isset($csrf_token)) {
                $this->addToView('csrf_token', $csrf_token);
            }
        }

        $this->sendHeader();
        if (strpos($this->content_type, 'application/json') === false) { //Not JSON
            if ($this->view_mgr->isViewCached()) {
                $cache_key = $this->getCacheKeyString();
                if ($this->profiler_enabled && strpos($this->content_type, 'text/javascript') === false) {
                    $view_start_time = microtime(true);
                    $cache_source = $this->shouldRefreshCache()?"DATABASE":"FILE";
                    $results = $this->view_mgr->fetch($this->view_template, $cache_key);
                    $view_end_time = microtime(true);
                    $total_time = $view_end_time - $view_start_time;
                    $profiler = Profiler::getInstance();
                    $profiler->add($total_time, "Rendered view from ". $cache_source . ", cache key: <i>".
                        $this->getCacheKeyString(), false).'</i>';
                    return $results;
                } else {
                    return $this->view_mgr->fetch($this->view_template, $cache_key);
                }
            } else {
                if ($this->profiler_enabled && strpos($this->content_type, 'text/javascript') === false) {
                    $view_start_time = microtime(true);
                    $results = $this->view_mgr->fetch($this->view_template);
                    $view_end_time = microtime(true);
                    $total_time = $view_end_time - $view_start_time;
                    $profiler = Profiler::getInstance();
                    $profiler->add($total_time, "Rendered view (not cached)", false);
                    return $results;
                } else  {
                    return $this->view_mgr->fetch($this->view_template);
                }
            }
        } else { //JSON
            if ($this->view_mgr->isViewCached()) {
                echo "CACHING IS ON ";
                $cache_key = $this->getCacheKeyString();
                if ($this->profiler_enabled) {
                    echo "PROFILER IS ENABLED ";
                    $profiler = Profiler::getInstance();
                    $profiler_items = $profiler->getProfile();
                    $this->json_data['isosceles_profiler'] = $profiler_items;
                    $this->prepareJSON();
                }
                return $this->view_mgr->fetch('isosceles.json.tpl', $cache_key);
            } else {
                echo "CACHING IS OFF ";
                if ($this->profiler_enabled) {
                    echo "PROFILER IS ENABLED ";
                    $profiler = Profiler::getInstance();
                    $view_render_time = 0; //There's no logic in the JSON Smarty template
                    $profiler->add(0, "Rendered view (not cached)", false);
                    $profiler_items = $profiler->getProfile();
                    $this->json_data['isosceles_profiler'] = $profiler_items;
                    $this->prepareJSON();
                }
                return $this->view_mgr->fetch($this->view_template);
            }
        }
    }

    /**
     * Prepares the JSON data in $this->json_data and adds it to the current view under the key "json".
     * To change the parameters, the child controller can override this function, and call the parent with
     * different defaults
     *
     * @param bool $indent Whether or not to indent the JSON string. Defaults to true.
     * @param bool $stripslashes Whether or not to strip escaped slashes. Default to true.
     * @param bool $convert_numeric_strings Whether or not to convert numeric strings to numbers. Defaults to true.
     */
    protected function prepareJSON($indent = true, $stripslashes = true, $convert_numeric_strings = true) {
        if (isset($this->json_data)) {
            $json = json_encode($this->json_data);
            if ($stripslashes) {
                // strip escaped forwardslashes
                $json = preg_replace("/\\\\\//", '/', $json);
            }
            if ($convert_numeric_strings) {
                // converts numeric strings to numbers
                $json = Utils::convertNumericStrings($json);
            }
            if ($indent) {
                // indents JSON strings so they are human readable
                $json = Utils::indentJSON($json);
            }
            $this->addToView('json', $json);
        }
    }

    /**
     * Send content type header
     */
    protected function sendHeader() {
        if ( ! headers_sent() ) { // suppress 'headers already sent' error while testing
            header('Content-Type: ' . $this->content_type, true);
        }
    }
    /**
     * Sets the view template filename
     *
     * @param str $tpl_filename
     */
    protected function setViewTemplate($tpl_filename) {
        $this->view_template = $tpl_filename;
    }

    /**
     * Sets json data structure to output a json string, and sets Content-Type to appplication/json
     *
     * @param array json data
     */
    protected function setJsonData($data) {
        if ($data != null) {
            $this->setContentType('application/json');
        }

        $this->json_data = $data;
    }

    /**
     * Sets Content Type header
     *
     * @param string Content Type
     */
    protected function setContentType($content_type) {
        if ($content_type != 'image/png') {
            $this->content_type = $content_type.'; charset=UTF-8';
        } else {
            $this->content_type = $content_type;
        }
    }

    /**
     * Gets Content Type header
     *
     * @return string Content Type
     */
    public function getContentType() {
        return $this->content_type;
    }

    /**
     * Add javascript to header
     *
     * @param str javascript path
     */
    public function addHeaderJavaScript($script) {
        array_push($this->header_scripts, $script);
    }

    /**
     * Add CSS to header
     *
     * @param str CSS path
     */
    public function addHeaderCSS($css) {
        array_push($this->header_css, $css);
    }

    /**
     * get CSS scripts
     *
     * @return array List of CSS files
     */
    public function getHeaderCSS() {
        return $this->header_css;
    }

    /**
     * Add data to view template engine for rendering
     *
     * @param str $key
     * @param mixed $value
     */
    protected function addToView($key, $value) {
        $this->view_mgr->assign($key, $value);
    }

    /**
     * Invoke the controller
     *
     * Always use this method, not control(), to invoke the controller.
     */
    public function go() {
        try {
            $this->initalizeApp();

            // are we in need of a database migration?
            $classname = get_class($this);
            $results = $this->control();
            if ($this->profiler_enabled && !isset($this->json_data)
            && strpos($this->content_type, 'text/javascript') === false
            && strpos($this->content_type, 'text/csv') === false) {
                $end_time = microtime(true);
                $total_time = $end_time - $this->start_time;
                $profiler = Profiler::getInstance();
                $this->disableCaching();
                $profiler->add($total_time,
                    "total page execution time, running ".$profiler->total_queries." queries.");
                $this->setViewTemplate('_isosceles.profiler.tpl');
                $this->addToView('profile_items',$profiler->getProfile());
                return  $results . $this->generateView();
            } else  {
                return $results;
            }
        } catch (Exception $e) {
            // Send HTTP 500 Internal Server Error header
            if (isset($_SERVER["SERVER_PROTOCOL"])) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            }

            //If Router has been instantiated and can handle 500 errors, use that (recommended)
            if (isset(Router::$routes['500'])) {
                $controller = new Router::$routes['500'](true);
                $controller->addErrorMessage($e->getMessage());
                return $controller->go();
            } else {
               //Explicitly set TZ (before we have user's choice) to avoid date() warning about using system settings
                Utils::setDefaultTimezonePHPini();
                $content_type = $this->content_type;
                if (strpos($content_type, ';') !== false) {
                    $exploded = explode(';', $content_type);
                    $content_type = array_shift($exploded);
                }
                switch ($content_type) {
                    case 'application/json':
                        $this->setViewTemplate('isosceles.500.json.tpl');
                        break;
                    case 'text/plain':
                        $this->setViewTemplate('isosceles.500.txt.tpl');
                        break;
                    default:
                        $this->setViewTemplate('isosceles.500.tpl');
                }
                $this->addToView('error_type', get_class($e));
                $this->addErrorMessage($e->getMessage());
                return $this->generateView();
            }
        }
    }

    /**
     * Initalize app
     * Load config file and required plugins
     * @throws Exception
     */
    private function initalizeApp() {
        $classname = get_class($this);
        //Initialize config
        $config = Config::getInstance();
        if ($config->getValue('timezone')) {
            date_default_timezone_set($config->getValue('timezone'));
        }
        if ($config->getValue('debug')) {
            ini_set("display_errors", 1);
            ini_set("error_reporting", E_ALL);
        }
    }

    /**
     * Provided for tests only, to assert that proper view values have been set. (Debug must be equal to true.)
     * @return ViewManager
     */
    public function getViewManager() {
        return $this->view_mgr;
    }

    /**
     * Turn off caching
     * Provided in case an individual controller wants to override the application-wide setting.
     */
    protected function disableCaching() {
        $this->view_mgr->disableCaching();
    }

    /**
     * Check if cache needs refreshing
     * @return bool
     */
    protected function shouldRefreshCache() {
        if ($this->view_mgr->isViewCached()) {
            return !$this->view_mgr->isCached($this->view_template, $this->getCacheKeyString());
        } else {
            return true;
        }
    }

    /**
     * Set web page title
     * This method only works for views that reference _header.tpl.
     * @param str $title
     */
    public function setPageTitle($title) {
        $this->addToView('controller_title', $title);
    }

    /**
     * Add error message to view.
     * Include field if the message goes on a specific place on the page; otherwise leave it null for the message
     * to be page-level.
     * @param str $msg
     * @param str $field Defaults to null for page-level messages.
     */
    public function addErrorMessage($msg, $field=null) {
        $this->disableCaching();
        $this->view_mgr->addErrorMessage($msg, $field);
    }

    /**
     * Add success message to view
     * Include field if the message goes on a specific place on the page; otherwise leave it null for the message
     * to be page-level.
     * @param str $msg
     * @param str $field Defaults to null for page-level messages.
     */
    public function addSuccessMessage($msg, $field=null) {
        $this->disableCaching();
        $this->view_mgr->addSuccessMessage($msg, $field);
    }

    /**
     * Add informational message to view
     * Include field if the message goes on a specific place on the page; otherwise leave it null for the message
     * to be page-level.
     * @param str $msg
     * @param str $field Defaults to null for page-level messages.
     */
    public function addInfoMessage($msg, $field=null) {
        $this->disableCaching();
        $this->view_mgr->addInfoMessage($msg, $field);
    }

    /**
     * Will enable a CSRF token in the view
     */
    public function enableCSRFToken() {
        $this->view_csrf_token = true;
    }

    /**
     * Get the view CSRF token enabled status
     */
    public function isEnableCSRFToken() {
        return $this->view_csrf_token;
    }

    /**
     * Validate the CSRF token passed in the request data.
     * @throws invalid InvalidCSRFTokenException
     * @return bool True if $_POST['csrf_token'] or $_GET['csrf_token'] is valid
     */
    public function validateCSRFToken() {
        $token = 'no token passed';
        if (isset($_POST['csrf_token'])) {
            $token = $_POST['csrf_token'];
        } else if (isset($_GET['csrf_token'])) {
            $token = $_GET['csrf_token'];
        }
        $session_token = Session::getCSRFToken();
        if ($session_token && $session_token == $token) {
            return true;
        } else {
            throw new InvalidCSRFTokenException($token);
        }
    }
    /**
     * Send Location header
     * @param str $destination
     * @return bool Whether or not redirect header was sent
     */
    protected function redirect($destination=null) {
        if (!isset($destination)) {
            $destination = Config::getInstance()->getValue('site_root_path  ');
        }
        $this->redirect_destination = $destination; //for validation
        if ( !headers_sent() ) {
            header('Location: '.$destination);
            return true;
        } else {
            return false;
        }
    }
}
