<?php
class ecrRendererFactory
{

  /**
   *
   * @param string $type
   * @param array  $coverageByFile
   *
   * @return ecrRenderer
   */
  public static function create($type, array $coverageByFile)
  {
    switch ($type)
    {
      case 'emma':
        return new ecrEmmaRenderer($coverageByFile);
        break;
      default:
        throw new sfException(sprintf('Type %s not accepted', var_export($type, true)));
        break;
    }
  }

}