<?php
class ignored1
{

  /**
   * @codeCoverageIgnore
   */
  protected function thisMethodIsIgnoed()
  {
    $car = 'blue';
    $color = 'test';
    return true;
  }

  protected function anotherMethod()
  {
  }
}
