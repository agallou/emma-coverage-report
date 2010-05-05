<?php
class ecrGenericFilesToTest extends ecrFilesToTest
{

  public function getAllFilesToTest()
  {
    $classes = array();
    foreach ($this->getApplications() as $app)
    {
      $configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', true);
      sfContext::createInstance($configuration);
      $autoload = ecrAutoload::getInstance();
      $autoload->reloadClasses();
      $classes += $autoload->getAllFiles();
    }
    return array_unique($classes);
  }

  protected function getApplications()
  {
    return sfFinder::type('dir')->maxdepth(0)->relative()->in(sfConfig::get('sf_apps_dir'));
  }

  public function getTestFileFromTestedFile($testedFile)
  {
    $basename = pathinfo($testedFile, PATHINFO_BASENAME);
    $class    = str_replace(array('.class.php'), array(''), $basename);

    $testFiles = $this->getTestFiles();

    if (!array_key_exists($class, $testFiles))
    {
      if (!array_key_exists(strtolower($class), $testFiles))
      {
        throw new ecrTestFileNotFoundException($class);
      }
      return $testFiles[strtolower($class)];
    }
    return $testFiles[$class];
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
