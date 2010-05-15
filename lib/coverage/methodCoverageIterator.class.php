<?php
/**
 * methodCoverageIterator.class.php
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
 * methodCoverageIterator
 *
 * @package    AgEmmaCoverageReport
 * @subpackage Coverage
 * @author     Adrien Gallou <adriengallou@gmail.com>
 * @version    Release: <package_version>
 *
 */
class methodCoverageIterator extends ecrIterator
{

  /**
   * (non-PHPdoc)
   *
   * @see plugins/agEmmaCoverageReportPlugin/lib/utils/ecrIterator#getClass()
   *
   * @return string
   */
  protected function getClass()
  {
    return 'methodCoverage';
  }

  /**
   * Number of lines in all methods
   *
   * @return int
   */
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

  /**
   * Number of covered lines in all methods
   *
   * @return int
   */
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
   * Iterator of covered methods
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

  /**
   * Number of covered methods
   *
   * @return int
   */
  public function getCoveredMethodsCount()
  {
    return count($this->getCoveredMethods());
  }

  /**
   * Pourcent of covered methods
   *
   * @return int
   */
  public function getCoveredMethodsPourcent()
  {
    if (count($this) == 0)
    {
      return 0;
    }
    return $this->getCoveredMethodsCount() / count($this) * 100;
  }

  /**
   * Iterator of non covered methods
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