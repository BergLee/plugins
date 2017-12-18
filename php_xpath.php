<?php 
error_reporting(E_ERROR);
$doc=new DOMDocument ();
$doc->loadHtmlFile("xxx.html");


$xpath=new DOMXpath($doc);
$result=$xpath->query("//ul[@id='menu']/li");
echo "<pre>";
print_r($result);
 ?>