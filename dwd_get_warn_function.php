<?php
function sonderzeichen($string){
$search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "´");
$replace = array("Ae", "Oe", "Ue", "ae", "oe", "ue", "ss", "");
return str_replace($search, $replace, $string);
}

function warnung_extract($rgx, $datei_lk, $bln, $lk, $buff, $conn_id) {

	$key = preg_grep("$rgx", $buff);
	if ($key) {
        // Lädt letze Datei mit $rgx Schlüssel herunter
	$last_cap = array_pop($key);
	$datei_lk_cap = "$datei_lk" . "_cap";
	ftp_get($conn_id, "$datei_lk_cap", $last_cap, FTP_TEXT);

	// Öffnen der XML Datei
	$xmlFile = "$datei_lk_cap";
	if (file_exists($xmlFile)) {
    		$xml = simplexml_load_file($xmlFile);
       	}
	 else {
    		exit("Datei $xmlFile kann nicht geöffnet werden.");
	}

	// Gültigkeit der Meldung
	$expire = strtotime(substr($xml->info->expires,0,10) . " " . substr($xml->info->expires,11,5));
	echo $expire . " " . strtotime("now") . "<br />";

	//Vergleich der Gültigkeit
	if($expire > strtotime("now")) {

	//Prüfen der Beschreibung, ob Stufe vorhanden
	if (strstr(substr($xml->info->description, -15, 13))) {
		$stufe = sonderzeichen(preg_replace("#[\r|\n]#", "", substr($xml->info->description, -15, 13)));}

	//Datei Schreiben bei aktueller Wettermeldung
	$inhalt_neu = ":$bln:" . sonderzeichen($xml->info->area->areaDesc) . ": " . sonderzeichen($xml->info->headline) . " - " . 
			$stufe . " bis " . date("H:i",$expire) . " Uhr " . " => www.dwd.de\n";
			echo $inhalt_neu . "<br />";
                }

	else {$inhalt_neu = "";}
	$datei = fopen("$datei_lk","w");
        echo fwrite($datei,"$inhalt_neu");
        fclose($datei);
	} // END IF KEY

	else {
        	$datei = fopen("$datei_lk","w");
        	echo fwrite($datei, "",100);
        	fclose($datei);
	}
} // End Funtion
?>
