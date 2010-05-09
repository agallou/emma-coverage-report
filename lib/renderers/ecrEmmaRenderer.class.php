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

    $packages->setAttribute('value', 2);
    $classes->setAttribute('value', 2);
    $methods->setAttribute('value', 2);

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

    $coverageClass = $dom->createElement('coverage');
    $coverageClass->setAttribute('type', 'class, %');
    $coverageClass->setAttribute('value', '0% (0/0)');
    $all->appendChild($coverageClass);

    $coverageMethod = $dom->createElement('coverage');
    $coverageMethod->setAttribute('type', 'method, %');
    $coverageMethod->setAttribute('value', '0% (0/0)');
    $all->appendChild($coverageMethod);

    $coverageBlock = $dom->createElement('coverage');
    $coverageBlock->setAttribute('type', 'block, %');
    $coverageBlock->setAttribute('value', '0% (0/0)');
    $all->appendChild($coverageBlock);

    $elementTotalLines = $dom->createElement('coverage');
    $elementTotalLines->setAttribute('type', 'line, %');
    $all->appendChild($elementTotalLines);

    $dirs = array();

    foreach (array_keys($coverageByFile) as $file)
    {
      $dirs[] = str_replace(array('/', '.'), array('_', ''), pathinfo($file, PATHINFO_DIRNAME));
    }
    $dirs = array_unique($dirs);

    $packagesTab = array();
    $totalLinesPackageElements = array();
    foreach ($dirs as $dir)
    {
      $coverageClass = $dom->createElement('coverage');
      $coverageClass->setAttribute('type', 'class, %');
      $coverageClass->setAttribute('value', '0% (0/0)');

      $coverageMethod = $dom->createElement('coverage');
      $coverageMethod->setAttribute('type', 'method, %');
      $coverageMethod->setAttribute('value', '0% (0/0)');

      $coverageBlock = $dom->createElement('coverage');
      $coverageBlock->setAttribute('type', 'block, %');
      $coverageBlock->setAttribute('value', '0% (0/0)');

      $totalLinesPackageElements[$dir] = $dom->createElement('coverage');
      $totalLinesPackageElements[$dir]->setAttribute('type', 'line, %');

      $packagesTab[$dir] = $dom->createElement('package');
      $packagesTab[$dir]->setAttribute('name', $dir);
      $packagesTab[$dir]->appendChild($coverageClass);
      $packagesTab[$dir]->appendChild($coverageMethod);
      $packagesTab[$dir]->appendChild($coverageBlock);
      $packagesTab[$dir]->appendChild($totalLinesPackageElements[$dir]);
      $all->appendChild($packagesTab[$dir]);
    }

    $totalLines       = 0;
    $totalTestedLines = 0;
    $testedLinesPackage = array();
    $totalLinesPackage  = array();
    foreach ($coverageByFile as $file => $coveragePercent)
    {
      $key = str_replace(array('/', '.'), array('_', ''), pathinfo($file, PATHINFO_DIRNAME));
      $srcfile = $dom->createElement('srcfile');
      $strFile = str_replace("/", "_", $file);
      $srcfile->setAttribute('name', $strFile);
      $numberOfLines = $this->getNumberOfLinesFile(sfConfig::get('sf_root_dir') . DIRECTORY_SEPARATOR . $file);
      $testedLines = round($coveragePercent / 100 * $numberOfLines);
      $totalLines += $numberOfLines;
      $totalTestedLines += $testedLines;
      isset($testedLinesPackage[$key]) || $testedLinesPackage[$key] = 0;
      isset($totalLinesPackage[$key]) || $totalLinesPackage[$key] = 0;
      $testedLinesPackage[$key] += $testedLines;
      $totalLinesPackage[$key] += $numberOfLines;
      $coverageString = sprintf('%s (%s/%s)', $coveragePercent, $testedLines, $numberOfLines);

      $coverageClass = $dom->createElement('coverage');
      $coverageClass->setAttribute('type', 'class, %');
      $coverageClass->setAttribute('value', '0% (0/0)');
      $srcfile->appendChild($coverageClass);

      $coverageMethod = $dom->createElement('coverage');
      $coverageMethod->setAttribute('type', 'method, %');
      $coverageMethod->setAttribute('value', '0% (0/0)');
      $srcfile->appendChild($coverageMethod);

      $coverageBlock = $dom->createElement('coverage');
      $coverageBlock->setAttribute('type', 'block, %');
      $coverageBlock->setAttribute('value', '0% (0/0)');
      $srcfile->appendChild($coverageBlock);

      $coverageLine = $dom->createElement('coverage');
      $coverageLine->setAttribute('type', 'line, %');
      $coverageLine->setAttribute('value', $coverageString);
      $srcfile->appendChild($coverageLine);

      $strClass = pathinfo($file, PATHINFO_FILENAME);

      $class = $dom->createElement('class');
      $class->setAttribute('name', $strClass);
      $coverageClass2 = clone $coverageClass;
      $coverageMethod2 = clone $coverageMethod;
      $coverageBlock2 = clone $coverageBlock;
      $coverageLine2 = clone $coverageLine;
      $class->appendChild($coverageClass2);
      $class->appendChild($coverageMethod2);
      $class->appendChild($coverageBlock2);
      $class->appendChild($coverageLine2);

      $method = $dom->createElement('method');
      $method->setAttribute('name', $strClass);
      $coverageMethod3 = clone $coverageMethod;
      $coverageBlock3 = clone $coverageBlock;
      $coverageLine3 = clone $coverageLine;
      $method->appendChild($coverageMethod3);
      $method->appendChild($coverageBlock3);
      $method->appendChild($coverageLine3);

      $class->appendChild($method);

      $srcfile->appendChild($class);

      $packagesTab[$key]->appendChild($srcfile);
    }

    foreach($dirs as $dir)
    {
      $totalLinesString = sprintf('%s%% (%s/%s)', round($testedLinesPackage[$dir]/$totalLinesPackage[$dir]*100), $testedLinesPackage[$dir], $totalLinesPackage[$dir]);
      $totalLinesPackageElements[$dir]->setAttribute('value', $totalLinesString);
    }

    $stringTotalLines = sprintf('%s%% (%s/%s)', round($totalTestedLines/$totalLines*100), $totalTestedLines, $totalLines);
    $elementTotalLines->setAttribute('value', $stringTotalLines);

    $srcfiles->setAttribute('value', count($coverageByFile));
    $srclines->setAttribute('value', $totalLines);

    return $dom->saveXml();
  }

}