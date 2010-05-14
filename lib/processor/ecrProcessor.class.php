<?php
class ecrProcessor
{

  /**
   *
   * @var ecrFilesToTest
   */
  protected $filesToTest;

  /**
   *
   * @param ecrFilesToTest $filesToTest
   *
   * @return void
   */
  public function __construct(ecrFilesToTest $filesToTest)
  {
    $this->filesToTest = $filesToTest;
  }

  /**
   *
   * @return FileCoverageIterator
   */
  public function process()
  {
    $iterator = new fileCoverageIterator();
    $files = $this->filesToTest->getAllFilesToTest();
    foreach ($files as $class => $file)
    {
      $maker = new ecrFileCoverageMaker($file);
      $maker->setUntestedLines($this->getUncoveredLines($file));
      $value = $maker->getFileCoverage();
      $iterator->add($value);
    }
    return $iterator;
  }

  protected function getUncoveredLines($file)
  {
    $options = '';
    if (!is_null($this->getXDebugPath()))
    {
      $options = sprintf(' --xdebug-extension-path="%s" ', $this->getXDebugPath());
    }
    try
    {
      $testFile = $this->filesToTest->getTestFileFromTestedFile($file);
      $testFile = substr($testFile, strlen(sfConfig::get('sf_test_dir')) + 1);
      $file     = substr($file, strlen(sfConfig::get('sf_root_dir')) + 1);
      $cmd      = sprintf('%s symfony ecr:coverage %s test/%s %s --detailed', sfToolkit::getPhpCli(), $options, $testFile, $file);
      $output   = array();
      exec($cmd, $output);
      $parser = new ecrCoverageOutputParser($output);
      $uncoveredLines = $parser->getMissing();
    }
    catch (ecrTestFileNotFoundException $ex)
    {
      $uncoveredLines = null;
    }
    return $uncoveredLines;
  }

  protected function getXDebugPath()
  {
    return $this->xDebugPath;
  }

  public function setXDebugPath($xDebugPath)
  {
     $this->xDebugPath = $xDebugPath;
  }

}