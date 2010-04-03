<?php
class ecrLimeCoverage extends lime_coverage
{

  protected $phpOptions = array();

  public function setPhpOptions($options)
  {
    $this->phpOptions = $options;
  }

  public function getPhpOptions()
  {
    return $this->phpOptions;
  }

  public function __construct($harness)
  {
    $this->harness = $harness;
  }

  protected function checkForXdebug()
  {
    $phpOptions = $this->getPhpOptions();
    if (!array_key_exists('xdebug-extension-path', $phpOptions))
    {
      if (!function_exists('xdebug_start_code_coverage'))
      {
        throw new Exception('You must install and enable xdebug before using lime coverage.');
      }

      if (!ini_get('xdebug.extended_info'))
      {
        throw new Exception('You must set xdebug.extended_info to 1 in your php.ini to use lime coverage.');
      }
    }
  }

  /**
   * We just change the call to test to pass the path to xdebug extension
   *
   * (non-PHPdoc)
   * @see lib/vendor/symfony/vendor/lime/lime_coverage#process($files)
   */
  public function process($files)
  {
    $this->checkForXdebug();
    if (!is_array($files))
    {
      $files = array($files);
    }

    $phpOptions = $this->getPhpOptions();
    $options    = '';
    if (array_key_exists('xdebug-extension-path', $phpOptions))
    {
      $options .= sprintf('-d "zend_extension=%s" ', $phpOptions['xdebug-extension-path']);
    }

    $tmp_file = sys_get_temp_dir().DIRECTORY_SEPARATOR.'test.php';
    foreach ($files as $file)
    {
      $tmp = <<<EOF
<?php
xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
include('$file');
echo '<PHP_SER>'.serialize(xdebug_get_code_coverage()).'</PHP_SER>';
EOF;
      file_put_contents($tmp_file, $tmp);
      ob_start();
      // see http://trac.symfony-project.org/ticket/5437 for the explanation on the weird "cd" thing
      passthru(sprintf('cd & %s %s %s 2>&1', escapeshellarg($this->harness->php_cli), $options, escapeshellarg($tmp_file)), $return);
      $retval = ob_get_clean();

      if (0 != $return) // test exited without success
      {
        // something may have gone wrong, we should warn the user so they know
        // it's a bug in their code and not symfony's

        $this->harness->output->echoln(sprintf('Warning: %s returned status %d, results may be inaccurate', $file, $return), 'ERROR');
      }

      if (false === $cov = @unserialize(substr($retval, strpos($retval, '<PHP_SER>') + 9, strpos($retval, '</PHP_SER>') - 9)))
      {
        if (0 == $return)
        {
          // failed to serialize, but PHP said it should of worked.
          // something is seriously wrong, so abort with exception
          throw new Exception(sprintf('Unable to unserialize coverage for file "%s"', $file));
        }
        else
        {
          // failed to serialize, but PHP warned us that this might have happened.
          // so we should ignore and move on
          continue; // continue foreach loop through $this->harness->files
        }
      }

      foreach ($cov as $file => $lines)
      {
        if (!isset($this->coverage[$file]))
        {
          $this->coverage[$file] = $lines;
          continue;
        }

        foreach ($lines as $line => $flag)
        {
          if ($flag == 1)
          {
            $this->coverage[$file][$line] = 1;
          }
        }
      }
    }

    if (file_exists($tmp_file))
    {
      unlink($tmp_file);
    }
  }

}