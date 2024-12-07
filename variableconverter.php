<script>
//język
<?php $lang = (isset($_GET['l']) && $_GET['l'] == "en") ? true : false; ?>
var lang = <?php echo ($lang) ? "true" : "false"; ?>;

//betoniarka zmiennych
var albumy = new Array();
var piosenki = new Array();
<?php
//informacje na temat wszystkich albumów
$q = "SELECT nazwa, rok, kolor, opis, opis2 FROM s_albumy";
$r = $conn->query($q) or die($q.$conn->error);
while($a = $r->fetch_assoc()){?>
albumy['<?php echo str_replace("'", "\'", $a['nazwa']); ?>'] = new Array('rok', 'kolor', 'opis', 'utwory');
albumy['<?php echo str_replace("'", "\'", $a['nazwa']); ?>']['rok'] = "<?php echo $a['rok']; ?>";
albumy['<?php echo str_replace("'", "\'", $a['nazwa']); ?>']['kolor'] = "<?php echo $a['kolor']; ?>";
albumy['<?php echo str_replace("'", "\'", $a['nazwa']); ?>']['opis'] = "<?php echo ($lang)?$a['opis2']:$a['opis']; ?>";
<?php $albumylista[] = $a['nazwa'];
}
$r->free_result();

//wypełnienie albumów utworami
foreach($albumylista as $x){?>
albumy['<?php echo $x; ?>']['utwory'] = new Array();
<?php
    if($x == "brak albumu"){
        $q = "SELECT a.tytuł, p.nr, p.id, a.nazwa, a.data_out, a.inspiracja
            FROM s_cnb as p
            LEFT JOIN p_projekty as a ON p.id = a.id
            WHERE p.cover = 0 
            ORDER BY p.nr ASC";
    }else{
        if($x == "Covery"){
            $q = "SELECT a.tytuł, p.nr, p.id, a.nazwa, a.data_out, a.inspiracja
                FROM s_cnb as p
                LEFT JOIN p_projekty as a ON p.id = a.id
                WHERE p.cover = 1 
                ORDER BY p.nr ASC";
        }else{
        $q = "SELECT a.tytuł, p.nr, p.id, a.nazwa, a.data_out, a.inspiracja
            FROM s_piosenki2 as p
            LEFT JOIN p_projekty as a ON p.id = a.id
            WHERE a.album like '$x'
            ORDER BY p.nr ASC";
        }
    }
	$r = $conn->query($q) or die($q.$conn->error);
	while($a = $r->fetch_assoc()){
	    //cenzura nazwisk
        $names = explode(" ", $a['inspiracja']);
        for($i=0;$i<count($names);$i++){
            $initial = substr($names[$i], 0, 1);
            if(ctype_upper($initial)){
                if(substr($names[$i], -1) == ",") $names[$i] = $initial.".,"; //jeśli przecinek, to zachowaj go
                    else $names[$i] = $initial.".";
            }
        }
        
        //projekty bez nazwy, czyli dodaj do nazwy spację; gdyby nie było nazwy, to nie wydrukuje spacji, bananał
        if($a['nazwa'] !== null) $a['nazwa'] = " ".$a['nazwa'];
        
        $a['inspiracja'] = str_replace(". ", ".", implode(" ", $names)); ?>
albumy['<?php echo $x; ?>']['utwory'].push("<?php echo $a['tytuł']; ?>");
piosenki['<?php echo str_replace("'", "\'", $a['tytuł']); ?>'] = new Array('album', 'nr', 'idproj', 'datanagr', 'inspir');
piosenki['<?php echo str_replace("'", "\'", $a['tytuł']); ?>']['album'] = "<?php echo $x; ?>";
piosenki['<?php echo str_replace("'", "\'", $a['tytuł']); ?>']['nr'] = "<?php echo $a['nr']; ?>";
piosenki['<?php echo str_replace("'", "\'", $a['tytuł']); ?>']['idproj'] = "<?php if($a['id'] === null) echo ($lang)?'N/D':'bd.'; else echo $a['id'].$a['nazwa']; ?>";
piosenki['<?php echo str_replace("'", "\'", $a['tytuł']); ?>']['datanagr'] = "<?php if($a['data_out'] === null) echo ($lang)?'N/D':'bd.'; else echo date('j.m.Y', strtotime($a['data_out'])); ?>";
piosenki['<?php echo str_replace("'", "\'", $a['tytuł']); ?>']['inspir'] = "<?php if($a['inspiracja'] == "") echo ($lang)?'N/D':'bd.'; else echo $a['inspiracja']; ?>";
<?php }
	$r->free_result();
}
?>
</script>