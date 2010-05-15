<?php
/**
 * fileCoverageIterator.class.php
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
 * fileCoverageIterator
 *
 * @package    AgEmmaCoverageReport
 * @subpackage Coverage
 * @author     Adrien Gallou <adriengallou@gmail.com>
 * @version    Release: <package_version>
 *
 */
class fileCoverageIterator extends ecrIterator
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
    return 'fileCoverage';
  }

  /**
   * Return array of fileCoverageIterator with package name in key
   *
   * @return array
   */
  public function getByPackage()
  {
    $packages = array();
    /* @var $fileCoverage fileCoverage */
    foreach ($this as $fileCoverage)
    {
      if (!isset($packages[$fileCoverage->getPackageName()]))
      {
        $packages[$fileCoverage->getPackageName()] = new fileCoverageIterator();
      }
      $packages[$fileCoverage->getPackageName()]->add($fileCoverage);
    }
    return $packages;
  }

  /**
   * Return array of all packages names
   *
   * @return array
   */
  public function getAllPackages()
  {
    $packages = array();
    /* @var $fileCoverage fileCoverage */
    foreach ($this as $fileCoverage)
    {
      $packages[] = $fileCoverage->getPackageName();
    }
    return array_unique($packages);
  }

  /**
   * Return number of packages
   *
   * @return int
   */
  public function getPackageCount()
  {
    return count($this->getAllPackages());
  }

  /**
   * Return number of classes
   *
   * @return int
   */
  public function getClassCount()
  {
    $classesCount = 0;
    /* @var $fileCoverage fileCoverage */
    foreach ($this as $fileCoverage)
    {
      $classesCount += $fileCoverage->getClassCount();
    }
    return $classesCount;
  }

  /**
   * Return number of methods
   *
   * @return int
   */
  public function getMethodCount()
  {
    $methodCount = 0;
    /* @var $fileCoverage fileCoverage */
    foreach ($this as $fileCoverage)
    {
      $methodCount += $fileCoverage->getMethodCount();
    }
    return $methodCount;
  }

  /**
   * Number of lines
   *
   * @return int
   */
  public function getTotalLines()
  {
    $totalLines = 0;
    /* @var $fileCoverage fileCoverage */
    foreach ($this as $fileCoverage)
    {
      $totalLines += $fileCoverage->getTotalLines();
    }
    return $totalLines;
  }

  /**
   * Number of covered lines
   *
   * @return int
   */
  public function getCoveredLinesCount()
  {
    $lines = 0;
    /* @var $fileCoverage fileCoverage */
    foreach ($this as $fileCoverage)
    {
      $lines += $fileCoverage->getCoveredLines();
    }
    return $lines;
  }

  /**
   * Number of covered classes
   *
   * @return int
   */
  public function getCoveredClassCount()
  {
    $classCount = 0;
    /* @var $fileCoverage fileCoverage */
    foreach ($this as $fileCoverage)
    {
      $classCount += count($fileCoverage->getCoveredClass());
    }
    return $classCount;
  }

  /**
   * Pourcent of covered classes
   *
   * @return int
   */
  public function getCoveredClassPourcent()
  {
    if ($this->getClassCount() == 0)
    {
      return 0;
    }
    return $this->getCoveredClassCount() / $this->getClassCount() * 100;
  }

  /**
   * Number of covered methods
   *
   * @return int
   */
  public function getCoveredMethodCount()
  {
    $count = 0;
    /* @var $fileCoverage fileCoverage */
    foreach ($this as $fileCoverage)
    {
      $count += count($fileCoverage->getCoveredMethods());
    }
    return $count;
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
    return $this->getCoveredMethodCount() / $this->getMethodCount() * 100;
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
    return $this->getCoveredLinesCount()/ $this->getTotalLines() * 100;
  }
}