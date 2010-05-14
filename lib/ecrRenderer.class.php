<?php
abstract class ecrRenderer
{

  /**
   *
   * @var FileCoverageIterator
   */
  protected $fileCoverageiterator;

  /**
   *
   * @param array $coverageByFile
   *
   * @return void
   */
  public function __construct(FileCoverageIterator $fileCoverageiterator)
  {
    $this->fileCoverageiterator = $fileCoverageiterator;
  }

  /**
   *
   * @return string
   */
  abstract public function render();

  /**
   *
   * @return FileCoverageIterator
   */
  protected function getFileCoverageIterator()
  {
    return $this->fileCoverageiterator;
  }

}