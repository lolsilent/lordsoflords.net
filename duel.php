<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($inc_races);
require_once($inc_battle);
require_once($game_header);

if(!empty($_GET['accept'])){$accept=clean_post($_GET['accept']);}else{$accept='';}

if($dresult=mysqli_query($link, "SELECT * FROM `$tbl_duel` WHERE `id`=$accept and `opponent`='$row->charname' LIMIT 1")){
if($dobj=mysqli_fetch_object($dresult)){
mysqli_free_result($dresult);
mysqli_query($link, "DELETE FROM `$tbl_duel` WHERE `id`=$dobj->id and `opponent`='$row->charname' LIMIT 1");
if($oppresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `server_id`='$row->server_id' and `charname`='$dobj->challenger' LIMIT 1")){
if($opp=mysqli_fetch_object($oppresult)){
mysqli_free_result($oppresult);

$row=battlestats($row);
$opp=battlestats($opp);

print '<table width="100%">
<tr><th colspan="5">'.$row->sex.' '.$row->charname.' vs '.$opp->sex.' '.$opp->charname.'</th></tr>
<tr>
<td><br>Weapon damage<br>Attack spell<br>Heal spell<br>Magic shield<br>Defense<br>Attack Rating<br>Magic Rating</td>
<td>min<br>'.lint($row->min_wd).'<br>'.lint($row->min_spell).'<br>'.lint($row->min_heal).'<br>'.lint($row->min_shield).'<br>'.lint($row->min_defense).'<br>'.lint($row->min_ar).'<br>'.lint($row->min_mr).'</td>
<td>max<br>'.lint($row->max_wd).'<br>'.lint($row->max_spell).'<br>'.lint($row->max_heal).'<br>'.lint($row->max_shield).'<br>'.lint($row->max_defense).'<br>'.lint($row->max_ar).'<br>'.lint($row->max_mr).'</td>
<td>min<br>'.lint($opp->min_wd).'<br>'.lint($opp->min_spell).'<br>'.lint($opp->min_heal).'<br>'.lint($opp->min_shield).'<br>'.lint($opp->min_defense).'<br>'.lint($opp->min_ar).'<br>'.lint($opp->min_mr).'</td>
<td>max<br>'.lint($opp->max_wd).'<br>'.lint($opp->max_spell).'<br>'.lint($opp->max_heal).'<br>'.lint($opp->max_shield).'<br>'.lint($opp->max_defense).'<br>'.lint($opp->max_ar).'<br>'.lint($opp->max_mr).'</td>
</tr></table>
';
$battles=0;

if($dobj->kind == 0){
while($row->life>=1 and $opp->life>=1 and $battles<5){
$battles++;print "<b>Round $battles</b><br>";
if($row->max_wd>$row->max_spell){
$opp->life=weapon($row,$opp);if($row->life<=0 or $opp->life<=0){break;}
$row->life=weapon($opp,$row);if($row->life<=0 or $opp->life<=0){break;}
$opp->life=magic($row,$opp);if($row->life<=0 or $opp->life<=0){break;}
$row->life=magic($opp,$row);if($row->life<=0 or $opp->life<=0){break;}
$opp->life=heal($row,$opp);if($row->life<=0 or $opp->life<=0){break;}
$row->life=heal($opp,$row);if($row->life<=0 or $opp->life<=0){break;}
}else{
$opp->life=magic($row,$opp);if($row->life<=0 or $opp->life<=0){break;}
$row->life=magic($opp,$row);if($row->life<=0 or $opp->life<=0){break;}
$opp->life=weapon($row,$opp);if($row->life<=0 or $opp->life<=0){break;}
$row->life=weapon($opp,$row);if($row->life<=0 or $opp->life<=0){break;}
$opp->life=heal($row,$opp);if($row->life<=0 or $opp->life<=0){break;}
$row->life=heal($opp,$row);if($row->life<=0 or $opp->life<=0){break;}
}
}//while
}elseif($dobj->kind == 1){
while($row->life>=1 and $opp->life>=1 and $battles<5){
$battles++;print "<b>Round $battles</b><br>";
$opp->life=weapon($row,$opp);if($row->life<=0 or $opp->life<=0){break;}
$row->life=weapon($opp,$row);if($row->life<=0 or $opp->life<=0){break;}
$opp->life=heal($row,$opp);if($row->life<=0 or $opp->life<=0){break;}
$row->life=heal($opp,$row);if($row->life<=0 or $opp->life<=0){break;}
}//while
}elseif($dobj->kind == 2){
while($row->life>=0 and $opp->life>=0 and $battles<5){
$battles++;print "<b>Round $battles</b><br>";
$opp->life=magic($row,$opp);if($row->life<=0 or $opp->life<=0){break;}
$row->life=magic($opp,$row);if($row->life<=0 or $opp->life<=0){break;}
$opp->life=heal($row,$opp);if($row->life<=0 or $opp->life<=0){break;}
$row->life=heal($opp,$row);if($row->life<=0 or $opp->life<=0){break;}
}//while
}elseif($dobj->kind == 3){
if($row->str>$opp->str){
$opp->life=0; print $row->sex.' '.$row->charname.' is the strongest!<br>';
}elseif($row->str<$opp->str){
$row->life=0; print $opp->sex.' '.$opp->charname.' is the strongest!<br>';
}
}elseif($dobj->kind == 4){
if($row->intel>$opp->intel){
$opp->life=0; print $row->sex.' '.$row->charname.' is the most intelligence!<br>';
}elseif($row->intel<$opp->intel){
$row->life=0; print $opp->sex.' '.$opp->charname.' is the most intelligence!<br>';
}
}

if($opp->life<=0 and $row->life>=1){
$win_xp=round($opp->xp/100);
$win_gold=round($opp->gold/100);

/*if($win_xp>$row->xp or $win_xp<$row->level){$win_xp=$row->level;}
if($win_gold>$row->gold or $win_gold<$row->level){$win_gold=$row->level;}*/

if($win_xp<=0 or $win_xp>=$opp->xp){$win_xp=1;}
if($win_gold<=0 or $win_xp>=$opp->gold){$win_gold=1;}

print 'You have slain '.$opp->sex.' '.$opp->charname.'. You gain '.lint($win_xp).' xp and '.lint($win_gold).' gold.';

$update_it.=", `xp`=".($row->xp+$win_xp).", `gold`=".($row->gold+$win_gold);

mysqli_query($link, "UPDATE `$tbl_history` SET `duelsw`=`duelsw`+1 WHERE `charname`='$row->charname' LIMIT 1");
mysqli_query($link, "UPDATE `$tbl_members` SET `xp`=".($opp->xp-$win_xp).", `gold`=".($opp->gold-$win_gold)." WHERE `id`=$opp->id LIMIT 1");
mysqli_query($link, "UPDATE `$tbl_history` SET `duelsl`=`duelsl`+1 WHERE `charname`='$opp->charname' LIMIT 1");
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES ('','$row->server_id','2','$row->sex $row->charname gains ".lint($win_xp)." xp and ".lint($win_gold)." gold from $opp->sex $opp->charname',$current_time)");
/*
$multi_query="UPDATE `$tbl_history` SET `duelsw`=`duelsw`+1 WHERE `charname`='$row->charname' LIMIT 1;";
$multi_query.="UPDATE `$tbl_members` SET `xp`=".($opp->xp-$win_xp).", `gold`=".($opp->gold-$win_gold)." WHERE `id`=$opp->id LIMIT 1;";
$multi_query.="UPDATE `$tbl_history` SET `duelsl`=`duelsl`+1 WHERE `charname`='$opp->charname' LIMIT 1;";
$multi_query.="INSERT INTO `$tbl_papers` VALUES ('','$row->server_id','2','$row->sex $row->charname gains ".lint($win_xp)." xp and ".lint($win_gold)." gold from $opp->sex $opp->charname',$current_time);";
*/
}elseif($row->life<=0 and $opp->life>=1){

$win_xp=round($row->xp/100);
$win_gold=round($row->gold/100);

/*if($win_xp>$opp->xp or $win_xp<$opp->level){$win_xp=$opp->level;}
if($win_gold>$opp->gold or $win_gold<$opp->level){$win_gold=$opp->level;}*/

if($win_xp<=0 or $win_xp>=$row->xp){$win_xp=1;}
if($win_gold<=0 or $win_xp>=$row->gold){$win_gold=1;}

print 'You have been slain by '.$opp->sex.' '.$opp->charname.'. You lose '.lint($win_xp).' xp and '.lint($win_gold).' gold.';

$update_it.=", `xp`=".($row->xp-$win_xp).", `gold`=".($row->gold-$win_gold);

mysqli_query($link, "UPDATE `$tbl_history` SET `duelsl`=`duelsl`+1 WHERE `charname`='$row->charname' LIMIT 1");
mysqli_query($link, "UPDATE `$tbl_members` SET `xp`=".($opp->xp+$win_xp).", `gold`=".($opp->gold+$win_gold)." WHERE `id`=$opp->id LIMIT 1");
mysqli_query($link, "UPDATE `$tbl_history` SET `duelsw`=`duelsw`+1 WHERE `charname`='$opp->charname' LIMIT 1");
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES ('','$row->server_id','2','$opp->sex $opp->charname gains ".lint($win_xp)." xp and ".lint($win_gold)." gold from $row->sex $row->charname',$current_time)");
/*
$multi_query="UPDATE `$tbl_history` SET `duelsl`=`duelsl`+1 WHERE `charname`='$row->charname' LIMIT 1;";
$multi_query.="UPDATE `$tbl_members` SET `xp`=".($opp->xp+$win_xp).", `gold`=".($opp->gold+$win_gold)." WHERE `id`=$opp->id LIMIT 1;";
$multi_query.="UPDATE `$tbl_history` SET `duelsw`=`duelsw`+1 WHERE `charname`='$opp->charname' LIMIT 1;";
$multi_query.="INSERT INTO `$tbl_papers` VALUES ('','$row->server_id','2','$opp->sex $opp->charname gains ".lint($win_xp)." xp and ".lint($win_gold)." gold from $row->sex $row->charname',$current_time);";
*/
}else{
print 'The battle tied.';
}


mysqli_query($link, "INSERT INTO `$tbl_index` VALUES(NULL,'".date('m d Y')."',1,$current_time) ON DUPLICATE KEY UPDATE `fights`=`fights`+1");
mysqli_query($link, "DELETE FROM `$tbl_save` WHERE `charname`='$row->charname' LIMIT 10");
mysqli_query($link, "DELETE FROM `$tbl_save` WHERE `charname`='$opp->charname' LIMIT 10");
/*
if(!empty($multi_query)){
$multi_query.="INSERT INTO `$tbl_index` VALUES(NULL,'".date('m d Y')."',1,$current_time) ON DUPLICATE KEY UPDATE `fights`=`fights`+1;";
$multi_query.="DELETE FROM `$tbl_save` WHERE `charname`='$row->charname' LIMIT 10;";
$multi_query.="DELETE FROM `$tbl_save` WHERE `charname`='$opp->charname' LIMIT 10";
mysqli_multi_query($link, $multi_query);
}
*/
}else{print 'Looking for something?';}}}else{print 'Looking for something?';}}
require_once($game_footer);
?>
