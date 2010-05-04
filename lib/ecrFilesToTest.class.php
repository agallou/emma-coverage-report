<?php
abstract class ecrFilesToTest
{
  /**
   *
   * @return array
   */
  abstract public function getAllFilesToTest();

  /**
   *
   * @return string
   */
  abstract public function getTestFileFromTestedFile($testFile);

  /**
   *
   * @var array
   */
  protected $options = array();

  /**
   *
   * @param array $options
   *
   * @return void
   */
  public function __construct(array $options = array())
  {
    $this->setOptions($options);
  }

  /**
   *
   * @param array $options
   *
   * @return void
   */
  protected function setOptions(array $options)
  {
    $this->options = $options;
  }

  /**
   *
   * @return array
   */
  protected function getOptions()
  {
    return $this->options;
  }

  /**
   *
   * @param string $value
   *
   * @return mixed
   */
  protected function getOption($value)
  {
    $options = $this->getOptions();
    if (!isset($options[$value]))
    {
      return null;
    }
    return $options[$value];
  }

  /**
   *
   * @return array
   */
  public function getClassesNotFound()
  {
    $filesToTest = $this->getAllFilesToTest();
    $testFiles   = $this->getTestFiles();
    return array_diff_key($testFiles, $filesToTest);
  }

}