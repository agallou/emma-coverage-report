<?php
abstract class ecrRenderer
{

  /**
   *
   * @var array
   */
  protected $coverageByFile;

  /**
   *
   * @param array $coverageByFile
   *
   * @return void
   */
  public function __construct(array $coverageByFile)
  {
    $this->coverageByFile = $coverageByFile;
  }

  /**
   *
   * @return string
   */
  abstract public function render();

  /**
   *
   * @param string $file
   *
   * @return int
   */
  protected function getNumberOfLinesFile($file)
  {
    if (!file_exists($file))
    {
      return 1;
    }
    return count(file($file));
  }

}