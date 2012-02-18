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

include dirname(__FILE__) . '/init.tests.php';
require_once ISOSCELES_PATH.'libs/extlib/simpletest/autorun.php';
require_once ISOSCELES_PATH.'libs/extlib/simpletest/web_tester.php';
require_once ISOSCELES_PATH.'libs/extlib/simpletest/mock_objects.php';

if (isset($argv[1]) && ($argv[1] == '--usage' || $argv[1] == '-h' || $argv[1] == '-help')) {
    echo "Isosceles test suite runner
Usage: [environment vars...] php tests/all_tests.php [args...]

Environment vars:
    TEST_DEBUG=1            Output debugging message during development
    SKIP_UPGRADE_TESTS=1    Skip upgrade tests, ie, do a short run
    TEST_TIMING=1           Output test run timing information
    RD_MODE=1               Use database stored on RAM disk (for speed improvements)

Arguments:
    -help, -h               Show this help message


";
    return;
}

$RUNNING_ALL_TESTS = true;
$TOTAL_PASSES = 0;
$TOTAL_FAILURES = 0;
$start_time = microtime(true);

$test_suite = new TestSuite('Isosceles tests');
$test_suite->add(new TestOfFileDataManager());
$test_suite->add(new TestOfProfiler());
$test_suite->add(new TestOfTestController());
$test_suite->add(new TestOfViewManager());
$test_suite->add(new TestOfUtils());
$test_suite->add(new TestOfLoader());
$test_suite->add(new TestOfConfig());

$tr = new TextReporter();
list($usec, $sec) = explode(" ", microtime());
$start =  ((float)$usec + (float)$sec);
$tests->run( $tr );

//if (getenv("TEST_TIMING")=="1") {
//    list($usec, $sec) = explode(" ", microtime());
//    $finish =  ((float)$usec + (float)$sec);
//    $runtime = round($finish - $start);
//    printf("Tests completed run in $runtime seconds\n");
//}
//if (isset($RUNNING_ALL_TESTS) && $RUNNING_ALL_TESTS) {
//    $TOTAL_PASSES = $TOTAL_PASSES + $tr->getPassCount();
//    $TOTAL_FAILURES = $TOTAL_FAILURES + $tr->getFailCount();
//}
//
//$end_time = microtime(true);
//$total_time = ($end_time - $start_time) / 60;
//
//echo "
//Total passes: ".$TOTAL_PASSES."
//Total failures: ".$TOTAL_FAILURES."
//Time elapsed: ".round($total_time)." minute(s)
//
//";

//echo trim(exec("cd ".ISOSCELES_PATH."docs/source/; wc -w `find ./ -type f -name \*.rst` | tail -n 1")) .
//" words of application documentation
//
//";
