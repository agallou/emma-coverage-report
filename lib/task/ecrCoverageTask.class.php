<?php
class ecrTestCoverageTask extends sfTestCoverageTask
{

  protected $xdebugExtensionPath = null;

  protected function configure()
  {
    parent::configure();
    $this->namespace = 'ecr';
    $this->addOption('xdebug-extension-path', null, sfCommandOption::PARAMETER_OPTIONAL, 'Path to xdebu extension');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->xdebugExtensionPath = $options['xdebug-extension-path'];
    parent::execute($arguments, $options);
  }

  protected function getCoverage(lime_harness $harness, $detailed = false)
  {
    $coverage = new ecrLimeCoverage($harness);
    $coverage->setPhpOptions(array('xdebug-extension-path' => $this->xdebugExtensionPath));
    $coverage->verbose = $detailed;
    $coverage->base_dir = sfConfig::get('sf_root_dir');
    $coverage->base_dir = '';

    return $coverage;
  }

}
