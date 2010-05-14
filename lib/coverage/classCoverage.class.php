<?php
class classCoverage
{
  /**
   *
   * @var methodCoverageIterator
   */
  protected $methodCoverageIterator;

  /**
   *
   * @var string
   */
  protected $name = null;

  /**
   *
   * @return void
   */
  public function __construct($name)
  {
    $this->name                   = $name;
    $this->methodCoverageIterator = new methodCoverageIterator();
  }

  /**
   *
   * @return methodCoverageIterator
   */
  public function getMethodCoverageIterator()
  {
    return $this->methodCoverageIterator;
  }

  /**
   *
   * @param methodCoverageIterator $methodsCoverage
   *
   * @return void
   */
  public function setMethodCoverageIterator(methodCoverageIterator $methodCoverageIterator)
  {
    $this->methodCoverageIterator = $methodCoverageIterator;
  }

  public function getTotalLines()
  {
    return $this->getMethodCoverageIterator()->getTotalLines();
  }

  public function getName()
  {
    return $this->name;
  }

  public function getCoveredLinesPourcent()
  {
    if ($this->getTotalLines() == 0)
    {
      return 0;
    }
    return $this->getCoveredLines() / $this->getTotalLines() * 100;
  }

  public function getCoveredLines()
  {
    return $this->getMethodCoverageIterator()->getCoveredLines();
  }

  public function getCoveredMethods()
  {
    return $this->getMethodCoverageIterator()->getCoveredMethods();
  }

  public function getUnCoveredMethods()
  {
    return $this->getMethodCoverageIterator()->getUnCoveredMethods();
  }

  public function isFullyCovered()
  {
    return (count($this->getUnCoveredMethods()) == 0);
  }

  public function toString()
  {
    return sprintf('%s %d%% %d/%d', $this->getName(), $this->getCoveredLinesPourcent(), $this->getCoveredLines(), $this->getTotalLines());
  }

  public function getMethodCount()
  {
    return count($this->getMethodCoverageIterator());
  }

  public function getCoveredMethodCount()
  {
    return $this->getMethodCoverageIterator()->getCoveredMethodsCount();
  }

  public function getCoveredMethodPourcent()
  {
    return $this->getMethodCoverageIterator()->getCoveredMethodsPourcent();
  }

}