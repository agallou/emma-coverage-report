<?php
include(dirname(__FILE__).'/../../bootstrap/unit.php');

include(dirname(__FILE__).'/../../../lib/utils/ecrPhpdocIgnore.class.php');


$t = new lime_test(5, new lime_output_color());

$file = realpath(dirname(__FILE__) . '/../../data/ecrPhpdocignore/ignored1.php');
$parser = new ecrPhpdocIgnore($file);
$t->is($parser->getIgnoredLines(), array(8, 9, 10, 11, 12, 13, 14), 'ignoredLines with one simple method');


$file = realpath(dirname(__FILE__) . '/../../data/ecrPhpdocignore/ignored3.php');
$parser = new ecrPhpdocIgnore($file);
$t->is($parser->getIgnoredLines(), array(8, 9, 10, 11, 12, 13, 14), 'ignoredLines with one simple method and anther method');


$file = realpath(dirname(__FILE__) . '/../../data/ecrPhpdocignore/ignored2.php');
$parser = new ecrPhpdocIgnore($file);
$t->is($parser->getIgnoredLines(), array(), 'ignoredLines with no comment');


$file = realpath(dirname(__FILE__) . '/../../data/ecrPhpdocignore/ignored4.php');
$parser = new ecrPhpdocIgnore($file);
$t->is($parser->getIgnoredLines(), array(8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22), 'ignoredLines with one "complex" method');


$file = realpath(dirname(__FILE__) . '/../../data/ecrPhpdocignore/ignored5.php');
$parser = new ecrPhpdocIgnore($file);
$t->is($parser->getIgnoredLines(), array(8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 26, 27, 28, 29, 30), 'two ignoed funtions');

