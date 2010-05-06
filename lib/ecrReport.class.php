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
   * @var array
   */
  protected $filesToTestOptions = array();

  /**
   *
   * @param array $options
   *
   * @return void
   */
  public function setOptionsForFilesToTest(array $options)
  {
    $this->filesToTestOptions = $options;
  }

  /**
   *
   * @return array
   */
  public function getOptionsForFilesToTest()
  {
    return $this->filesToTestOptions;
  }

  /**
   *
   * @return array
   */
  public function getCoveragePercentByFile()
  {
    $coverage  = array();
    $options   = '';
    if (!is_null($this->getXDebugPath()))
    {
      $options = sprintf(' --xdebug-extension-path="%s" ', $this->getXDebugPath());
    }
    $filesToTest = new ecrGenericFilesToTest($this->getOptionsForFilesToTest());
    $files       = $filesToTest->getAllFilesToTest();
    foreach ($files as $testedFile)
    {
      try
      {
        $testFile     = $filesToTest->getTestFileFromTestedFile($testedFile);
        $testFile     = substr($testFile, strlen(sfConfig::get('sf_test_dir')) + 1);
        $testedFile   = substr($testedFile, strlen(sfConfig::get('sf_root_dir')) + 1);
        $cmd        = sprintf('%s symfony ecr:coverage %s test/%s %s', sfToolkit::getPhpCli(), $options, $testFile, $testedFile);
        $output     = '';
        exec($cmd, $output);
        $matches = array();
        preg_match('/TOTAL COVERAGE: (.*)%/', $output[2], $matches);
        $percent = $matches[1];
        $coverage[$testedFile] = $percent;
      }
      catch (ecrTestFileNotFoundException $ex)
      {
        $testedFile   = substr($testedFile, strlen(sfConfig::get('sf_root_dir')) + 1);
        $coverage[$testedFile] = 0;
      }
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
   * @return string
   */
  public function getUnitTestsDir()
  {
    return sfConfig::get('sf_test_dir') . DIRECTORY_SEPARATOR . 'unit';
  }

}