<?php
/**
 * methodCoverage.class.php
 *
 * PHP version 5
 *
 * @package    AgEmmaCoverageReport
 * @subpackage Coverage
 * @author     Adrien Gallou <adriengallou@gmail.com>
 * @version    SVN: <svn_id>
 *
 */
/**
 * methodCoverage
 *
 * @package    AgEmmaCoverageReport
 * @subpackage Coverage
 * @author     Adrien Gallou <adriengallou@gmail.com>
 * @version    Release: <package_version>
 *
 */
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
   * Constructor
   *
   * @param string $name method name
   *
   * @return void
   */
  public function __construct($name)
  {
    $this->name = $name;
  }

  /**
   * Number of lines
   *
   * @return int
   */
  public function getTotalLines()
  {
    return $this->totalLines;
  }

  /**
   * Set number of lines
   *
   * @param int $totalLines number of lines
   *
   * @return void
   */
  public function setTotalLines($totalLines)
  {
    $this->totalLines = $totalLines;
  }

  /**
   * Number of covered lines
   *
   * @return int
   */
  public function getCoveredLines()
  {
    return $this->coveredLines;
  }

  /**
   * Set number of covered lines
   *
   * @param int $coveredLines number of covered lines
   *
   * @return void
   */
  public function setCoveredLines($coveredLines)
  {
    $this->coveredLines = $coveredLines;
  }

  /**
   * Returns method name
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Is all method lines covered
   *
   * @return bool
   */
  public function isFullyCovered()
  {
    return ($this->getUncoveredLines() == 0);
  }

  /**
   * Number of uncovered lines
   *
   * @return int
   */
  public function getUncoveredLines()
  {
    return $this->getTotalLines() - $this->getCoveredLines();
  }

  /**
   * Pourcent of uncovered lines
   *
   * @return int
   */
  public function getCoveredLinesPourcent()
  {
    if ($this->getTotalLines() == 0)
    {
      return 0;
    }
    return $this->getCoveredLines() / $this->getTotalLines() * 100;
  }

  /**
   * Pourcent% covered/total
   *
   * @deprecated
   *
   * @return string
   */
  public function toString()
  {
    return sprintf('%s %d%% %d/%d',
      $this->getName(),
      $this->getCoveredLinesPourcent(),
      $this->getCoveredLines(),
      $this->getTotalLines());
  }

}