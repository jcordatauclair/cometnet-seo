<?php


#------------------------------------------------------------------------------#
#                              WRITE PARAMS TO .TXT                            #
#------------------------------------------------------------------------------#
//$paramFile = '../searchEngine/param/parameters.txt';
$paramFile = '../CrawlGooglePythonBundle/bin/param/parameters.txt';
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
chdir("../CrawlGooglePythonBundle/bin");
exec("python scrape.py");
#------------------------------------------------------------------------------#

print("\n");

#------------------------------------------------------------------------------#
#                    TRANSFORM THE OUTPUT FILE AS A PHP ARRAY                  #
#------------------------------------------------------------------------------#
$outputFile = file_get_contents('../CrawlGooglePythonBundle/bin/searchEngine/output/output.json');
$arrayPHP = json_decode($outputFile, true);

print("\n");
print("###### decode the JSON file : OK! ######");
print("\n");

$arrayUserSite = array_slice($arrayPHP, 0, 1, true);
$arrayGoogleResults = array_slice($arrayPHP, 1, -1);

print("\n");
print_r($arrayUserSite);
print("\n");

print("\n");
print_r($arrayGoogleResults);
print("\n");

print("\n");
print("###### split the arrays : OK! ######");
print("\n");

$tabResGoogle = array();

foreach($arrayGoogleResults as $unResultat) {
  $chaineEcho = $unResultat['title'];
  $chaineEcho .= $unResultat['itemsURL'];

  $tabResGoogle[] = array('title'=>$unResultat['title'],
                    'itemsURL'=>$unResultat['itemsURL']);
}

print("\n");
print_r($tabResGoogle);
print("\n");

print("\n");
print("###### tabResGoogle : OK! ######");
print("\n");

print_r($arrayUserSite[0]['title']);
#------------------------------------------------------------------------------#
