<?php
require_once 'common.php';
require_once 'xmlParse.php';
ini_set("memory_limit","3072M");

echo "\n\nOPEN FILE\n\n";
$filename = "hndb.xml";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);

echo "\n\nPARSE XML\n\n";
$xmlObj    = new XmlToArray($contents); 
$hndb = $xmlObj->createArray(); 

echo "\n\nMETRICS\n\n";
echo count($hndb);
var_dump(array_keys($hndb['HackerNews']['row']));
?>
