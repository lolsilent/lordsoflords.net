<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

if(!empty($_POST['action'])){$action=clean_post($_POST['action']);}else{$action='';}
if(!empty($_POST['steal'])){$steal=clean_post($_POST['steal']);}else{$steal='';}
if(!empty($_POST['inactive'])){$inactive=clean_int($_POST['inactive']);}else{$inactive='';}
$inactive_days=(86400*10);
$thief_time=1800;
if ($row->stealth-$current_time <= 0 and !empty($steal) and !empty($inactive) and !empty($action)){
if($oresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `timer`<$current_time-$inactive_days and `charname`!='$row->charname' and `level`<=$row->level and `id`='$inactive') ORDER BY `level` DESC LIMIT 100")){
if($pobj=mysqli_fetch_object($oresult)){mysqli_free_result($oresult);
print 'You just stole ';
switch ($steal){
case $steal == 1://gold and xp
	$stolen_xp = $pobj->xp/$row->level;
	$stolen_gold = $pobj->gold/$row->level;
	if($stolen_xp>$row->xp or $stolen_xp<$row->level or $stolen_xp<0){$stolen_xp=$row->level;}
	if($stolen_gold>($row->gold+$row->stash) or $stolen_gold<$row->level or $stolen_gold<0){$stolen_gold=$row->level;}
	if($stolen_xp>1 or $stolen_gold>0){
		mysqli_query($link, "UPDATE `$tbl_members` SET `xp`=`xp`-$stolen_xp, `gold`=`gold`-$stolen_gold WHERE `id`=$pobj->id LIMIT 1");
	$update_it.=", `xp`=".($row->xp+$stolen_xp).", `gold`=".($row->gold+$stolen_gold);
	print lint($stolen_xp).' xp and '.lint($stolen_gold).' gold';
		mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname steals ".lint($stolen_xp)." xp and ".lint($stolen_gold)." gold from $pobj->sex $pobj->charname',$current_time)");}else{print 'nothing';}
break;
case $steal == 2://stats
	$thief_time*=3;
	$stats_array=array('strength'=>'str','dexterity'=>'dex','agility'=>'agi','intelligence'=>'intel','concentration'=>'conc','contravention'=>'cont');
	$randed_stats=array_rand($stats_array);
	$stats_bool = $stats_array[$randed_stats];
	$stats_intel = round($pobj->$stats_bool/(rand(100,1000)/100));
	if($stats_intel<0 or $stats_intel>$row->level){$stats_intel=round($row->level/(rand(100,1000)/100));}
	if($stats_intel>1){
		mysqli_query($link, "UPDATE `$tbl_members` SET `$stats_bool`=`$stats_bool`-$stats_intel WHERE `id`=$pobj->id LIMIT 1");
	$update_it.=", `$stats_bool`=`$stats_bool`+$stats_intel";
	print $stats_intel.' of '.$pobj->$stats_bool.' '.$randed_stats;
	mysqli_query($link, "INSERT INTO `$tbl_steals` VALUES(NULL,'$row->server_id','$row->sex','$row->charname','$randed_stats',$stats_intel,$current_time)");}else{print 'nothing';}
break;
case $steal == 3://equipments
	$thief_time*=2;
	$equipments=array('weapon','spell','heal','helm','shield','amulet','ring','armor','belt','pants','hand','feet');
	$randed_equip=$equipments[array_rand($equipments)];
	$equip_intel = round($pobj->$randed_equip/(rand(100,1000)/100));
	if($equip_intel<0 or $equip_intel>$row->level){$equip_intel=round($row->level/(rand(100,1000)/100));}
	if($equip_intel>1){
		mysqli_query($link, "UPDATE `$tbl_members` SET `$randed_equip`=`$randed_equip`-$equip_intel WHERE `id`=$pobj->id LIMIT 1");
	$update_it.=", `$randed_equip`=`$randed_equip`+$equip_intel";
	print $equip_intel.' of '.$pobj->$randed_equip.' '.$randed_equip;
	mysqli_query($link, "INSERT INTO `$tbl_steals` VALUES(NULL,'$row->server_id','$row->sex','$row->charname','$randed_equip',$equip_intel,$current_time)");}else{print 'nothing';}
break;
case $steal == 4://stash
	$stolen_gold = $pobj->stash/$row->level;
	if($stolen_gold>($row->gold+$row->stash) or $stolen_gold<$row->level or $stolen_gold<0){$stolen_gold=$row->level;}
	if($stolen_gold>1){
		mysqli_query($link, "UPDATE `$tbl_members` SET `stash`=`stash`-$stolen_gold WHERE `id`=$pobj->id LIMIT 1");
	$update_it.=", `gold`=".($row->gold+$stolen_gold);
	print lint($stolen_gold).' gold out of the stash';
	mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname plundered $pobj->sex $pobj->charname stash for ".lint($stolen_gold)." gold',$current_time)");}else{print 'nothing';}
break;
case $steal == 5://charm
	$thief_time*=3;
	$mcresult=mysqli_query($link, "SELECT * FROM `$tbl_charms` WHERE `charname`='$row->charname' LIMIT 5");
	if (mysqli_num_rows($mcresult) < 5){mysqli_free_result($mcresult);
	if($cresult=mysqli_query($link, "SELECT * FROM `$tbl_charms` WHERE (`charname`='$pobj->charname' and `name`!='Gods Charm' and `name`!='Heavenly Charm') ORDER BY `id` ASC LIMIT 1")) {
	if($cobj=mysqli_fetch_object($cresult)){mysqli_free_result($cresult);
	mysqli_query($link, "UPDATE `$tbl_charms` SET `charname`='$row->charname' WHERE `id`=$cobj->id LIMIT 1");
	print $cobj->name.' charm';
	mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname steals a $cobj->name charm from $pobj->sex $pobj->charname',$current_time)");}else{print 'nothing';}}else{print 'nothing';}}else{print 'nothing';}
break;
default:'nothing';
}
print ' from '.$pobj->sex.' '.$pobj->charname.'.<br>';
$update_it.=", `stealth`=$current_time+$thief_time";
$row->stealth=$current_time+$thief_time;
}}
} elseif ($row->stealth-$current_time <= 0 and empty($steal) and empty($inactive) and empty($action)){
print '<form method="post" action="steal.php?sid='.$sid.'"><table width="100%">
<tr><th colspan="2">Steal from inactives</th></tr>
<tr><td width="50%">Inactive for '.($inactive_days/86400).' days</td><td><select name="inactive">';
$i=0;
if($loresult=mysqli_query($link, "SELECT `id`,`level`,`charname` FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `timer`<$current_time-$inactive_days and `charname`!='$row->charname' and `level`<=$row->level) ORDER BY `level` DESC LIMIT 100")){
while($lpobj=mysqli_fetch_object($loresult)){$i++;
print '<option value="'.$lpobj->id.'">Inactive Player</option>';
mysqli_query($link, "DELETE FROM $tbl_save WHERE `charname`='$lpobj->charname'");
}
mysqli_free_result($loresult);
}
if($i<=0){print '<option value="0">Nobody</option>';}
print '
</select></td></tr><tr><td>Go for his</td><td><select name="steal"><option value="1">Gold and Xp</option><option value="2">Stats</option><option value="3">Equipment</option><option value="4">Stash</option><option value="5">Charm</option></select></td></tr>
<tr><th colspan="2"><input type="submit" name="action" value="Steal it!"></th></tr></table></form>
';
}
print ($row->stealth-$current_time > 0?"You have to wait ".dater($row->stealth)." to recover before you can steal again.":'');
$thief_time=1800;
print '<hr>
Gold and Xp steals takes '.lint($thief_time/60).' minutes to recover.<br>
Stats steals takes '.lint($thief_time*3/60).' minutes to recover.<br>
Equipment steals takes '.lint($thief_time*2/60).' minutes to recover.<br>
Stash stealing takes '.lint($thief_time/60).' minutes to recover.<br>
Charm (can\'t steal GC and HC) steals takes '.lint($thief_time*3/60).' minutes to recover'.($row->stealth-$current_time <= 0 and $fp >= 1)?'<a href="merge.php?sid='.$sid.'">.</a>':'.'.'
<hr>';
require_once($game_footer);
?>