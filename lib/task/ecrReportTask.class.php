<?php
class srCoverageReportTask extends sfBaseTask
{
  /**
   * (non-PHPdoc)
   * @see lib/vendor/symfony/task/sfTask#configure()
   */
  protected function configure()
  {
    $this->namespace           = 'ecr';
    $this->name                = 'report';
    $this->briefDescription    = 'Crée un rapport du coverage des tests unitaires pour emma';
    $this->detailedDescription = 'Crée un rapport du coverage des tests unitaires pour emma';

    $this->addOption('xml', null, sfCommandOption::PARAMETER_REQUIRED, 'Path to save xml file');
    $this->addOption('xdebug-extension-path', null, sfCommandOption::PARAMETER_OPTIONAL, 'Path to xdebug extension');
  }

    /**
   * (non-PHPdoc)
   * @see lib/vendor/symfony/task/sfTask#execute()
   */
  protected function execute($arguments = array(), $options = array())
  {
    ecrUtils::checkForXDebugExtenstion($options);
    $report = new ecrReport();
    $report->setXDebugPath($options['xdebug-extension-path']);
    $renderer = ecrRendererFactory::create('emma', $report->getCoveragePercentByFile());
    file_put_contents($options['xml'], $renderer->render());
  }

}