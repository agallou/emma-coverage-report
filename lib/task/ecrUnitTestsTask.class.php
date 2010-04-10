<?php
class ecrTestUnitTask extends sfBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::OPTIONAL | sfCommandArgument::IS_ARRAY, 'The test name'),
    ));

    $this->namespace = 'ecr-test';
    $this->name = 'unit';
    $this->briefDescription = 'Launches unit tests';
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (count($arguments['name']))
    {
      foreach ($arguments['name'] as $name)
      {
        $files = sfFinder::type('file')->follow_link()->name(basename($name).'Test.php')->in(sfConfig::get('sf_test_dir').DIRECTORY_SEPARATOR.'unit'.DIRECTORY_SEPARATOR.dirname($name));
        foreach ($files as $file)
        {
          include($file);
        }
      }
    }
    else
    {
      require_once(sfConfig::get('sf_symfony_lib_dir').'/vendor/lime/lime.php');

      $h = new lime_harness(new lime_output_color());
      $h->base_dir = sfConfig::get('sf_plugins_dir').'/agEmmaCoverageReportPlugin/test/unit';

      // register unit tests
      $finder = sfFinder::type('file')->follow_link()->name('*Test.php');
      $h->register($finder->in($h->base_dir));

      return $h->run() ? 0 : 1;
    }
  }
}
