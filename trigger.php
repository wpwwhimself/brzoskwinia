$(function(){
<?php
if(isset($_GET['a'])){?>
openAlbumInfo("<?php echo $_GET['a']; ?>");
<?php }
if(isset($_GET['s'])){?>
openBoth(piosenki["<?php echo $_GET['s']; ?>"]['album'], "<?php echo $_GET['s']; ?>");
<?php }?>
});