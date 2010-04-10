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
      $percent = substr($output[1], -3);
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
    $basename   = pathinfo($testFile, PATHINFO_BASENAME);
    //$testedFile = str_replace(array('Test', '.php'), array('', '.class.php'), $basename);
    $testedFile = str_replace(array('Test', '.php'), array('', ''), $basename);
    //$autoload = sfSimpleAutoload::getInstance();
    //$autoload = sfAutoload::getInstance();
    $autoload = sfSimpleAutoload::getInstance();
    //$autoload->reloadClasses();
    //$autoload->register();
    if (isset($autoload->classes[$testedFile]))
    {
      $class = $autoload->classes[$testedFile];
    }
    //var_dump($autoload->classes);
    //die();
    //$autoload->reloadClasses(true);
    //var_dump($testedFile);
    //$class = $autoload->getClassPath($testedFile);
    if (!isset($class))
    {
      $class = 'fichierNonTrouve';
      //throw new sfException('fichier testé non trouvé');
    }
    $class = str_replace(sfConfig::get('sf_root_dir'), '.', $class);
    return $class;

    //return $testedPath;
  }

  /**
   *
   * @param string $path
   *
   * @return string
   */
  /*
  protected function getRelativePathFromSfRootDir($path)
  {
    return substr($path, strlen($this->getUnitTestsDir()));
  }
  */

  /**
   *
   * @return string
   */
  protected function getUnitTestsDir()
  {
    return sfConfig::get('sf_test_dir') . DIRECTORY_SEPARATOR . 'unit';
  }

}