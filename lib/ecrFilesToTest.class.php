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
    $filesToTest = array_change_key_case($this->getAllFilesToTest());
    $testFiles   = array_change_key_case($this->getTestFiles());
    return array_diff_key($testFiles, $filesToTest);
  }

  /**
   *
   * @param array $files
   * @param array $globPatterns
   *
   * @return array
   */
  protected function filterExcludedFiles(array $files)
  {
    if (is_null($this->getOption('exclusions_globs')))
    {
      return $files;
    }
    foreach ($files as $class => $file)
    {
      $file = ecrUtils::getRelativePathFromSfRoot($file);
      foreach ($this->getOption('exclusions_globs') as $exlusionGlob)
      {
        sfGlobToRegex::setStrictWildcardSlash(false);
        $regexp = sfGlobToRegex::glob_to_regex($exlusionGlob);
        if (preg_match($regexp, $file))
        {
          unset($files[$class]);
        }
      }
      $file = realpath($file);
    }
    return $files;
  }

}
