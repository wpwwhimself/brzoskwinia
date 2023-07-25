$(function(){
<?php
if(isset($_GET['a'])){?>
openAlbumInfo("<? echo $_GET['a']; ?>");
<?}
if(isset($_GET['s'])){?>
openBoth(piosenki["<? echo $_GET['s']; ?>"]['album'], "<? echo $_GET['s']; ?>");
<?}?>
});