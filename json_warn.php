<?php
require_once('/var/www/html/dwd_warn/config_json.php');
date_default_timezone_set("Europe/Berlin");

function sonderzeichen($string){
$search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "´");
$replace = array("AE", "OE", "UE", "ae", "oe", "ue", "ss", "");
return str_replace($search, $replace, $string);
}

foreach ($gemkey as $key=>$region) {
   $stream = file_get_contents("http://www.wetterdienst.de/warnwetter/json_parser.php?region[]=$key");
   $json = substr($stream, 24, -2);
   $data = json_decode($json,true);
   // var_dump($data);

   if (isset($data["warnings"][$key][0]["regionName"])) {
	echo "Hab was...\n";
	echo substr($data["warnings"][$key][0]["end"],0,-3) . " " . time() . "\n";
	if (substr($data["warnings"][$key][0]["end"],0,-3) >= time()){

	$inhalt = ":BLN1WX" . strtoupper($region) . ":" . sonderzeichen(str_replace("Amtliche", "DWD",$data["warnings"][$key][0]["headline"])) . " in " . strtoupper(str_replace(" ", "", $region)) . 
	" bis " . date("d.m. H:i",substr($data["warnings"][$key][0]["end"],0,-3)) . "\n";

	echo $inhalt;
	$filename = "/var/www/html/dwd_warn/warn_" . strtolower(str_replace(" ", "", $region));
	$datei = fopen($filename,"a");
        fwrite($datei,"$inhalt");
        fclose($datei);
	}
}
}
?>
