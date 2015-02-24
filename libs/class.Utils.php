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
 * You should have received a copy of the GNU General Public License along with Isosceles.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

class Utils {
    /**
     * Indents a flat JSON string to make it more human-readable.
     *
     * @author http://recursive-design.com/blog/2008/03/11/format-json-with-php/
     * @param string $json The original JSON string to process.
     * @return string Indented version of the original JSON string.
     */
    public static function indentJSON($json) {
        $result = '';
        $pos = 0;
        $str_len = strlen($json);
        $indent_str = '    ';
        $new_line = "\n";
        $prev_char = '';
        $prev_prev_char = '';
        $out_of_quotes = true;

        for ($i = 0; $i <= $str_len; $i++) {

            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ($char == '"') {
                if ( $prev_char != "\\") {
                    $out_of_quotes = !$out_of_quotes;
                } elseif ($prev_prev_char == "\\") {
                    $out_of_quotes = !$out_of_quotes;
                }
                // If this character is the end of an element,
                // output a new line and indent the next line.
            } else if (($char == '}' || $char == ']') && $out_of_quotes) {
                $result .= $new_line;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indent_str;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $out_of_quotes) {
                $result .= $new_line;
                if ($char == '{' || $char == '[') {
                    $pos++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indent_str;
                }
            }

            $prev_prev_char = $prev_char;
            $prev_char = $char;
        }

        return $result;
    }

    /**
     * Becuse PHP doesn't have a data type large enough to hold some of the
     * numbers that Twitter deals with, this function strips the double
     * quotes off every string that contains only numbers inside the double
     * quotes.
     *
     * @param string $encoded_json JSON formatted string.
     * @return string Encoded JSON with numeric strings converted to numbers.
     */
    public static function convertNumericStrings($encoded_json) {
        return preg_replace('/\"((?:-)?[0-9]+(\.[0-9]+)?)\"/', '$1', $encoded_json);
    }

    /**
     * If date.timezone is not set in php.ini, default to America/Los_Angeles to avoid date() warning about
     * using system settings.
     * This method exists to avoid the warning which Smarty triggers in views that don't have access to a
     * THINKUP_CFG timezone setting yet, like during installation, or when a config file doesn't exist.
     */
    public static function setDefaultTimezonePHPini() {
        if (ini_get('date.timezone') == false) {
            // supress the date_default_timezone_get() warn as php 5.3.* doesn't like when date.timezone is not set in
            // php.ini, but many systems comment it out by default, or have no php.ini by default
            $error_reporting = error_reporting(); // save old reporting setting
            error_reporting( E_ERROR | E_USER_ERROR ); // turn off warning messages
            $tz = date_default_timezone_get(); // get tz if we can
            error_reporting( $error_reporting ); // reset error reporting
            if(! $tz) { // if no $tz defined, use UTC
                $tz = 'UTC';
            }
            ini_set('date.timezone',$tz);
        }
    }

    /**
     * Generate var dump to string.
     * @return str
     */
    public static function varDumpToString($mixed = null) {
        ob_start();
        var_dump($mixed);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * Get the application's host name or server name, i.e., example.com.
     * @return str Host name either set by PHP global vars or stored in the database
     */
    public static function getApplicationHostName() {
        //First attempt to get the host name without querying the database
        //Try SERVER_NAME
        $server = empty($_SERVER['SERVER_NAME']) ? '' : $_SERVER['SERVER_NAME'];
        //Second, try HTTP_HOST
        if ($server == '' ) {
            $server = empty($_SERVER['HTTP_HOST']) ? '' : $_SERVER['HTTP_HOST'];
        }
        //Finally fall back to defined application setting in config
        if ($server == '') {
            $config = Config::getInstance();
            $server = $config->getValue('application_host');
        }
        //domain name is always lowercase
        $server = strtolower($server);
        return $server;
    }

    /**
     * Get application URL
     * @param bool $replace_localhost_with_ip
     * @param bool $use_filesystem_path Use filesystem path instead of path specified in config.inc.php
     * @return str application URL
     */
    public static function getApplicationURL($replace_localhost_with_ip = false, $use_filesystem_path = true,
        $should_url_encode = true) {
        $server = self::getApplicationHostName();
        if ($replace_localhost_with_ip) {
            $server = ($server == 'localhost')?'127.0.0.1':$server;
        }
        if ($use_filesystem_path) {
            $site_root_path = Utils::getSiteRootPathFromFileSystem();
        } else {
            $cfg = Config::getInstance();
            $site_root_path = $cfg->getValue('site_root_path');
        }
        if ($should_url_encode) {
            //URLencode everything except spaces in site_root_path
            $site_root_path = str_replace('%2f', '/', strtolower(urlencode($site_root_path)));
        }
        if  (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80') { //non-standard port
            if (isset($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] == '443') { //account for standard https port
                $port = '';
            } else {
                $port = ':'.$_SERVER['SERVER_PORT'];
            }
        } else {
            $port = '';
        }
        return 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$server.$port.$site_root_path;
    }

    /**
     * Get site root path from filesystem.
     * @return str
     */
    public static function getSiteRootPathFromFileSystem() {
        if (isset($_SERVER['PHP_SELF'])) {
            $current_script_path = explode('/', $_SERVER['PHP_SELF']);
        } else {
            $current_script_path = array();
        }
        array_pop($current_script_path);
        $current_script_path = implode('/', $current_script_path) . '/';
        echo $current_script_path;
        return $current_script_path;
    }
}