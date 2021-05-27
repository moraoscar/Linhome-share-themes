<?php

$header = <<<EOT
<?xml version='1.0' encoding='UTF-8'?>
<resources>
EOT;
$trailer="</resources>";

function xmlDom()  {
    $xmlfile = "../texts.xml";
    $xml = file_get_contents($xmlfile);
    $dom = new DOMDocument;
    $dom->loadXML($xml);
    return $dom;
}


$dom = xmlDom();
$supported = array();
$sections = $dom->getElementsByTagName('section');
foreach ($sections as $section) {
	$code=$section->getAttribute("name");
	$langs = $section->getElementsByTagName('entry');
	foreach ($langs as $lang) {
		$name = $lang->getAttribute("name");
		if (!in_array($name, $supported)) {
    			$supported[] = $name;
			$all[$name]=$header;
		}
		$all[$name] .=  "\n <string name=\"$code\">".addslashes($lang->nodeValue)."</string> ";
	}	
}

foreach($supported as $lang) {
$name = $lang == "default" ? "" : "-$lang"; 
if (!file_exists("res/values$name")) {
    mkdir("res/values$name", 0777, true);
}
$all[$lang] .= "\n$trailer";
file_put_contents("res/values$name/strings.xml",$all[$lang]);
}


?>


