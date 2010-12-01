<?php
class ecrProcessorWithPhpdocIgnore extends ecrProcessor
{

  protected function getUncoveredLines($file)
  {
    $uncoveredLines = parent::getUncoveredLines($file);
    $ignoredLines   = $this->getIgnoredLinesFromPhpdoc($file);
    $uncoveredLines = $this->removeIgnoredLinesFromUncoveredLines($uncoveredLines, $ignoredLines, $file);
    return $uncoveredLines;
  }

  protected function removeIgnoredLinesFromUncoveredLines($uncoveredLines, array $ignoredLines, $file)
  {
    if (null === $uncoveredLines)
    {
      $uncoveredLines = range(1, count(file($file)));
    }
    foreach ($ignoredLines as $ignoredLine)
    {
      if (in_array($ignoredLine, $uncoveredLines))
      {
        unset($uncoveredLines[array_search($ignoredLine, $uncoveredLines)]);
      }
    }
    return array_values($uncoveredLines);
  }


  protected function getIgnoredLinesFromPhpdoc($file)
  {
    $phpdocignore = new ecrPhpdocIgnore($file);
    return $phpdocignore->getIgnoredLines();
  }

}
