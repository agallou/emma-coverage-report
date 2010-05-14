<?php
abstract class ecrIterator implements Iterator, Countable
{
  /**
   *
   * @var int
   */
  protected $position = 0;

  /**
   *
   * @var array
   */
  protected $values = array();

  /**
   *
   * @param array $values
   *
   * @return void
   */
  public function __construct(array $values = array())
  {
    foreach ($values as $value)
    {
      $this->add($value);
    }
  }

  abstract protected function getClass();

  function rewind()
  {
    $this->position = 0;
  }

  function current()
  {
    return $this->values[$this->position];
  }

  function key()
  {
    return $this->position;
  }

  function next()
  {
    ++$this->position;
  }

  public function valid()
  {
    return isset($this->values[$this->position]);
  }

  /**
   *
   * @param mixed $values
   *
   * @return void
   */
  public function add($values)
  {
    if (!is_array($values) && !($values instanceof ecrIterator))
    {
      $values = array($values);
    }
    foreach ($values as $value)
    {
      if (get_class($value) ==  $this->getClass())
      {
        $this->values[] = $value;
      }
    }
  }

  public function count()
  {
    return count($this->values);
  }
}