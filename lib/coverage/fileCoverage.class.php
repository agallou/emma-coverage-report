<?php
class fileCoverage
{
  /**
   *
   * @var classsCoverageIterator
   */
  protected $classCoverageIterator;

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
    $this->name                  = $name;
    $this->classCoverageIterator = new classCoverageIterator();
  }

  /**
   *
   * @return classCoverageIterator
   */
  public function getClassCoverageIterator()
  {
    return $this->classCoverageIterator;
  }

  /**
   *
   * @param classCoverageIterator $classsCoverage
   *
   * @return void
   */
  public function setClassCoverageIterator(classCoverageIterator $classCoverageIterator)
  {
    $this->classCoverageIterator = $classCoverageIterator;
  }

  /**
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  public function getSanitizedName()
  {
    return self::sanitizeName($this->getName());
  }

  public function getPackageName()
  {
    return self::sSanitizeName(pathinfo($this->getName(), PATHINFO_DIRNAME));
  }

  protected static function sanitizeName($name)
  {
    return str_replace(array('/', '.'), array('_', ''), $name);
  }

  public function getClassCount()
  {
    return count($this->getClassCoverageIterator());
  }

  public function getMethodCount()
  {
    return $this->getClassCoverageIterator()->getMethodCount();
  }

  public function getCoveredClass()
  {
    return $this->getClassCoverageIterator()->getCoveredClass();
  }

  public function getCoveredMethods()
  {
    return $this->getClassCoverageIterator()->getCoveredMethods();
  }

  public function getCoveredMethodCount()
  {
    return $this->getClassCoverageIterator()->getCoveredMethodsCount();
  }

  public function getCoveredMethodPourcent()
  {
    return $this->getClassCoverageIterator()->getCoveredMethodPourcent();
  }

  public function getCoveredLines()
  {
    return $this->getClassCoverageIterator()->getCoveredLines();
  }

  public function getUnCoveredClass()
  {
    return $this->getClassCoverageIterator()->getUnCoveredClass();
  }

  public function getTotalLines()
  {
    return $this->getClassCoverageIterator()->getTotalLines();
  }

  public function getCoveredLinesPourcent()
  {
    return $this->getClassCoverageIterator()->getCoveredLinesPourcent();
  }

  public function getCoveredClassCount()
  {
    return $this->getClassCoverageIterator()->getCoveredClassCount();
  }

  public function getCoveredClassPourcent()
  {
    return $this->getClassCoverageIterator()->getCoveredClassPourcent();
  }
}