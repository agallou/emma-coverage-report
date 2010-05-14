<?php
class ecrTokenTask extends sfbaseTask
{
  protected function configure()
  {
    $this->namespace = 'ecr';
    $this->name = 'token';

    $this->addOption('xml', null, sfCommandOption::PARAMETER_REQUIRED, 'Path to save xml file');
    $this->addOption('xdebug-extension-path', null, sfCommandOption::PARAMETER_OPTIONAL, 'Path to xdebug extension');
    $this->addOption('exclusions', null, sfCommandOption::PARAMETER_OPTIONAL, 'yml file of exlusions globs');
  }

  protected function execute($arguments = array(), $options = array())
  {
    ecrUtils::checkForXDebugExtenstion($options);
    $f_options = array();
    if (isset($options['exclusions']))
    {
      if (!file_exists($options['exclusions']))
      {
        throw new sfException(sprintf('File %s not found', var_export($options['exclusions'], true)));
      }
      $f_options['exclusions_globs'] = ecrUtils::getExcludedFiles($options['exclusions']);
    }
    $filesToTest = new ecrGenericFilesToTest($f_options);
    $processor   = new ecrProcessor($filesToTest);
    $processor->setXDebugPath($options['xdebug-extension-path']);
    $filesCoverage = $processor->process();
    $renderer = ecrRendererFactory::create('emma', $filesCoverage);
    $this->log(sprintf('Saving %s', $options['xml']));
    file_put_contents($options['xml'], $renderer->render());
    foreach ($filesToTest->getClassesNotFound() as $className => $testFile)
    {
      $this->log(sprintf('File to test not found for class "%s" (test : "%s")', $className, $testFile));
    }
  }

}
