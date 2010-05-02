<?php
class ecrSimpleAutoload extends sfSimpleAutoload
{
  static protected $instance;

  /**
   *
   * @param $cacheFile
   *
   * @return ecrSimpleAutoload
   */
  static public function getInstance($cacheFile = null)
  {
    if (!isset(self::$instance))
    {
      self::$instance = new ecrSimpleAutoload($cacheFile);
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
