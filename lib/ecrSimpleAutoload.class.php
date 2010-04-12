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

  /**
   *
   * @param string$class
   *
   * @return string
   */
  public function getClassPath($class)
  {
    $class = strtolower($class);
    if (!isset($this->classes[$class]))
    {
      return null;
    }
    return $this->classes[$class];
  }
}
