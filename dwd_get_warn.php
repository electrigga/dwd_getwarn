<?php

require_once('/var/www/html/dwd_warn/config.php'); 

// Kreis Gotha
$gth_datei = "/var/www/html/dwd_warn/warn_gth";
$gth_rgx = "/(GTHX)/";
$gth_bln = "BLN1WXGTH";
$gth_lk  = "Kreis Gotha";

// Eisenach
$ea_datei = "/var/www/html/dwd_warn/warn_ea";
$ea_rgx = "/(EAXX)/";
$ea_bln = "BLN1WXEA ";
$ea_lk  = "Stadt Eisenach";

// Wartburgkreis
$wak_datei = "/var/www/html/dwd_warn/warn_wak";
$wak_rgx = "/(WAKX)/";
$wak_bln = "BLN1WXWAK";
$wak_lk  = "Wartburgkreis";

// Unstrut Hainich
$uh_datei = "/var/www/html/dwd_warn/warn_uh";
$uh_rgx = "/(UHXX)/";
$uh_bln = "BLN1WXUH ";
$uh_lk  = "Unstrut-Hainich";

// Schmalkalden Meiningen
$sm_datei = "/var/www/html/dwd_warn/warn_sm";
$sm_rgx = "/(SMXX)/";
$sm_bln = "BLN1WXSM ";
$sm_lk  = "Schmaldkalden-Meiningen";

// Erfurt
$ef_datei = "/var/www/html/dwd_warn/warn_ef";
$ef_rgx = "/(EFXX)/";
$ef_bln = "BLN1WXEF ";
$ef_lk  = "Erfurt";

require("/var/www/html/dwd_warn/dwd_get_warn_function.php");
// Verbindung aufbauen
$conn_id = ftp_connect($ftp_server) or die("Konnte keine Verbindung zu $ftp_server aufbauen");

// Login mit Benutzername und Passwort
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("Login nicht korrekt");

// Datein aus Verzeichnis in ein Array einlesen
// $buff = ftp_nlist($conn_id, $ftp_verzeichnis_sms);
$buff = ftp_nlist($conn_id, $ftp_verzeichnis_cap);

// print_r($buff);
// print_r($buff_cap);

// Ergebnisse für Gotha
warnung_extract($gth_rgx, $gth_datei, $gth_bln, $gth_lk, $buff, $conn_id);

// Ergebnisse Wartburgkreis
warnung_extract($wak_rgx, $wak_datei, $wak_bln, $wak_lk, $buff, $conn_id);

// Ergebnisse für Unstrut-Hainich
warnung_extract($uh_rgx, $uh_datei, $uh_bln, $uh_lk, $buff, $conn_id);

// Ergebnisse für Schmaldkalden-Meiningen
warnung_extract($sm_rgx, $sm_datei, $sm_bln, $sm_lk, $buff, $conn_id);

// Ergebnisse für Erfurt
warnung_extract($ef_rgx, $ef_datei, $ef_bln, $ef_lk, $buff, $conn_id);

// Ergebnisse für Eisenach
warnung_extract($ea_rgx, $ea_datei, $ea_bln, $ea_lk, $buff, $conn_id);

// Verbindung schließen
ftp_close($conn_id);
?>
