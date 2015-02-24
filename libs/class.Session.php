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
class Session {
    /**
     * @return bool Is user logged in
     */
    public static function isLoggedIn() {
        if (!SessionCache::isKeySet('user')) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * @return str Currently logged-in username (email address)
     */
    public static function getLoggedInUser() {
        if (self::isLoggedIn()) {
            return SessionCache::get('user');
        } else {
            return null;
        }
    }
    /**
     * Complete login action.
     * @param str $email
     */
    public static function completeLogin($email) {
        SessionCache::put('user', $email);
        // set a CSRF token
        SessionCache::put('csrf_token', uniqid(mt_rand(), true));
        if (isset($_SESSION["MODE"]) && $_SESSION["MODE"] == 'TESTS') {
            SessionCache::put('csrf_token', 'TEST_CSRF_TOKEN');
        }
    }
    /**
     * Log out
     */
    public static function logout() {
        SessionCache::unsetKey('user');
    }
    /**
     * Returns a CSRF token that should be used whith _GETs and _POSTs requests.
     * @return str CSRF token
     */
    public static function getCSRFToken() {
        if (self::isLoggedIn()) {
            return SessionCache::get('csrf_token');
        } else {
            return null;
        }
    }
}