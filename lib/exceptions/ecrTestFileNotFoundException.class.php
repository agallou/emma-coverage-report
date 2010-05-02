<?php
class ecrTestFileNotFoundException extends sfException
{

  public function __construct($class = '')
  {
    $msg = 'Test file not found';
    if (strlen($class))
    {
      $msg .= sprintf(' (class : %s)', $class);
    }
    $this->message =  $msg;
    return parent::__construct();
  }

}