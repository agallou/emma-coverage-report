<?php
include(dirname(__FILE__).'/../bootstrap/unit.php');

include(dirname(__FILE__).'/../../lib/ecrReport.class.php');

$t = new lime_test(null, new lime_output_color());

class mockEcrReport extends ecrReport
{
  public function getTestedFileFromTestFile($testFile)
  {
    return parent::getTestedFileFromTestFile($testFile);
  }
}

$task = new mockEcrReport();

$t->diag('getTestedFileFromTestFile()');
$t->is($task->getTestedFileFromTestFile(sfConfig::get('sf_root_dir') . 'test/unit/myClassNameTest.php'), 'myClassName.class.php');
