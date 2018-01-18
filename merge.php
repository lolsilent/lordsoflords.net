<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);
print '<p><b><h2>YOU FOUND A SECRET IN THE GAME!</h2><br>THE ONE EMERGER!<br>It will merge your char with a chosen inactive char and the inactive player will die!</b></p>';

if($fp >= 1){
if(!empty($_POST['action'])){$action=clean_post($_POST['action']);}else{$action='';}
if(!empty($_POST['inactive'])){$inactive=clean_int($_POST['inactive']);}else{$inactive='';}

$inactive_days=86400*100;
$thief_time=1800*3.5;

if ($row->stealth-$current_time <= 0 and !empty($inactive) and !empty($action)){
if($oresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `timer`<$current_time-$inactive_days and `charname`!='$row->charname' and `level`<=$row->level and `id`='$inactive') ORDER BY `level` DESC LIMIT 100")){
if($pobj=mysqli_fetch_object($oresult)){
mysqli_free_result($oresult);
if($pobj->xp <= 1000){$pobj->xp=1000;}
if($pobj->gold <= 1000){$pobj->gold=1000;}
if($pobj->stash <= 1000){$pobj->stash=1000;}

print '<b>Merging started with '.$pobj->sex.' '.$pobj->charname.'.</b>';
$update_it .= ", `xp`=`xp`+$pobj->xp, `gold`=`gold`+$pobj->gold, `stash`=`stash`+$pobj->stash, `life`=`life`+$pobj->life, `str`=`str`+$pobj->str, `dex`=`dex`+$pobj->dex, `agi`=`agi`+$pobj->agi, `intel`=`intel`+$pobj->intel, `conc`=`conc`+$pobj->conc, `cont`=`cont`+$pobj->cont, `weapon`=`weapon`+$pobj->weapon, `spell`=`spell`+$pobj->spell, `heal`=`heal`+$pobj->heal, `helm`=`helm`+$pobj->helm, `shield`=`shield`+$pobj->shield, `amulet`=`amulet`+$pobj->amulet, `ring`=`ring`+$pobj->ring, `armor`=`armor`+$pobj->armor, `belt`=`belt`+$pobj->belt, `pants`=`pants`+$pobj->pants, `hand`=`hand`+$pobj->hand, `feet`=`feet`+$pobj->feet, `stealth`=$current_time+$thief_time";

$total_stats = $pobj->str+$pobj->dex+$pobj->agi+$pobj->intel+$pobj->conc+$pobj->cont;
$total_items = $pobj->weapon+$pobj->spell+$pobj->heal+$pobj->helm+$pobj->shield+$pobj->amulet+$pobj->ring+$pobj->armor+$pobj->belt+$pobj->pants+$pobj->hand+$pobj->feet;
	print '<br>Total stats '.lint($total_stats).'.<br>Total items '.lint($total_items).'.<br>Total xp '.lint($pobj->xp).' XP.<br>Total gold $'.lint($pobj->gold+$pobj->stash).'.<br>';

mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES ('','$row->server_id','3','$row->sex $row->charname cast the spell <a title=\"Total stats ".lint($total_stats).".
Total items ".lint($total_items).".
Total xp ".lint($pobj->xp)." XP.
Total gold $".lint($pobj->gold+$pobj->stash)." gold.\">emerge</a> on $pobj->sex $pobj->charname',$current_time)");

foreach ($table_names as $tables) {
mysqli_query($link, "DELETE FROM `$tables` WHERE `charname`='$pobj->charname'");
}

$row->stealth=$current_time+$thief_time;
}else{print 'Someone just emerged with this player, before you had a chance to do so.';}
}
} elseif ($row->stealth-$current_time <= 0 and empty($inactive) and empty($action)){
print '<form method="post" action="merge.php?sid='.$sid.'"><table width="100%">
<tr><th colspan="3">Merge with inactives</th></tr>
<tr><td>Inactive for '.($inactive_days/86400).' days</td><td><select name="inactive">';
$i=0;
if($loresult=mysqli_query($link, "SELECT `id` FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `timer`<$current_time-$inactive_days and `charname`!='$row->charname' and `level`<= $row->level) ORDER BY `level` DESC LIMIT 100")){
while($lpobj=mysqli_fetch_object($loresult)){$i++;
print '<option value="'.$lpobj->id.'">Inactive Player</option>';
}
mysqli_free_result($loresult);
}
if($i<=0){print '<option value="0">Nobody</option>';}
print '
</select></td><td><input type="submit" name="action" value="EMERGE TO BE THE ONE!"></td></tr></table></form>
';
}
print $row->stealth-$current_time > 0?"You have to wait ".dater($row->stealth)." to recover before you can merge again.":'';
$thief_time=1800*3.5;
print '<hr>
Merging takes '.lint($thief_time/60).' minutes to recover.<br>
<hr>';
}else{
print 'Merging is Freeplayers only!';
}
require_once($game_footer);
?>