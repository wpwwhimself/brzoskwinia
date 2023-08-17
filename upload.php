<?php
require_once("auth.php");
session_start();

if(isset($_POST['klucz'])){
	if($_POST['klucz'] == BACKEND_PASSWORD) $_SESSION['klucz'] = true;
}

if(!isset($_SESSION['klucz'])){?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<h1>Klucz?</h1>
	<form method=post>
		<input type=password name='klucz' autofocus/>
		<input type=submit value="Do boju!" />
	</form>
<?php }else{
require("cue.php");
giveMeTheCue(0);

unset($album_summary); unset($song_summary);
if(isset($_POST['submit'])){
	if($_POST['submit'] == "Utwórz album"){
		//kwerenda wpisująca
		$q = "INSERT INTO s_albumy VALUES ('', '";
		$q .= str_replace("'", "\'", $_POST['album_name'])."', '";
		$q .= $_POST['album_year']."', '";
		$q .= $_POST['album_color']."', ";
			$_POST['album_own'] = ($_POST['album_own'] == 1)? 1 : 0;
			$q .= $_POST['album_own'].", '";
		$q .= str_replace("'", "\'", $_POST['album_desc'])."', '";
		$q .= str_replace("'", "\'", $_POST['album_desc2'])."');";
		$conn->query($q) or die($q.$conn->error);
		
		//wgraj cover
		$catalogue = "library/";
		$file = $catalogue.$_POST['album_name'].".png";
		move_uploaded_file($_FILES['album_cover']["tmp_name"], $file);
		
		//utwórz katalog na utwory
		mkdir($catalogue.$_POST['album_name']);
		
		$album_summary = "Gotowe! Dodano ".$_POST['album_name'].".";
	}if($_POST['submit'] == "Dodaj utwór"){
		//kwerenda wpisująca
		if($_POST['song_type'] == 0){
    		$q = "INSERT INTO s_piosenki2 VALUES ('";
    		$q .= $_POST['song_id']."', ";
    		$q .= $_POST['song_nr'].")";
		}else{
		    $q = "INSERT INTO s_cnb VALUES ('";
    		$q .= $_POST['song_id']."', ";
    		$q .= ($_POST['song_type']-1).", ";
    		$q .= $_POST['song_nr'].")";
		}
		$conn->query($q) or die($q.$conn->error);
		
		//wgraj utwór
		$q = "SELECT tytuł, album, data_out FROM p_projekty WHERE id = '".$_POST['song_id']."'";
		$r = $conn->query($q) or die($conn->error);
		$a = $r->fetch_assoc();
		$r->free_result;
		if($_POST['song_type'] == 1) $a['album'] = "brak albumu";
		if($_POST['song_type'] == 2) $a['album'] = "Covery";
		
		$catalogue = "library/".$a['album']."/";
		$file = $catalogue.$a['tytuł'].".ogg";
		move_uploaded_file($_FILES['song_file']["tmp_name"], $file);
		
		$song_summary = "Gotowe! Dodano ".$a['tytuł'].".";
		
		//nowinka
		if($_POST['song_new']){
		    $q = "UPDATE s_nowinka2 SET ";
    	    $q .= "id = '".$_POST['song_id']."', ";
    	    $q .= "kiedy = '".date('Y-m-d', strtotime($a['data_out']))."', ";
    	    $q .= "opis = '', ";
    	    $q .= "opis2 = ''";
    	    $conn->query($q) or die($q.$conn->error);
    	    
    	    $song_summary .= "<br>Nowinka zaktualizowana. Dodaj opis!";
		}
	}if($_POST['submit'] == "Odśwież"){
	    //kwerenda podstawiająca
	    $q = "UPDATE s_nowinka2 SET ";
	    $q .= "id = '".$_POST['new_id']."', ";
	    $q .= "kiedy = '".date('Y-m-d', strtotime($_POST['new_date']))."', ";
	    $q .= "opis = '".str_replace("'", "\'", $_POST['new_desc'])."', ";
	    $q .= "opis2 = '".str_replace("'", "\'", $_POST['new_desc2'])."'";
	    $conn->query($q) or die($q.$conn->error);
	    
	    $new_summary = "Gotowe! ".$_POST['new_id']." jest teraz nowinką.";
	}
}

generateHead("Uploader arcymaga");
?>
<div class="home">
<h1>Uploader arcymaga</h1>
<div style="display: flex; justify-content: space-around;">
<section>
<h2>Utwórz album</h2>
<form method='post' enctype="multipart/form-data">
<label for="album_name">Nazwa</label>
<input type="text" name="album_name" id="album_name"></input><br>
<label for="album_cover">Okładka</label>
<input type="file" name="album_cover" id="album_cover"></input><br>
<label for="album_year">Rok</label>
<input type="text" name="album_year" id="album_year" placeholder="albo nd."></input><br>
<label for="album_color">Kolor</label>
<input type="color" name="album_color" id="album_color"></input><br>
<label for="album_own">Własny?</label>
<input type="checkbox" name="album_own" id="album_own" value=1></input><br>
<label for="album_desc">Opis</label>
<textarea name="album_desc" id="album_desc"></textarea><br>
<label for="album_desc">Opis inglisz</label>
<textarea name="album_desc2" id="album_desc2"></textarea><br>
<input type="submit" name="submit" value="Utwórz album"></input>
</form>
<? if(isset($album_summary)) print "<p>$album_summary</p>"; ?>
</section>

<section>
<h2>Dodaj utwór</h2>
<form method='post' enctype="multipart/form-data">
<label for="song_id">ID projektu</label>
<input type="text" name="song_id" id="song_id"></input><br>
<input type="radio" name="song_type" id="song_type_0" value=0><label for="song_type_0">standard</label>
<input type="radio" name="song_type" id="song_type_1" value=1><label for="song_type_1">brak albumu</label>
<input type="radio" name="song_type" id="song_type_2" value=2><label for="song_type_2">cover</label><br>
<label for="song_nr">Numer ścieżki na albumie</label>
<input type="number" name="song_nr" id="song_nr"></input><br>
<label for="song_file">Plik</label>
<input type="file" name="song_file" id="song_file"></input><br>
<label for="song_new">Dodaj jako nowinkę</label>
<input type="checkbox" name="song_new" id="song_new"></input><br>
<input type="submit" name="submit" value="Dodaj utwór"></input>
</form>
<? if(isset($song_summary)) print "<p>$song_summary</p>"; ?>
</section>

<section>
<h2>Co jest nowinką?</h2>
<?
$q = "SELECT * FROM s_nowinka2";
$r = $conn->query($q) or die($conn->error);
$nowinka = $r->fetch_assoc();
$r->free_result();
?>
<form method="post">
<label for="new_id">ID projektu</label>
<input type="text" name="new_id" id="new_id" value="<?php echo $nowinka['id']; ?>" /><br>
<label for="new_date">Kiedy to</label>
<input type="date" name="new_date" id="new_date" value="<?php echo $nowinka['kiedy']; ?>"></input><br>
<label for="new_desc">Opis</label>
<textarea name="new_desc" id="new_desc"><?php echo $nowinka['opis']; ?></textarea><br>
<label for="new_desc">Opis inglisz</label>
<textarea name="new_desc2" id="new_desc2"><?php echo $nowinka['opis2']; ?></textarea><br>
<input type="submit" name="submit" value="Odśwież"></input>
</form>
<? if(isset($new_summary)) print "<p>$new_summary</p>"; ?>
</section>
</div>
</div>
<a href="/?archmage=1"><b>Zurück...</b></a>
<?
generateBottom();
}
?>