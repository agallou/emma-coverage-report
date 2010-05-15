<?php
/**
 * fileCoverage.class.php
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
 * fileCoverage
 *
 * @package    AgEmmaCoverageReport
 * @subpackage Coverage
 * @author     Adrien Gallou <adriengallou@gmail.com>
 * @version    Release: <package_version>
 *
 */
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
   * Constructor
   *
   * @param string $name file name
   *
   * @return void
   */
  public function __construct($name)
  {
    $this->name                  = $name;
    $this->classCoverageIterator = new classCoverageIterator();
  }

  /**
   * Iterator of classes in file
   *
   * @return classCoverageIterator
   */
  public function getClassCoverageIterator()
  {
    return $this->classCoverageIterator;
  }

  /**
   * Set iterator of classes on file
   *
   * @param classCoverageIterator $classCoverageIterator classes in file
   *
   * @return void
   */
  public function setClassCoverageIterator(
    classCoverageIterator $classCoverageIterator
  )
  {
    $this->classCoverageIterator = $classCoverageIterator;
  }

  /**
   * Returns file name
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Returns sanitized file name
   *
   * @return string
   */
  public function getSanitizedName()
  {
    return self::sanitizeName($this->getName());
  }

  /**
   * Returns package name
   *
   * @return string
   */
  public function getPackageName()
  {
    return self::sanitizeName(pathinfo($this->getName(), PATHINFO_DIRNAME));
  }

  /**
   * Package and file names links does not work on Hudson CI if the link contains
   *  slashes of dots.
   *
   * @param string $name string to sanitize
   *
   * @return string
   */
  protected static function sanitizeName($name)
  {
    return str_replace(array('/', '.'), array('_', ''), $name);
  }

  /**
   * Number of classes in file
   *
   * @return int
   */
  public function getClassCount()
  {
    return count($this->getClassCoverageIterator());
  }

  /**
   * Returns number of methods in file
   *
   * @return int
   */
  public function getMethodCount()
  {
    return $this->getClassCoverageIterator()->getMethodCount();
  }

  /**
   * Iterator of covered classes
   *
   * @return classCoverageIterator
   */
  public function getCoveredClass()
  {
    return $this->getClassCoverageIterator()->getCoveredClass();
  }

  /**
   * Iterator of covered methods
   *
   * @return methodCoverageIterator
   */
  public function getCoveredMethods()
  {
    return $this->getClassCoverageIterator()->getCoveredMethods();
  }

  /**
   * Number of covered methods in file
   *
   * @return int
   */
  public function getCoveredMethodCount()
  {
    return $this->getClassCoverageIterator()->getCoveredMethodsCount();
  }

  /**
   * Pourcent of covered methods in file
   *
   * @return int
   */
  public function getCoveredMethodPourcent()
  {
    return $this->getClassCoverageIterator()->getCoveredMethodPourcent();
  }

  /**
   * Number of covered lines in file
   *
   * @return int
   */
  public function getCoveredLines()
  {
    return $this->getClassCoverageIterator()->getCoveredLines();
  }

  /**
   * Iterator of non covered classes
   *
   * @return classCoverageIterator
   */
  public function getUnCoveredClass()
  {
    return $this->getClassCoverageIterator()->getUnCoveredClass();
  }

  /**
   * Number of lines in file
   *
   * @return int
   */
  public function getTotalLines()
  {
    return $this->getClassCoverageIterator()->getTotalLines();
  }

  /**
   * Pourcent of covered lines
   *
   * @return int
   */
  public function getCoveredLinesPourcent()
  {
    return $this->getClassCoverageIterator()->getCoveredLinesPourcent();
  }

  /**
   * Number of covered classes
   *
   * @return unknown_type
   */
  public function getCoveredClassCount()
  {
    return $this->getClassCoverageIterator()->getCoveredClassCount();
  }

  /**
   * Pourcent of covered classes
   *
   * @return int
   */
  public function getCoveredClassPourcent()
  {
    return $this->getClassCoverageIterator()->getCoveredClassPourcent();
  }
}