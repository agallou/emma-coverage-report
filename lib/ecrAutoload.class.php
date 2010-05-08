<?php
class ecrAutoload extends sfAutoload
{
  /**
   *
   * @var ecrAutoload
   */
  static protected $instance = null;

  /**
   *
   * @param $cacheFile
   *
   * @return ecrAutoload
   */
  static public function getInstance()
  {
    if (!isset(self::$instance))
    {
      self::$instance = new ecrAutoload();
    }

    return self::$instance;
  }

  public function getAllFiles()
  {
    return $this->classes;
  }

  /**
   *
   * @param string $class
   *
   * @return string
   */
  public function getClassPath($class)
  {
    if (!isset($this->classes[$class]))
    {
      if (!isset($this->classes[strtolower($class)]))
      {
        return null;
      }
      return $this->classes[strtolower($class)];
    }
    return $this->classes[$class];
  }

  /**
   *
   * @return void
   */
  public function forceRefreshCache()
  {
    self::$freshCache = false;
  }

}
