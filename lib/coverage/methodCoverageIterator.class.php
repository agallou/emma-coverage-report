<?php
class methodCoverageIterator extends ecrIterator
{

  protected function getClass()
  {
    return 'methodCoverage';
  }

  public function getTotalLines()
  {
    $totalLines = 0;
    /* @var $methodCoverage methodCoverage */
    foreach ($this as $classCoverage)
    {
      $totalLines += $classCoverage->getTotalLines();
    }
    return $totalLines;
  }

  public function getCoveredLines()
  {
    $coveredLines = 0;
    /* @var $method methodCoverage */
    foreach ($this as $method)
    {
      $coveredLines += $method->getCoveredLines();
    }
    return $coveredLines;
  }

  /**
   *
   * @return methodCoverageIterator
   */
  public function getCoveredMethods()
  {
    $coveredMethods = array();
    /* @var $method methodCoverage */
    foreach ($this as $method)
    {
      if ($method->isFullyCovered())
      {
        $coveredMethods[] = $method;
      }
    }
    return new methodCoverageIterator($coveredMethods);
  }

  public function getCoveredMethodsCount()
  {
    return count($this->getCoveredMethods());
  }

  public function getCoveredMethodsPourcent()
  {
    if (count($this) == 0)
    {
      return 0;
    }
    return $this->getCoveredMethodsCount() / count($this) * 100;
  }

  /**
   *
   * @return methodCoverageIterator
   */
  public function getUnCoveredMethods()
  {
    $unCoveredMethods = array();
    /* @var $method methodCoverage */
    foreach ($this as $method)
    {
      if (!$method->isFullyCovered())
      {
        $unCoveredMethods[] = $method;
      }
    }
    return new methodCoverageIterator($unCoveredMethods);
  }
}