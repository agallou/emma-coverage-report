<?php
class ecrGenericFilesToTest extends ecrFilesToTest
{

  public function getAllFilesToTest()
  {
    $autoload = ecrSimpleAutoload::getInstance(sfConfig::get('sf_cache_dir').'/project_autoload.cache');
    $autoload->reload();
    return $autoload->getAllFiles();
  }

  public function getTestFileFromTestedFile($testedFile)
  {
    $basename = pathinfo($testedFile, PATHINFO_BASENAME);
    $class    = str_replace(array('.class.php'), array(''), $basename);
//infosProviderSerieAllocine
    $testFiles = $this->getTestFiles();

    if (!array_key_exists($class, $testFiles))
    {
      throw new ecrTestFileNotFoundException($class);
    }
    return $testFiles[$class];

    //$testedFile = $autoload->getClassPath($class);
    //$testedFile = str_replace(sfConfig::get('sf_root_dir'), '.', $testedFile);
    //return $testedFile;
  }

  public function getTestFiles()
  {
    $ecrReport = new ecrReport();
    $files     = sfFinder::type('file')->name('*.php')->in($ecrReport->getUnitTestsDir());
    $testFiles = array();
    foreach ($files as $file)
    {
      $basename = pathinfo($file, PATHINFO_BASENAME);
      $class    = str_replace(array('Test.php'), array(''), $basename);
      $testFiles[$class] = $file;
    }
    return $testFiles;
  }

}