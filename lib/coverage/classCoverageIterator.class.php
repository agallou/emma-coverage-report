<?php
/**
 * classCoverageIterator.class.php
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
 * classCoverageIterator
 *
 * @package    AgEmmaCoverageReport
 * @subpackage Coverage
 * @author     Adrien Gallou <adriengallou@gmail.com>
 * @version    Release: <package_version>
 *
 */
class classCoverageIterator extends ecrIterator
{

  /**
   * (non-PHPdoc)
   *
   * @see plugins/agEmmaCoverageReportPlugin/lib/utils/ecrIterator#getClass()
   * @return string
   */
  protected function getClass()
  {
    return 'classCoverage';
  }

  /**
   * Total number of methods in all classes
   *
   * @return int
   */
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

  /**
   * Total of lines in classes
   *
   * @return int
   */
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

  /**
   * Total of covered lines in classes
   *
   * @return int
   */
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

  /**
   * Iterator of covered classes
   *
   * @return classCoverageIterator
   */
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

  /**
   * Number of covered classes
   *
   * @return int
   */
  public function getCoveredClassCount()
  {
    return count($this->getCoveredClass());
  }

  /**
   * Pourcent of covered classes
   *
   * @return int
   */
  public function getCoveredClassPourcent()
  {
    if (count($this) == 0)
    {
      return 0;
    }
    return $this->getCoveredClassCount() / count($this) * 100;
  }

  /**
   * Pourcent of covered methods
   *
   * @return int
   */
  public function getCoveredMethodPourcent()
  {
    if ($this->getMethodCount() == 0)
    {
      return 0;
    }
    return $this->getCoveredMethodsCount() / $this->getMethodCount() * 100;
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
   * Iterator of covered methods
   *
   * @return methodCoverageIterator
   */
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
   * Iterator of uncovered classes
   *
   * @return classCoverageIterator
   */
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