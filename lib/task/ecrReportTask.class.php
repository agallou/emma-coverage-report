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
    $this->addOption('exclusions', null, sfCommandOption::PARAMETER_OPTIONAL, 'yml file of exlusions globs');
  }

    /**
   * (non-PHPdoc)
   * @see lib/vendor/symfony/task/sfTask#execute()
   */
  protected function execute($arguments = array(), $options = array())
  {
    ecrUtils::checkForXDebugExtenstion($options);
    $this->createConfiguration(null, null);
    $f_options = array();
    if (isset($options['exclusions']))
    {
      if (!file_exists($options['exclusions']))
      {
        throw new sfException(sprintf('File %s not found', var_export($options['exclusions'], true)));
      }
      $f_options['exclusions_globs'] = ecrUtils::getExcludedFiles($options['exclusions']);
    }
    $report = new ecrReport();
    $report->setOptionsForFilesToTest($f_options);
    $report->setXDebugPath($options['xdebug-extension-path']);
    $renderer = ecrRendererFactory::create('emma', $report->getCoveragePercentByFile());
    file_put_contents($options['xml'], $renderer->render());
    $filesToTest = new ecrGenericFilesToTest($f_options);
    foreach ($filesToTest->getClassesNotFound() as $className => $testFile)
    {
      $this->log(sprintf('Fichier à tester non trouvé pour la classe "%s" (test : "%s")', $className, $testFile));
    }
  }

}