<?php
class methodCoverage
{

  /**
   *
   * @var int
   */
  protected $totalLines = 0;

  /**
   *
   * @var int
   */
  protected $coveredLines = 0;

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
    $this->name = $name;
  }

  public function getTotalLines()
  {
    return $this->totalLines;
  }

  public function setTotalLines($totalLines)
  {
    $this->totalLines = $totalLines;
  }

  public function getCoveredLines()
  {
    return $this->coveredLines;
  }

  public function setCoveredLines($coveredLines)
  {
    $this->coveredLines = $coveredLines;
  }

  public function getName()
  {
    return $this->name;
  }

  public function isFullyCovered()
  {
    return ($this->getUncoveredLines() == 0);
  }

  public function getUncoveredLines()
  {
    return $this->getTotalLines() - $this->getCoveredLines();
  }

  public function getCoveredLinesPourcent()
  {
    if ($this->getTotalLines() == 0)
    {
      return 0;
    }
    return $this->getCoveredLines() / $this->getTotalLines() * 100;
  }

  public function toString()
  {
    return sprintf('%s %d%% %d/%d', $this->getName(), $this->getCoveredLinesPourcent(), $this->getCoveredLines(), $this->getTotalLines());
  }

}