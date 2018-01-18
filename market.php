<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);
mysqli_query($link, "DELETE FROM `$tbl_market` WHERE `timer`<($current_time-(60*60*24*5)) LIMIT 50");

error_reporting(0);

$cobj = new stdClass;
$cobj->credits = new stdClass;
if($cdresult=mysqli_query($link, "SELECT * FROM `$tbl_credits` WHERE (`username`='$row->username' and `charname`='$row->charname') LIMIT 1")){
if($cobj=mysqli_fetch_object($cdresult)){
mysqli_free_result($cdresult);}else{$cobj->credits=0;}}

$num=0;if($cmresult=mysqli_query($link, "SELECT * FROM `$tbl_charms` WHERE `charname`='$row->charname' ORDER BY `timer` ASC LIMIT 10")){
$num=mysqli_num_rows($cmresult);
mysqli_free_result($cmresult);
}

print 'You have '.($cobj->credits>=1?lint($cobj->credits).' credits and ':'').($num).' of the 5 charm slots used.';
if(!empty($_GET['cid'])){$cid=clean_post($_GET['cid']);}else{$cid='';}
if(!empty($_GET['action'])){$action=clean_post($_GET['action']);}else{$action='';}

if(!empty($action) and !empty($cid)){
if($action == 'retract'){

mysqli_query($link, "DELETE FROM $tbl_market WHERE `cid`='$cid' and `charname`='$row->charname' LIMIT 1");

}elseif($action == 'buy' and $num<=4){


if($pmkresult=mysqli_query($link, "SELECT * FROM `$tbl_market` WHERE (`server_id`='$row->server_id' and `cid`='$cid' and `charname`!='$row->charname') LIMIT 1")){
if($pmkobj=mysqli_fetch_object($pmkresult)){
mysqli_free_result($pmkresult);
if($pcmresult=mysqli_query($link, "SELECT * FROM `$tbl_charms` WHERE `id`='$pmkobj->cid' LIMIT 1")){
if($pcmobj=mysqli_fetch_object($pcmresult)){
mysqli_free_result($pcmresult);
if($cid==$pmkobj->cid and $cid==$pcmobj->id and $row->gold>=$pmkobj->gold and $cobj->credits>=$pmkobj->credits){

mysqli_query($link, "DELETE FROM `$tbl_market` WHERE `id`='$pmkobj->id' LIMIT 1");
mysqli_query($link, "UPDATE `$tbl_charms` SET `charname`='$row->charname' WHERE `id`='$pcmobj->id' LIMIT 1");

if($pmkobj->gold >= 1){
$update_it.=", `gold`=".($row->gold-$pmkobj->gold);
mysqli_query($link, "UPDATE `$tbl_members` SET `gold`=`gold`+'$pmkobj->gold' WHERE `charname`='$pmkobj->charname' LIMIT 1");
}

if($pmkobj->credits >= 1){
mysqli_query($link, "UPDATE `$tbl_credits` SET `credits`=`credits`-'$pmkobj->credits' WHERE `charname`='$row->charname' LIMIT 1");

if($ooresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `charname`='$pmkobj->charname' LIMIT 1")){
if($oobj=mysqli_fetch_object($ooresult)){
mysqli_free_result($ooresult);
mysqli_query($link, "INSERT INTO `$tbl_credits` VALUES(NULL,'$oobj->username','$oobj->charname','$pmkobj->credits') ON DUPLICATE KEY UPDATE `credits`=`credits`+$pmkobj->credits");
}}

}

mysqli_query($link, "INSERT INTO `$tbl_zlogs` VALUES(NULL,'$row->charname','$row->charname buys Charm $pcmobj->id from $pcmobj->charname for $pmkobj->gold gold and $pmkobj->credits credits','market','$ip','$current_time')");
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname bought a charm $pcmobj->name from $pcmobj->charname for ".lint($pmkobj->gold)." gold and ".lint($pmkobj->credits)." credits','$current_time')");
print '<br><br>You bought a charm '.$pcmobj->name.' from '.$pcmobj->charname.' for '.lint($pmkobj->gold).' gold and '.lint($pmkobj->credits).' credits';
}}}}}


}}

print '<table width="100%">
<tr><th colspan="13">'.$title.' Market</th></tr>
<tr><td>#</td><td>Seller</td><td>Charm name</td><td>Str</td><td>Dex</td><td>Agi</td><td>Int</td><td>Conc</td><td>Cont</td><td>Gold</td><td>Credits</td><td>Action</td><td>#</td></tr>
';
if($mkresult=mysqli_query($link, "SELECT * FROM `$tbl_market` WHERE (`server_id`='$row->server_id' and `gold`<='$row->gold' and `credits`<='$cobj->credits') or `charname`='$row->charname' ORDER BY id DESC LIMIT 100")){$i=0;
while($mkobj=mysqli_fetch_object($mkresult)){
if($cmresult=mysqli_query($link, "SELECT * FROM `$tbl_charms` WHERE `id`='$mkobj->cid' LIMIT 1")){
if($cmobj=mysqli_fetch_object($cmresult) and $mkobj->charname == $cmobj->charname){$i++;
mysqli_free_result($cmresult);
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.$i.'</td><td>'.$mkobj->charname.'</td><td>'.$cmobj->name.'</td><td>'.$cmobj->str.'</td><td>'.$cmobj->dex.'</td><td>'.$cmobj->agi.'</td><td>'.$cmobj->intel.'</td><td>'.$cmobj->conc.'</td><td>'.$cmobj->cont.'</td><td>$'.lint($mkobj->gold).'</td><td>'.lint($mkobj->credits).'</td><td>';

if($mkobj->charname == $row->charname){

print '<a href="market.php?sid='.$sid.'&amp;cid='.$mkobj->cid.'&amp;action=retract">Retract</a><?php }elseif($num<=4){?><a href="?sid='.$sid.'&amp;cid='.$mkobj->cid.'&amp;action=buy">Buy</a>';
}else{
	print 'Full';
	}

print '</td><td>'.$i.'</td></tr>';
}else{mysqli_query($link, "DELETE FROM $tbl_market WHERE `id`='$mkobj->id' LIMIT 1");}}}
mysqli_free_result($mkresult);
}
print '</table>';
require_once($game_footer);
?>