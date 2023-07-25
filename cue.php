<?php

/*****PODSTAWY*STRONY*****/

function generateHead($title, $sheet = 0, $en = false){
	//czyli początek początku
	print "<!DOCTYPE html><html lang=\"";
	print ($en)? "en" : "pl";
	print '\"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
	
	//konstrukcja tytułu
	print "<title>";
	if(isset($_GET['s']) || isset($_GET['a'])) print $_GET['s'].$_GET['a']." | $title | WPWW";
	    else print $title." | WPWW";
	print "</title>";
	
	//metatagi, czyli kolejna porcja bajdurzenia
	print "<meta name=author content=\"Wojciech Przybyła, Wesoły Wojownik\">";
	print "<meta name=description content=\"Zebrany dorobek artystyczny WPWW, działającego obecnie pod nazwą „Lightstream Music & Entertainment”. Dziesiątki utworów w zasięgu ręki.\">";
	print "<meta name=keywords content=\"Wojciech Przybyła, Wesoły Wojownik, fajna strona, Lightstream, WPWW, podkłady, muzyka\">";
	print "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
	print "<meta property='og:image' content='http://brzoskwinia.wpww.pl/interface/thumbnail.jpg' />";
	print "<meta property='og:type' content='website' />";
	print "<meta property='og:url' content='http://brzoskwinia.wpww.pl/' />";
	print "<meta property='og:title' content='Projekt Brzoskwinia – biblioteka utworów LM&E | WPWW' />";
	print "<meta property='og:description' content='Zebrany dorobek artystyczny WPWW, działającego obecnie pod nazwą „Lightstream Music & Entertainment”. Dziesiątki utworów w zasięgu ręki.' />";
		
	//Google Fonts
	print "<link href=\"https://fonts.googleapis.com/css?family=Krona+One\" rel=\"stylesheet\">";
	print "<link href=\"https://fonts.googleapis.com/css?family=Raleway\" rel=\"stylesheet\">";
	print "<link href=\"https://fonts.googleapis.com/css?family=Marcellus+SC\" rel=\"stylesheet\">";
	
	//główny CSS
	print "<link rel=stylesheet type='text/css' href='/interface/style.css?".time()."'>";
	//opcjonalny CSS
	if($sheet != "0") print "<link rel=stylesheet type='text/css' href='/interface/".$sheet.".css?".time()."'>";
	
	//ikona
	print "<link rel=icon type='image/png' href='/interface/logo.png'>";
	
	//jQuery
	echo<<<CHUJ
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
CHUJ;
	
	//koniec głowy, początek ciała
	print "</head><body>";
	
	nakrzyczNaLudzi();
}

function generateBottom($script = 0){
	//copyright
	print "<footer>Meticulously designed and furiously crafted by<br><a href='http://wpww.pl'>WPWW himself</a> • &copy;2018 – ".date("Y")."<br>";
	print (isset($_GET['l']) && $_GET['l'] == "en") ? "<a href='/'>Polski</a>" : "<a href='?l=en'>English</a>";
	if(isset($_GET['archmage'])) print "<br><a href=\"upload.php\">Jestem arcymagiem...</a>";
	print "</footer>";

	//opcjonalny skrypt
	if($script != "0") print "<script src='/interface/".$script.".js?".time()."'></script>";
	
	//trigger
	print "<script>";
	require("trigger.php");
	print "</script>";

	//koniec dokumentu - koniec ciała
	print "</body></html>";
	
	//jeśli podano połączenie z bazą w argumencie, wyłącz je
	global $conn;
	$conn->close();
}

/*****BUDULCE*****/

function nakrzyczNaLudzi(){ //jak nazwa wskazuje
	echo<<<CHUJ
<!--
───▄▀▀▀▄▄▄▄▄▄▄▀▀▀▄───
───█▒▒░░░░░░░░░▒▒█───
────█░░█░░░░░█░░█────
─▄▄──█░░░▀█▀░░░█──▄▄─
█░░█─▀▄░░░░░░░▄▀─█░░█
If you came here to judge my style of webpage design, be ready to use logical arguments.
Because if they are a pointless mantra, I do not need to heed your remarks.
-->
CHUJ;
}

/*****BAZA*DANYCH*****/

function giveMeTheCue($b){
	//łączenie z bazą danych
	global $conn;
	$conn = new mysqli("localhost", "p497635_archmage", "viper400X", "p497635_$b");
	
	//błędy
	if($conn->connect_error) echo "Nie można się połączyć z bazą: ".$conn->connect_error;
	
	//charset, bo świra dostaje
	$conn->set_charset("utf8");
}
?>