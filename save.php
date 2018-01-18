<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

if(!empty($_GET['action'])){$action=clean_post($_GET['action']);}else{$action='';}
if(!empty($_GET['gid'])){$gid=clean_post($_GET['gid']);}else{$gid='';}

if(!empty($action)){
	if($action == 'save'){
$saved=0;if($sresult=mysqli_query($link, "SELECT * FROM `$tbl_save` WHERE `server_id`='$row->server_id' and `charname`='$row->charname' ORDER BY `id` DESC LIMIT 5")){$saved=mysqli_num_rows($sresult);mysqli_free_result($sresult);}

if($saved<5){
mysqli_query($link, "INSERT INTO `$tbl_save` VALUES ('','$row->server_id','$row->charname',$row->level,$row->xp,$row->gold,$row->stash,$row->life,$row->str,$row->dex,$row->agi,$row->intel,$row->conc,$row->cont,$row->weapon,$row->spell,$row->heal,$row->helm,$row->shield,$row->amulet,$row->ring,$row->armor,$row->belt,$row->pants,$row->hand,$row->feet,$current_time)");
print 'Game saveD';
}else{print 'Slots are fulL';}
	}elseif($action == 'load' and !empty($gid)){
if($lresult=mysqli_query($link, "SELECT * FROM `$tbl_save` WHERE (`server_id`='$row->server_id' and `id`='$gid' and `charname`='$row->charname') ORDER BY `id` DESC LIMIT 1")){
if($lobj=mysqli_fetch_object($lresult)){
mysqli_free_result($lresult);if($row->charname == $lobj->charname){
$update_it .= ", `level`=$lobj->level, `xp`=$lobj->xp, `gold`=$lobj->gold, `stash`=$lobj->stash, `life`=$lobj->life, `str`=$lobj->str, `dex`=$lobj->dex, `agi`=$lobj->agi, `intel`=$lobj->intel, `conc`=$lobj->conc, `cont`=$lobj->cont, `weapon`=$lobj->weapon, `spell`=$lobj->spell, `heal`=$lobj->heal, `helm`=$lobj->helm, `shield`=$lobj->shield, `amulet`=$lobj->amulet, `ring`=$lobj->ring, `armor`=$lobj->armor, `belt`=$lobj->belt, `pants`=$lobj->pants, `hand`=$lobj->hand, `feet`=$lobj->feet";
print 'Game loadeD';}
}}
	}elseif($action == 'delete' and !empty($gid)){
mysqli_query($link, "DELETE FROM `$tbl_save` WHERE `server_id`='$row->server_id' and `charname`='$row->charname' and `id`='$gid' LIMIT 1");
print 'Game deleteD';
	}
}
print '
<table width="100%">
<tr><th colspan="6">Saved gameS</th></tr>
';
if($dresult=mysqli_query($link, "SELECT * FROM `$tbl_save` WHERE `charname`='$row->charname' ORDER BY `id` DESC LIMIT 5")){
print '<tr><td valign="top">Level<br>Xp<br>Gold<br>Stash<br>Life<br>Strength<br>Dexterity<br>Agility<br>Intelligence<br>Concentration<br>Contravention<br>Weapon<br>Spell<br>Heal<br>Helm<br>Shield<br>Amulet<br>Ring<br>Armor<br>Belt<br>Pants<br>Hand<br>Feet<br>Time</td>';
$i=0;while($dobj=mysqli_fetch_object($dresult)){$i++;
print '<td align="right" valign="top"'.(empty($bg)?' bgcolor="'.$colth.'"':'').'>'.lint($dobj->level).'<br>'.lint($dobj->xp).'<br>'.lint($dobj->gold).'<br>'.lint($dobj->stash).'<br>'.lint($dobj->life).'<br>'.lint($dobj->str).'<br>'.lint($dobj->dex).'<br>'.lint($dobj->agi).'<br>'.lint($dobj->intel).'<br>'.lint($dobj->conc).'<br>'.lint($dobj->cont).'<br>'.lint($dobj->weapon).'<br>'.lint($dobj->spell).'<br>'.lint($dobj->heal).'<br>'.lint($dobj->helm).'<br>'.lint($dobj->shield).'<br>'.lint($dobj->amulet).'<br>'.lint($dobj->ring).'<br>'.lint($dobj->armor).'<br>'.lint($dobj->belt).'<br>'.lint($dobj->pants).'<br>'.lint($dobj->hand).'<br>'.lint($dobj->feet).'<br>'.dater($dobj->timer).'<br><a href="save.php?sid='.$sid.'&amp;gid='.$dobj->id.'&amp;action=delete">Delete</a><br><a href="save.php?sid='.$sid.'&amp;gid='.$dobj->id.'&amp;action=load">Load</a></td>';
}
mysqli_free_result($dresult);
if($i<5){
for($i=(1+$i);$i<=5;$i++){
print '<td align="center" valign="top"'.(empty($bg)?' bgcolor="'.$colth.'"':'').'>Empty</td>';
}
}print '</tr>';}
print '
<tr><th colspan="6"><a href="save.php?sid='.$sid.'&amp;action=save">Save current gamE</a></th></tr>
</table>

<hr>Saved games are automatically deleted after a duel, transfer or a lost match by your clan in the tournaments.<hr>
';
require_once($game_footer);
?>