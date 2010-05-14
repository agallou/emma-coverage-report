<?php
class ecrEmmaRenderer extends ecrRenderer
{

  public function render()
  {
    $coverageByFile = $this->coverageByFile;
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;
    $dom->appendChild($dom->createComment(' EMMA report, generated ' . date('r') . ' '));
    $dom->appendChild($report = $dom->createElement('report'));

    $stats = $dom->createElement('stats');

    $packages = $dom->createElement('packages');
    $classes  = $dom->createElement('classes');
    $methods  = $dom->createElement('methods');
    $srclines = $dom->createElement('srclines');
    $srcfiles = $dom->createElement('srcfiles');

    $packages->setAttribute('value', $this->getFileCoverageIterator()->getPackageCount());
    $classes->setAttribute('value', $this->getFileCoverageIterator()->getClassCount());
    $methods->setAttribute('value', $this->getFileCoverageIterator()->getMethodCount());
    $srcfiles->setAttribute('value', count($this->getFileCoverageIterator()));
    $srclines->setAttribute('value', $this->getFileCoverageIterator()->getTotalLines());

    $stats->appendChild($packages);
    $stats->appendChild($classes);
    $stats->appendChild($methods);
    $stats->appendChild($srcfiles);
    $stats->appendChild($srclines);

    $report->appendChild($stats);

    $data = $dom->createElement('data');
    $all = $dom->createElement('all');
    $all->setAttribute('name', 'all classes');
    $data->appendChild($all);
    $report->appendChild($data);

    $this->appendInfosFromFileCoverageIterator($dom, $all, $this->getFileCoverageIterator());

    /* @var $filesCoverageInPackage FileCoverageIterator */
    foreach ($this->getFileCoverageIterator()->getByPackage() as $packageName => $filesCoverageInPackage)
    {
      $package = $dom->createElement('package');
      $package->setAttribute('name', $packageName);
      $this->appendInfosFromFileCoverageIterator($dom, $package, $filesCoverageInPackage);

      /* @var $coverageFile fileCoverage */
      foreach ($filesCoverageInPackage as $coverageFile)
      {
        $srcfile = $dom->createElement('srcfile');
        $srcfile->setAttribute('name', $coverageFile->getSanitizedName());
        $this->appendInfosFromFileCoverage($dom, $srcfile, $coverageFile);

        /* @var $coverageClass classCoverage */
        foreach ($coverageFile->getClassCoverageIterator() as $coverageClass)
        {
          $class = $dom->createElement('class');
          $class->setAttribute('name', $coverageClass->getName());
          $this->appendInfosFromClassCoverage($dom, $class, $coverageClass);
          foreach ($coverageClass->getMethodCoverageIterator() as $methodCoverage)
          {
            $method = $dom->createElement('method');
            $method->setAttribute('name', $methodCoverage->getName());
            $this->appendInfosFromMethodCoverage($dom, $method, $methodCoverage);
            $class->appendChild($method);
          }
          $srcfile->appendChild($class);
        }
        $package->appendChild($srcfile);
      }

      $all->appendChild($package);
    }
    return $dom->saveXml();
  }


  public function appendInfosFromMethodCoverage(DOMDocument $dom, DOMElement $element, methodCoverage $methodCoverage)
  {
    $infos = array(
      'block' => $this->getEmptyBlock(),
      'line'  => array(
        'pourcent' => $methodCoverage->getCoveredLinesPourcent(),
        'covered'  => $methodCoverage->getCoveredLines(),
        'total'    => $methodCoverage->getTotalLines(),
      ),
    );
    $this->appendInfos($dom, $element, $infos);
  }

  public function appendInfosFromClassCoverage(DOMDocument $dom, DOMElement $element, classCoverage $classCoverage)
  {
    $infos = array(
      'method' => array(
        'pourcent' => $classCoverage->getCoveredMethodPourcent(),
        'covered'  => $classCoverage->getCoveredMethodCount(),
        'total'    => $classCoverage->getMethodCount(),
      ),
      'block'  => $this->getEmptyBlock(),
      'line'   => array(
        'pourcent' => $classCoverage->getCoveredLinesPourcent(),
        'covered'  => $classCoverage->getCoveredLines(),
        'total'    => $classCoverage->getTotalLines(),
      ),
    );
    $this->appendInfos($dom, $element, $infos);
  }

  public function appendInfosFromFileCoverage(DOMDocument $dom, DOMElement $element, fileCoverage $fileCoverage)
  {
    $infos = array(
      'class'  => array(
        'pourcent' => $fileCoverage->getCoveredClassPourcent(),
        'covered'  => $fileCoverage->getCoveredClassCount(),
        'total'    => $fileCoverage->getClassCount(),
      ),
      'method' => array(
        'pourcent' => $fileCoverage->getCoveredMethodPourcent(),
        'covered'  => $fileCoverage->getCoveredMethodCount(),
        'total'    => $fileCoverage->getMethodCount(),
      ),
      'block'  => $this->getEmptyBlock(),
      'line'   => array(
        'pourcent' => $fileCoverage->getCoveredLinesPourcent(),
        'covered'  => $fileCoverage->getCoveredLines(),
        'total'    => $fileCoverage->getTotalLines(),
      ),
    );
    $this->appendInfos($dom, $element, $infos);
  }

  public function appendInfosFromFileCoverageIterator(DOMDocument $dom, DOMElement $element, fileCoverageIterator $fileCoverage)
  {
    $infos = array(
      'class'  => array(
        'pourcent' => $fileCoverage->getCoveredClassPourcent(),
        'covered'  => $fileCoverage->getCoveredClassCount(),
        'total'    => $fileCoverage->getClassCount(),
      ),
      'method' => array(
        'pourcent' => $fileCoverage->getCoveredMethodPourcent(),
        'covered'  => $fileCoverage->getCoveredMethodCount(),
        'total'    => $fileCoverage->getMethodCount(),
      ),
      'block'  => $this->getEmptyBlock(),
      'line'   => array(
        'pourcent' => $fileCoverage->getCoveredLinesPourcent(),
        'covered'  => $fileCoverage->getCoveredLinesCount(),
        'total'    => $fileCoverage->getTotalLines(),
      ),
    );
    $this->appendInfos($dom, $element, $infos);
  }

  public function appendInfos(DOMDocument $dom, DOMElement $element, array $infos)
  {
    foreach ($infos as $name => $info)
    {
      $coverageClass = $dom->createElement('coverage');
      $coverageClass->setAttribute('type', $name . ', %');
      $coverageClass->setAttribute('value', sprintf(
        '%d%% (%d/%d)',
        $info['pourcent'],
        $info['covered'],
        $info['total']
      ));
      $element->appendChild($coverageClass);
    }
  }

  protected function getEmptyBlock()
  {
    return array(
      'pourcent' => 0,
      'covered'  => 0,
      'total'    => 0,
    );
  }

}