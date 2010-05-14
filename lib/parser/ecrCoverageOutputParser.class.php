<?php
class ecrCoverageOutputParser
{

  protected $missing;

  public function __construct(array $output)
  {
    if (!isset($output[2]))
    {
      $this->missing = null;
      return;
    }
    if ($output[2] == 'TOTAL COVERAGE: 100%')
    {
      $this->missing = array();
      return;
    }
    $matches = array();
    $only = array();
    $range = array();
    preg_match_all('/(\d+)/', $output[2], $only);
    preg_match_all('/\[(\d+)\s-\s(\d+)\]/U', $output[2], $ranges, PREG_SET_ORDER);

    $only = $only[1];
    $rangeLines = array();
    foreach ($ranges as $range)
    {
      if (in_array($range[1], $only))
      {
        unset($only[array_search($range[1], $only)]);
      }
      if (in_array($range[2], $only))
      {
        unset($only[array_search($range[2], $only)]);
      }
      for($i=$range[1];$i<=$range[2];$i++)
      {
        $rangeLines[] = $i;
      }
    }

    $missing = array();
    $missing = array_merge($missing, $only);
    $missing = array_merge($missing, $rangeLines);
    sort($missing);
    foreach ($missing as &$miss)
    {
      $miss = (int)$miss;
    }
    $this->missing = $missing;
  }

  public function getMissing()
  {
    return $this->missing;
  }
}