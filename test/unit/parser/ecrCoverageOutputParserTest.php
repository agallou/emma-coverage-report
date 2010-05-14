<?php
include(dirname(__FILE__).'/../../bootstrap/unit.php');

include(dirname(__FILE__).'/../../../lib/parser/ecrCoverageOutputParser.class.php');

$t = new lime_test(4, new lime_output_color());

$output = array (
  0 => '>> coverage  running /home/agallou/workspace...ider/fichierSerieTest.php (1/1)',
  1 => 'lib/util/fichierSerie.class                                             96%',
  2 => '# missing: [44 - 45]',
  3 => 'TOTAL COVERAGE:  96%',
);

$missing = array(44, 45);

$parser = new ecrCoverageOutputParser($output);
$t->is_deeply($parser->getMissing(), $missing, '2 lignes manquantes ok');


$output = array (
  0 => '>> coverage  running /home/agallou/workspace...sProvider/srUtilsTest.php (1/1)',
  1 => 'lib/util/srUtils.class                                                  25%',
  2 => '# missing: 19 [30 - 34] [45 - 55] [57 - 66] 101 [108 - 110] [112 - 114] [116 - 119] [124 - 125] [127 - 129] [137 - 138] [140 - 141] [143 - 146] [154 - 157] 159 [164 - 167] 169 174 179',
  3 => 'TOTAL COVERAGE:  25%',
);
$missing = array(19, 30, 31, 32, 33, 34, 45, 46, 47, 48, 49, 50, 51 ,52, 53, 54, 55, 57, 58, 59, 60 ,61, 62, 63, 64, 65, 66, 101, 108, 109, 110, 112, 113, 114, 116, 117, 118, 119,
124, 125, 127, 128, 129, 137, 138, 140, 141, 143, 144, 145, 146, 154, 155, 156, 157, 159, 164, 165, 166, 167, 169, 174, 179);

$parser = new ecrCoverageOutputParser($output);
$t->is_deeply($parser->getMissing(), $missing, 'plusieurs lignes manquantes ok');

$output = array (
  0 => '>> coverage  running /home/agallou/workspace...ProviderSerieImdbTest.php (1/1)',
  1 => 'lib/infosProvider/infosProviderSerieImdb.class                          88%',
  2 => '# missing: 70 79 103 [117 - 118] 139 178 [180 - 184]',
  3 => 'TOTAL COVERAGE:  88%',
);

$missing = array(70, 79, 103, 117, 118, 139, 178, 180, 181, 182, 183, 184);

$parser = new ecrCoverageOutputParser($output);
$t->is_deeply($parser->getMissing(), $missing, 'plusieurs lignes 2 manquantes ok');

$output = array (
  0 => '>> coverage  running /home/agallou/workspace...derSerieThetvdbenTest.php (1/1)',
  1 => 'lib/infosProvider/infosProviderSerieThetvdben.class                    100%',
  2 => 'TOTAL COVERAGE: 100%',
);

$parser = new ecrCoverageOutputParser($output);
$t->is_deeply($parser->getMissing(), array(), '100% ok');