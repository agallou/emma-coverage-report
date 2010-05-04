<?php
class ecrAutoload extends sfAutoload
{
  static protected $instance = null;

  /**
   *
   * @param $cacheFile
   *
   * @return ecrSimpleAutoload
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
}
