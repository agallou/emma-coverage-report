<?php
class ignored1
{

  /**
   * @codeCoverageIgnore
   */
  protected function thisMethodIsIgnoed()
  {
    foreach ($toto as $titi)
    {
      $car = 'blue';
      if ($car == 'red')
      {
        $color = 'abc';
      }
      $color = 'test';
      return true;
    }
    return $tutu;
  }
}
