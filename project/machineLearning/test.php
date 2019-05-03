<?php


#------------------------------------------------------------------------------#
#                              WRITE PARAMS TO .TXT                            #
#------------------------------------------------------------------------------#
$paramFile = '../searchEngine/param/parameters.txt';
unlink($paramFile);

$openParam = fopen($paramFile, 'w') or die('Cannot open file:  '.$paramFile);

$userURL = 'http://com-et-net.com/';
$nResults = 5;
$efficiency = 2;
$setKeywords = ['web', 'design', 'référencement'];

fwrite($openParam, $userURL);
fwrite($openParam, "\n".$nResults);
fwrite($openParam, "\n".$efficiency);
foreach ($setKeywords as $keyword) {
    fwrite($openParam, "\n".$keyword);
}

fclose($openParam);
#------------------------------------------------------------------------------#


#------------------------------------------------------------------------------#
#                                CALL SCRAPE.PY                                #
#------------------------------------------------------------------------------#
chdir("../searchEngine/");
exec("python scrape.py");
#------------------------------------------------------------------------------#

print("\n");

#------------------------------------------------------------------------------#
#                    TRANSFORM THE OUTPUT FILE AS A PHP ARRAY                  #
#------------------------------------------------------------------------------#
$outputFile = file_get_contents('../searchEngine/searchEngine/output/output.json');
$arrayPHP = json_decode($outputFile, true);

$arrayUserSite = array_slice($arrayPHP, 0, 1, true);
$arrayGoogleResults = array_slice($arrayPHP, 1, -1);

print_r($arrayUserSite);
print_r($arrayGoogleResults);
#------------------------------------------------------------------------------#
