<?php
class ecrRendererFactory
{

  /**
   *
   * @param string               $type
   * @param FileCoverageIterator $fileCoverage
   *
   * @return ecrRenderer
   */
  public static function create($type, FileCoverageIterator $fileCoverage)
  {
    switch ($type)
    {
      case 'emma':
        return new ecrEmmaRenderer($fileCoverage);
        break;
      default:
        throw new sfException(sprintf('Type %s not accepted', var_export($type, true)));
        break;
    }
  }

}