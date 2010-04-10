<?php
class ecrUtils
{

  /**
   *
   * @param array $options
   *
   * @throws Exception
   *
   * @return void
   */
  public static function checkForXDebugExtenstion($options)
  {
    if (!array_key_exists('xdebug-extension-path', $options) || is_null($options['xdebug-extension-path']))
    {
      if (!function_exists('xdebug_start_code_coverage'))
      {
        throw new Exception('You must install and enable (or set xdebug-extension-path) xdebug before using lime coverage.');
      }

      if (!ini_get('xdebug.extended_info'))
      {
        throw new Exception('You must set xdebug.extended_info to 1 in your php.ini to use lime coverage.');
      }
    }
    else
    {
      if (!file_exists($options['xdebug-extension-path']))
      {
        throw new Exception('Xdebug extension file not found.');
      }
    }

  }

}