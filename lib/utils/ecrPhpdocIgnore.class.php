<?php
class ecrPhpdocIgnore
{

  protected $file;

  public function __construct($file)
  {
    $this->file = $file;
    //TODO exception si fichier non trouvé
  }

  public function getIgnoredLines()
  {
    $tokens = token_get_all(file_get_contents($this->file));
    $usedTokens = array();
    foreach ($tokens as $token)
    {
      if ($token[0] == T_FUNCTION || $token[0] == T_DOC_COMMENT)
      {
        $usedTokens[] = $token;
      }
    }
    $usedTokens[] = array(null, null, count(file($this->file)));
    $ignoredLines = array();
    $nextFunctionWillBeIgnored = false;
    $isCurrentFunctionIgnored   = false;
    $startLine = null;
    foreach ($usedTokens as $token)
    {
      if ($isCurrentFunctionIgnored)
      {
        $endLine = $token[2];
        if ($token[0] == T_FUNCTION || $token[0] == T_DOC_COMMENT)
        {
          $endLine--;
        }
        $ignoredLines = array_merge($ignoredLines, range($startLine, $endLine));
        $nextFunctionWillBeIgnored = false;
        $isCurrentFunctionIgnored  = false;
        $startLine                 = null;
      }

      if ($token[0] == T_DOC_COMMENT && strpos($token[1], '@codeCoverageIgnore'))
      {
        $nextFunctionWillBeIgnored = true;
      }
      elseif ($token[0] == T_FUNCTION && $nextFunctionWillBeIgnored && null === $startLine)
      {
        $isCurrentFunctionIgnored = true;
        $startLine = $token[2];
      }
          }
    return $ignoredLines;
  }


}
