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
class SessionCache {
    /**
     * Put a value in $_SESSION key.
     * @param str $key
     * @param str $value
     */
    public static function put($key, $value) {
        $config = Config::getInstance();
        $_SESSION[$config->getValue('source_root_path')][$key] = $value;
    }
    /**
     * Get a value from $_SESSION.
     * @param str $key
     * @return mixed Value
     */
    public static function get($key) {
        $config = Config::getInstance();
        if (self::isKeySet($key)) {
            return $_SESSION[$config->getValue('source_root_path')][$key];
        } else {
            return null;
        }
    }
    /**
     * Check if a key in $_SESSION has a value set.
     * @param str $key
     * @return bool
     */
    public static function isKeySet($key) {
        $config = Config::getInstance();
        return isset($_SESSION[$config->getValue('source_root_path')][$key]);
    }
    /**
     * Unset key's value in $_SESSION
     * @param str $key
     */
    public static function unsetKey($key) {
        $config = Config::getInstance();
        unset($_SESSION[$config->getValue('source_root_path')][$key]);
    }
    /**
     * Unset all keys' values in $_SESSION
     * @return void
     */
    public static function clearAllKeys() {
        $config = Config::getInstance();
        foreach ($_SESSION[$config->getValue('source_root_path')] as $key=>$value) {
            SessionCache::unsetKey($key);
        }
    }
}