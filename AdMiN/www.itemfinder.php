<?php 
#!/usr/local/bin/php

function itemfinder(){
global $link,$row,$tbl_items,$item_sub,$item_kinds;

$have_items=0;

$iresult=mysqli_query($link, "SELECT `id` FROM `$tbl_items` WHERE `mid`='$row->id' ORDER BY `timer` DESC LIMIT 10");

if($iresult){
$have_items=mysqli_num_rows($iresult);
mysqli_free_result($iresult);
}

if($have_items <= 10) {
$kind_base=0;
$sub_kind=0;
$item_rand = rand(1,100);
switch ($item_rand) {
	case $item_rand <= 70:
	$kind_base=0;
	$sub_kind=rand(0,5);
	break;
	case $item_rand > 70 && $item_rand <= 80:
	$kind_base=1;
	$sub_kind=rand(6,8);
	break;
	case $item_rand > 80 && $item_rand <= 90:
	$kind_base=2;
	$sub_kind=rand(9,11);
	break;
	case $item_rand > 90:
	$kind_base=3;
	$sub_kind=rand(6,12);
	break;
}
$value_base = rand(1000,15000)/1000;
mysqli_query($link, "INSERT INTO `$tbl_items` VALUES(NULL,'$row->id','$kind_base','$sub_kind','$value_base','0')");
print 'You have just found a <b>'.ucfirst($item_sub[$sub_kind]).' '.ucfirst($item_kinds[$kind_base]).'</b> !';

}
}

?>