<?php
class ecrReport
{

  /**
   *
   * @var string
   */
  protected $xDebugPath = null;

  /**
   *
   * @return array
   */
  public function getCoveragePercentByFile()
  {
    $coverage  = array();
    $ecrReport = new ecrReport();
    $files = sfFinder::type('file')->name('*.php')->in($ecrReport->getUnitTestsDir());
    $options = '';
    if (!is_null($this->getXDebugPath()))
    {
      $options = sprintf(' --xdebug-extension-path="%s" ', $this->getXDebugPath());
    }
    foreach ($files as $file)
    {
      $testFile   = substr($file, strlen(sfConfig::get('sf_test_dir')) + 1);
      $testedFile = $ecrReport->getTestedFileFromTestFile($file);
      $cmd        = sprintf('%s symfony ecr:coverage %s test/%s %s', sfToolkit::getPhpCli(), $options, $testFile, $testedFile);
      $output     = '';
      exec($cmd, $output);
      $matches = array();
      preg_match('/TOTAL COVERAGE: (.*)%/', $output[2], $matches);
      $percent = $matches[1];
      $coverage[$testedFile] = $percent;
    }
    return $coverage;
  }

  protected function getXDebugPath()
  {
    return $this->xDebugPath;
  }

  public function setXDebugPath($xDebugPath)
  {
     $this->xDebugPath = $xDebugPath;
  }

  /**
   *
   * @param string $testFile
   *
   * @return string
   */
  protected function getTestedFileFromTestFile($testFile)
  {
    $basename = pathinfo($testFile, PATHINFO_BASENAME);
    $autoload = ecrSimpleAutoload::getInstance(sfConfig::get('sf_cache_dir').'/project_autoload.cache');
    $autoload->reload();
    $class    = str_replace(array('Test', '.php'), array('', ''), $basename);
    if (!is_null($autoload->getClassPath($class)))
    {
      $testedFile = $autoload->getClassPath($class);
    }
    else
    {
      $testedFile = 'fichierNonTrouve';
    }
    $testedFile = str_replace(sfConfig::get('sf_root_dir'), '.', $testedFile);
    return $testedFile;
  }

  /**
   *
   * @return string
   */
  protected function getUnitTestsDir()
  {
    return sfConfig::get('sf_test_dir') . DIRECTORY_SEPARATOR . 'unit';
  }

}