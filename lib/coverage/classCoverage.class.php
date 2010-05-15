<?php
/**
 * classCoverage.class.php
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
 * classCoverage
 *
 * @package    AgEmmaCoverageReport
 * @subpackage Coverage
 * @author     Adrien Gallou <adriengallou@gmail.com>
 * @version    Release: <package_version>
 *
 */
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
   * Constructor
   *
   * @param string $name name of the class
   *
   * @return void
   */
  public function __construct($name)
  {
    $this->name                   = $name;
    $this->methodCoverageIterator = new methodCoverageIterator();
  }

  /**
   * Get list of all methods of the class
   *
   * @return methodCoverageIterator
   */
  public function getMethodCoverageIterator()
  {
    return $this->methodCoverageIterator;
  }

  /**
   * Define list of methods for the class
   *
   * @param methodCoverageIterator $methodCoverageIterator list of methods
   *
   * @return void
   */
  public function setMethodCoverageIterator(
    methodCoverageIterator $methodCoverageIterator
  )
  {
    $this->methodCoverageIterator = $methodCoverageIterator;
  }

  /**
   * Number of lines of the class
   *
   * @return int
   */
  public function getTotalLines()
  {
    return $this->getMethodCoverageIterator()->getTotalLines();
  }

  /**
   * Name of the class
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Pourcent of covered lines
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
   * Number of covered lines
   *
   * @return int
   */
  public function getCoveredLines()
  {
    return $this->getMethodCoverageIterator()->getCoveredLines();
  }

  /**
   * Returns iterator of covered methods
   *
   * @return methodCoverageIterator
   */
  public function getCoveredMethods()
  {
    return $this->getMethodCoverageIterator()->getCoveredMethods();
  }

  /**
   * Returns iterator of uncovered methods
   *
   * @return methodCoverageIterator
   */
  public function getUnCoveredMethods()
  {
    return $this->getMethodCoverageIterator()->getUnCoveredMethods();
  }

  /**
   * Is class fully covered
   *
   * @return bool
   */
  public function isFullyCovered()
  {
    return (count($this->getUnCoveredMethods()) == 0);
  }

  /**
   * Pourcent% covered/total
   *
   * @deprecated
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

  /**
   * Count of all methods in class
   *
   * @return int
   */
  public function getMethodCount()
  {
    return count($this->getMethodCoverageIterator());
  }

  /**
   * Count of covered methods
   *
   * @return int
   */
  public function getCoveredMethodCount()
  {
    return $this->getMethodCoverageIterator()->getCoveredMethodsCount();
  }

  /**
   * Pourcent of covered methods
   *
   * @return int
   */
  public function getCoveredMethodPourcent()
  {
    return $this->getMethodCoverageIterator()->getCoveredMethodsPourcent();
  }

}