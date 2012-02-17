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
 * You should have received a copy of the GNU General Public License along with ThinkUp.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * IsoscelesBasicUnitTestCase
 *
 * The parent class of all unit tests, without database interaction.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 */

class FileDataManager {
    /**
     * Get the path to a file based on $THINKUP_CFG['datadir_path'].
     * Default to webapp root folder /data/ directory if
     * config file doesn't exist or it does exist and the datadir_path value is not set.
     * @param str $file File or directory to get the path of
     * @return str Absolute path to file
     */
    public static function getDataPath($file=null) {
        try {
            $path = Config::getInstance()->getValue('datadir_path');
        } catch (ConfigurationException $e) {
            $path = WEBAPP_PATH.'data/';
        }
        if ($path=='') { //config file exists but datadir_path is not set
            $path = WEBAPP_PATH.'data/';
        }
        $path = preg_replace('/\/*$/', '', $path);
        if ($file) {
            $path = $path . '/' . $file;
        } else {
            $path = $path.'/';
        }
        return $path;
    }

    /**
     * Get the path to a file based on $THINKUP_CFG['datadir_path']
     * Default to webapp root folder /data/ directory if
     * config file doesn't exist or it does exist and the datadir_path value is not set.
     * @param str $file File or directory to get the path of
     * @return str Absolute path to file
     */
    public static function getBackupPath($str = null) {
        $path = 'backup/';
        $path = self::getDataPath($path);
        if (!file_exists($path)) {
            mkdir($path);
            @chmod($path, 0777);
        }
        if ($str) {
            $path = $path . $str;
        }
        return $path;
    }
}