<?php
class fileCoverageIterator extends ecrIterator
{

  protected function getClass()
  {
    return 'fileCoverage';
  }

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

  public function getPackageCount()
  {
    return count($this->getAllPackages());
  }

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

  public function getCoveredClassPourcent()
  {
    if ($this->getClassCount() == 0)
    {
      return 0;
    }
    return $this->getCoveredClassCount() / $this->getClassCount() * 100;
  }

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

  public function getCoveredMethodPourcent()
  {
    if ($this->getMethodCount() == 0)
    {
      return 0;
    }
    return $this->getCoveredMethodCount() / $this->getMethodCount() * 100;
  }

  public function getCoveredLinesPourcent()
  {
    if ($this->getTotalLines() == 0)
    {
      return 0;
    }
    return $this->getCoveredLinesCount()/ $this->getTotalLines() * 100;
  }
}