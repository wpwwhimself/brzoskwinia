<?php
require("cue.php");
giveMeTheCue(0);

$lang = (isset($_GET['l']) && $_GET['l'] == "en") ? true : false;
generateHead(($lang)?"Song library":"Biblioteka utworów", 0, $lang);
require("variableconverter.php");
?>
<div class="home">
<a href="<?php echo ($lang) ? "?l=en": "/"; ?>"><h1><?php echo ($lang)?"Song library":"Biblioteka utworów"; ?></h1></a>
<p class="hinto"><?php echo ($lang)?"Select an album to play...":"Wybierz album, aby posłuchać..."; ?></p>
<section>
<h2><?php echo ($lang)?"The newest news":"Najnowsza nowinka"; ?></h2>
<div class="newthing">
<?
$q = "SELECT a.tytuł, a.album, n.kiedy, n.opis, n.opis2
    FROM s_nowinka2 as n
    LEFT JOIN p_projekty as a ON n.id = a.id
    LIMIT 1";
$r = $conn->query($q) or die($q.$conn->error);
$a = $r->fetch_assoc();
print "<div>";
    print "<img src='/library/".$a['album'].".png' alt='".$a['album']."' class='drop-shadow'>";
    print "<h3 class='hoverplay interactive'><img src='/interface/controls/play.svg' alt='play'></h3>";
print "</div><div>";
    print "<h3 class='interactive'>".$a['tytuł']."</h3>";
    print "<h4 class='interactive'>".$a['album']."</h4>";
    print "<p>".date("j.m.Y", strtotime($a['kiedy']))."</p>";
    print "<p>".(($lang)?$a['opis2']:$a['opis'])."</p>";
print "</div>";
$r->free_result();
?>
</div>    
</section>
<section>
<h2><?php echo ($lang)?"My albums":"Albumy własne"; ?></h2>
<div class="albumlist">
<?
$q = "SELECT nazwa FROM s_albumy WHERE własny = 1 ORDER BY id DESC;";
$r = $conn->query($q) or die($q.$conn->error);
while($a = $r->fetch_assoc()){
	print "<div class='singlealbum'>";
	print "<img src='/library/".$a['nazwa'].".png' alt='".$a['nazwa']."' class='drop-shadow'>";
	print "<h3 class='hoverplay interactive'>".$a['nazwa']."</h3>";
	print "</div>";
}
$r->free_result();
?>
</div>
</section>
<section>
<h2><?php echo ($lang)?"Cooperations and other tracks":"Kooperacje i pozostałe utwory"; ?></h2>
<div class="albumlist">
<?
$q = "SELECT nazwa FROM s_albumy WHERE własny = 0 ORDER BY id DESC;";
$r = $conn->query($q) or die($q.$conn->error);
while($a = $r->fetch_assoc()){
	print "<div class='singlealbum'>";
	print "<img src='/library/".$a['nazwa'].".png' alt='".$a['nazwa']."' class='drop-shadow'>";
	print "<h3 class='hoverplay interactive'>".$a['nazwa']."</h3>";
	print "</div>";
}
$r->free_result();
?>
</div>
</section>
</div>

<div class="albumdetails">
<span id="albumdetailsclose" class="closeicon interactive">×</span>
<h1><?php echo ($lang)?"About the album":"O albumie"; ?></h1>
<div class="info">
<div class='albumcontainer'><img src=# alt=# class="drop-shadow"><div class="hoverplay interactive"><img src="/interface/controls/play.svg" alt='play'></div></div>
<div class="contents">
<h2>-</h2>
<p>-</p>
<ul class="linear-list">
<li>-</li>
<li><a><?php echo ($lang)?"Get album link to paste...":"Pobierz link albumu do wklejenia..."; ?></a><span class='green-tick'>&#x2713;</span></li>
</ul>
</div>
<div class="songlist drop-shadow">
<h3><?php echo ($lang)?"Track list":"Lista utworów"; ?></h3>
<ol>
<? for($i=0; $i<20; $i++) print "<li class='interactive'>-</li>"; ?>
</ol>
</div>
</div>
</div>

<div class="songdetails">
<span id="songdetailsclose" class="closeicon interactive">«</span>
<h1><?php echo ($lang)?"About the track":"O utworze"; ?></h1>
<p class="hinto"><?php echo ($lang)?"Click the cover to play...":"Kliknij okładkę, aby odtworzyć..."; ?></p>
<div class="info drop-shadow">
<div class='albumcontainer'><img src=# alt=# class="drop-shadow"><div class="hoverplay interactive"><img src="/interface/controls/play.svg" alt='play'></div></div>
<div class="contents">
<h2>-</h2>
<h3>z albumu XXX</h3>
</div>
</div>
<ul class="linear-list">
<li>projekt XXX</li>
<li>Nagrany XXX</li>
<li>Inspiracja: XXX</li>
<li><a><?php echo ($lang)?"Get track link to paste...":"Pobierz link utworu do wklejenia..."; ?></a><span class='green-tick'>&#x2713;</span></li>
</ul>
</div>

<div id=player>
<audio></audio>
<div class='albumcontainer'>
	<img src='library/brak albumu.png' alt='<?php echo ($lang)?"Select track...":"Wybierz utwór..."; ?>' class='album'>
	<div class="hoverplay interactive pause"><img src="/interface/controls/play.svg" alt='play'></div>
	<div class="hoverplay interactive next"><img src="/interface/controls/next.svg" alt='next'></div>
	<input type="range" min=0 max=100 value=75 class="mobile-hide interactive"></input>
</div>
<div>
<p><?php echo ($lang)?"Select track...":"Wybierz utwór..."; ?></p>
<h4 class='interactive'>-</h4>
<h5 class='interactive'>-</h5>
</div>
<img src="interface/load.gif" alt='loading...' class='load'>
</div>

<?
generateBottom("main");
?>