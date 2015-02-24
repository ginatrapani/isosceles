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
 * @license http://www.gnu.org/licenses/gpl.html
 */

class DAOFactory {

    /**
     * Maps DAO from db_type and defines interface names and class implementation
     */
    static $dao_mapping = array (
    //Test DAO
        'TestDAO' => array(
    //MySQL Version
            'mysql' => 'TestMySQLDAO',
    //faux Version
            'faux' => 'TestFauxDAO' ),
    );

    /*
     * Creates a DAO instance and returns it
     *
     * @param string $dao_key the name of the dao you wish to init
     * @param array $cfg_vals Optionally override config.inc.php vals; needs 'table_prefix', 'db_type',
     * 'db_socket', 'db_name', 'db_host', 'db_user', 'db_password'
     * @returns PDODAO A concrete dao instance
     */
    public static function getDAO($dao_key, $cfg_vals=null) {
        $db_type = self::getDBType($cfg_vals);
        if (! isset(self::$dao_mapping[$dao_key]) ) {
            throw new Exception("No DAO mapping defined for: " . $dao_key);
        }
        if (! isset(self::$dao_mapping[$dao_key][$db_type])) {
            throw new Exception("No db mapping defined for '" . $dao_key . "' with db type: " . $db_type);
        }
        $class_name = self::$dao_mapping[$dao_key][$db_type];
        $dao = new $class_name($cfg_vals);
        return $dao;
    }

    /**
     * Gets the db_type for our application, defaults to MySQL,
     * db_type can optionally be defined in webapp/config.inc.php as:
     *
     *<code>
     *     $ISOSCELES_CFG['db_type'] = 'somedb';
     *</code>
     *
     * @param array $cfg_vals Optionally override config.inc.php vals; needs 'table_prefix', 'db_type',
     * 'db_socket', 'db_name', 'db_host', 'db_user', 'db_password'
     * @return string db_type, will default to 'mysql' if not defined
     */
    public static function getDBType($cfg_vals=null) {
        $type = Config::getInstance($cfg_vals)->getValue('db_type');
        $type = is_null($type) ? 'mysql' : $type;
        return $type;
    }
}
