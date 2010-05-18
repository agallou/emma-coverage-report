<?php
class ecrFileCoverageMaker
{
  protected $fileName;

  protected $unTestedLines;

  public function __construct($fileName)
  {
    $this->fileName= $fileName;
  }

  public function getFileCoverage()
  {
    $coverage = new fileCoverage($this->getAbsoluteFileName());
    $coverage->setClassCoverageIterator($this->getClasssCoverage());
    return $coverage;
  }

  public function getAbsoluteFileName()
  {
    return substr($this->getTestedFile(), strlen(sfConfig::get('sf_root_dir')) + 1);
  }

  protected function getUnTestedLines()
  {
    if (is_null($this->unTestedLines))
    {
      $missing = array();
      for($i=1;$i<=count(file($this->getTestedFile()));$i++)
      {
        $missing[] = $i;
      }
      return $missing;
    }
    return $this->unTestedLines;
  }

  public function setUntestedLines($unTestedLines)
  {
    $this->unTestedLines = $unTestedLines;
  }

  protected function getTestedFile()
  {
    return $this->fileName;
  }

  protected function getAllCount($type = T_FUNCTION)
  {
    $tokens = token_get_all(file_get_contents($this->getTestedFile()));
    $nb     = 0;
    foreach ($tokens as $token)
    {
      $token_name = is_array($token) ? $token[0] : null;
      if (is_null($token_name) || $token_name != $type)
      {
        continue;
      }
      $nb++;
    }
    return $nb;
  }

  protected function getUncoveredCount($type = T_FUNCTION)
  {
    $tokens        = token_get_all(file_get_contents($this->getTestedFile()));
    $nb            = 0;
    $functionsPos = array();
    foreach ($tokens as $token)
    {
      $token_name = is_array($token) ? $token[0] : null;
      if (is_null($token_name) || $token_name != $type)
      {
        continue;
      }
      $functionsPos[] = $token[2];
    }
    $functionsPos[] = count(file($this->getTestedFile()));
    for($i = 0; $i < (count($functionsPos)-1); $i++)
    {
      $unTestedMethod = false;
      foreach ($this->getUnTestedLines() as $untestedLine)
      {
        if ($untestedLine >= $functionsPos[$i] && $untestedLine <= $functionsPos[$i+1])
        {
          $unTestedMethod = true;
        }
        if ($unTestedMethod)
        {
          $nb++;
          break;
        }
      }
    }
    return $nb;
  }

  /**
   *
   * @TODO passer en paramètre la classe ?
   * @TODO réutiliser getCount pour connaitre les limites de la classe ?
   * @TODO divier en deux, appel à une méthode getLines($type) ??
   *
   * @param $type
   * @return array
   */
  protected function getCount($type = T_FUNCTION)
  {
    $nameAfterToken = 1;
    $tokens       = token_get_all(file_get_contents($this->getTestedFile()));
    $nb           = 0;

    $functionsPos = array();
    $previousToken = null;
    $posAfterToken = 0;
    foreach ($tokens as $token)
    {
      if (!is_null($previousToken))
      {
        if ($posAfterToken == $nameAfterToken)
        {
          $functionsPos[] = array('name' => $token[1], 'begining' => $previousToken);
          $previousToken = null;
          $posAfterToken = 0;
          continue;
        }
        $posAfterToken++;
      }
      $token_name = is_array($token) ? $token[0] : null;
      if (is_null($token_name) || $token_name != $type)
      {
        continue;
      }
      $previousToken = $token[2];
    }

    for($i = 0; $i < (count($functionsPos)); $i++)
    {
      if (isset($functionsPos[$i+1]))
      {
        $functionsPos[$i]['end'] = $functionsPos[$i+1]['begining']-1;
      }
      else
      {
        $functionsPos[$i]['end'] = count(file($this->getTestedFile()));
      }
      $functionsPos[$i]['count'] = $functionsPos[$i]['end'] - $functionsPos[$i]['begining'];
    }

    $return       = array();
    foreach ($functionsPos as $pos)
    {
      $return[$pos['name']] = array(
        'count'  => $pos['count'],
        'tested' => $pos['count'],
      );
    }

    foreach ($functionsPos as $pos)
    {
      //$unTestedMethod = false;
      foreach ($this->getUnTestedLines() as $untestedLine)
      {
        if ($untestedLine >= $pos['begining'] && $untestedLine <= $pos['end'])
        {
          $return[$pos['name']]['tested']--;
        }
      }
    }

    return $return;
  }

  /**
   *
   * @TODO passer en paramètre la classe ?
   *
   * @return methodCoverageIterator
   */
  protected function getMethodsCoverage($className)
  {
    $iterator     = new methodCoverageIterator();
    $functionsPos = $this->getCount(T_FUNCTION);
    foreach ($functionsPos as $name => $pos)
    {
      //TODO cas ou c'est à - 1
      //(méthode createInfosProvider)
      $coverage = new methodCoverage($name);
      $coverage->setTotalLines($pos['count']);
      $coverage->setCoveredLines(($pos['tested'] > 0) ? $pos['tested'] : 0);
      $iterator->add($coverage);
    }
    return $iterator;
  }

  protected function getClasssCoverage()
  {
    $iterator     = new classCoverageIterator();
    $functionsPos = $this->getCount(T_CLASS);
    foreach (array_keys($functionsPos) as $name)
    {
      $coverage = new classCoverage($name);
      $coverage->setMethodCoverageIterator($this->getMethodsCoverage($name));
      $iterator->add($coverage);
    }
    return $iterator;
  }

}
