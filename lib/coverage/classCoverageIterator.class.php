<?php
class classCoverageIterator extends ecrIterator
{

  /**
   * (non-PHPdoc)
   * @see plugins/agEmmaCoverageReportPlugin/lib/utils/ecrIterator#getClass()
   */
  protected function getClass()
  {
    return 'classCoverage';
  }

  public function getMethodCount()
  {
    $methodCount = 0;
    /* @var $classCoverage classCoverage */
    foreach ($this as $classCoverage)
    {
      $methodCount += $classCoverage->getMethodCount();
    }
    return $methodCount;
  }

  public function getTotalLines()
  {
    $totalLines = 0;
    /* @var $classCoverage classsCoverage */
    foreach ($this as $classCoverage)
    {
      $totalLines += $classCoverage->getTotalLines();
    }
    return $totalLines;
  }

  public function getCoveredLines()
  {
    $lines = 0;
    /* @var $classCoverage classsCoverage */
    foreach ($this as $classCoverage)
    {
      $lines += $classCoverage->getCoveredLines();
    }
    return $lines;
  }

  public function getCoveredClass()
  {
    $coveredClass = new classCoverageIterator();
    /* @var $classCoverage classsCoverage */
    foreach ($this as $classCoverage)
    {
      if ($classCoverage->isFullyCovered())
      {
        $coveredClass->add($classCoverage);
      }
    }
    return $coveredClass;
  }

  public function getCoveredClassCount()
  {
    return count($this->getCoveredClass());
  }

  public function getCoveredClassPourcent()
  {
    if (count($this) == 0)
    {
      return 0;
    }
    return $this->getCoveredClassCount() / count($this) * 100;
  }

  public function getCoveredMethodPourcent()
  {
    if ($this->getMethodCount() == 0)
    {
      return 0;
    }
    return $this->getCoveredMethodsCount() / $this->getMethodCount() * 100;
  }

  public function getCoveredLinesPourcent()
  {
    if ($this->getTotalLines() == 0)
    {
      return 0;
    }
    return $this->getCoveredLines() / $this->getTotalLines() * 100;
  }

  public function getCoveredMethods()
  {
    $coveredMethods = new methodCoverageIterator();
    /* @var $classCoverage classsCoverage */
    foreach ($this as $classCoverage)
    {
      //var_dump($classCoverage->getCoveredMethods());
      $coveredMethods->add($classCoverage->getCoveredMethods());
    }
    return $coveredMethods;
  }

  public function getCoveredMethodsCount()
  {
    return count($this->getCoveredMethods());
  }

  public function getUnCoveredClass()
  {
    $unCoveredClass = new classCoverageIterator();
    /* @var $classCoverage classsCoverage */
    foreach ($this as $classCoverage)
    {
      if (!$classCoverage->isFullyCovered())
      {
        $unCoveredClass->add($classCoverage);
      }
    }
    return $unCoveredClass;
  }

}