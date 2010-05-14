<?php
class ecrGenericFilesToTest extends ecrFilesToTest
{

  /**
   * (non-PHPdoc)
   * @see plugins/agEmmaCoverageReportPlugin/lib/ecrFilesToTest#getAllFilesToTest()
   */
  public function getAllFilesToTest()
  {
    $classes = array();
    foreach ($this->getApplications() as $app)
    {
      $configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', true);
      sfContext::createInstance($configuration);
      $autoload = ecrAutoload::getInstance();
      $autoload->forceRefreshCache();
      $autoload->reloadClasses(true);
      $classes += $autoload->getAllFiles();
    }
    return $this->filterExcludedFiles(array_unique($classes));
  }

  /**
   *
   * @return array
   */
  protected function getApplications()
  {
    return sfFinder::type('dir')->maxdepth(0)->relative()->in(sfConfig::get('sf_apps_dir'));
  }

  /**
   * (non-PHPdoc)
   * @see plugins/agEmmaCoverageReportPlugin/lib/ecrFilesToTest#getTestFileFromTestedFile($testFile)
   */
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

  /**
   *
   * @return array
   */
  public function getTestFiles()
  {
    $files     = sfFinder::type('file')->name('*.php')->in($this->getUnitTestsDir());
    $testFiles = array();
    foreach ($files as $file)
    {
      $basename = pathinfo($file, PATHINFO_BASENAME);
      $class    = str_replace(array('Test.php'), array(''), $basename);
      $testFiles[$class] = $file;
    }
    return $testFiles;
  }

  protected function getUnitTestsDir()
  {
    return sfConfig::get('sf_test_dir') . DIRECTORY_SEPARATOR . 'unit';
  }

}
