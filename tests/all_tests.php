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

include dirname(__FILE__) . '/init.tests.php';
require_once ISOSCELES_PATH.'extlibs/simpletest/mock_objects.php';

if (isset($argv[1]) && ($argv[1] == '--usage' || $argv[1] == '-h' || $argv[1] == '-help')) {
    echo "Isosceles test suite runner
Usage: [environment vars...] php tests/all_tests.php [args...]

Environment vars:
    TEST_DEBUG=1            Output debugging message during development

Arguments:
    -help, -h               Show this help message


";
    return;
}

$test_suite = new TestSuite('Isosceles tests');
$test_suite->add(new TestOfRouter());
$test_suite->add(new TestOfConfig());
$test_suite->add(new TestOfDAOFactory());
$test_suite->add(new TestOfFileDataManager());
$test_suite->add(new TestOfLoader());
$test_suite->add(new TestOfPDODAO());
$test_suite->add(new TestOfProfiler());
$test_suite->add(new TestOfIsoscelesExampleController());
$test_suite->add(new TestOfUtils());
$test_suite->add(new TestOfViewManager());
//@TODO Figure out why we can't include TestOfFixtureBuilder here
//$test_suite->add(new TestOfFixtureBuilder());

//$tr = new TextReporter();
//$test_suite->run( $tr );

