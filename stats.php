<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_races);
require_once($inc_battle);
require_once($inc_functions);
require_once($game_header);

if(!empty($_GET['stats'])){$stats=clean_post($_GET['stats']);}

if(!empty($_POST['stats'])){$stats=clean_post($_POST['stats']);}else{if(!isset($stats)) {$stats='';}}

$stats_array=array('Strength'=>'str','Dexterity'=>'dex','Agility'=>'agi','Intelligence'=>'intel','Concentration'=>'conc','Contravention'=>'cont');
$next_level=next_level();

if(!empty($stats) and $row->xp >= $next_level){
	if(array_key_exists($stats,$stats_array)){
if($row->level>1000){$amount=round($row->level/1000);}else{$amount=1;}
if($fp>0 or $row->level>100){$amount*=5;}

$update_it.=", `".$stats_array[$stats]."`=".$row->$stats_array[$stats]."+".($amount*5).", `level`=$row->level+$amount, `life`=".(($row->level+$amount)*150);
print '<b>You leveled up '.lint($amount).' levels!</b>';
$row->$stats_array[$stats]+=($amount*5);$row->level+=$amount;
$next_level=next_level();
	}
}



print '<table width="100%"><tr>
<th>Stats</th><th>Natural</th><th>Bonus</th><th>Charmed</th></tr>
<tr><td nowrap>Strength<br>Dexterity<br>Agility<br>Intelligence<br>Concentration<br>Contravention</td>
<td>'.lint($row->str).'<br>'.lint($row->dex).'<br>'.lint($row->agi).'<br>'.lint($row->intel).'<br>'.lint($row->conc).'<br>'.lint($row->cont).'</td>';

$bonus=array(0,0,0,0,0,0);

if($iresult=mysqli_query($link, "SELECT * FROM `$tbl_items` WHERE (`mid`='$row->id' AND `sub`<='5' AND `timer`>='$current_time') ORDER BY `timer` DESC LIMIT 10")){
while($irow=mysqli_fetch_object($iresult)){
	switch ($irow->sub) {
		case 0:
		$bonus[0]+=$irow->value;
		break;
		case 1:
		$bonus[1]+=$irow->value;
		break;
		case 2:
		$bonus[2]+=$irow->value;
		break;
		case 3:
		$bonus[3]+=$irow->value;
		break;
		case 4:
		$bonus[4]+=$irow->value;
		break;
		case 5:
		$bonus[5]+=$irow->value;
		break;
	}
}
mysqli_free_result($iresult);
}

if($cmresult=mysqli_query($link, "SELECT * FROM `$tbl_charms` WHERE `charname`='$row->charname' and `timer`>=$current_time LIMIT 5")){
if(mysqli_num_rows($cmresult)){
while($cmobj=mysqli_fetch_object($cmresult)){
if($cmobj->timer>time()){
if($cmobj->str){$bonus[0]+=$cmobj->str;}
if($cmobj->dex){$bonus[1]+=$cmobj->dex;}
if($cmobj->agi){$bonus[2]+=$cmobj->agi;}
if($cmobj->intel){$bonus[3]+=$cmobj->intel;}
if($cmobj->conc){$bonus[4]+=$cmobj->conc;}
if($cmobj->cont){$bonus[5]+=$cmobj->cont;}
}
}
mysqli_free_result($cmresult);
}
}

print '<td>';
foreach($bonus as $val){
print '+'.round($val).'%<br>';
}
print '</td><td>'.lint($row->str+(($row->str/100)*$bonus[0]))."<br>".lint($row->dex+(($row->dex/100)*$bonus[1]))."<br>".lint($row->agi+(($row->agi/100)*$bonus[2]))."<br>".lint($row->intel+(($row->intel/100)*$bonus[3]))."<br>".lint($row->conc+(($row->conc/100)*$bonus[4]))."<br>".lint($row->cont+(($row->cont/100)*$bonus[5])).'</td></tr></table>';

if(!in_array($row->race,array_keys($races_array))){$row->race='Human';}

$tot_stats=$row->str+$row->dex+$row->agi+$row->intel+$row->conc+$row->cont;
$base_attack=($row->str/$tot_stats)*$races_array[$row->race][0];
$base_defend=($row->agi/$tot_stats)*$races_array[$row->race][1]+($row->armor+$row->helm+$row->shield+$row->belt+$row->pants+$row->hand+$row->feet);
$base_magic=($row->intel/$tot_stats)*$races_array[$row->race][2];
if($tot_stats<=0){$tot_stats=100;}
if($base_attack<=0){$base_attack=5;}
if($base_defend<=0){$base_defend=5;}
if($base_magic<=0){$base_magic=5;}

$row->min_wd=round($row->str*(1+$base_attack+$row->hand+$row->weapon));
$row->min_spell=round($row->intel*(1+$row->ring+$base_magic+$row->spell));
$row->min_heal=round($row->intel*(1+$row->amulet+$base_magic+$row->heal));
$row->min_shield=round($row->cont*(1+$row->ring+$row->amulet+$base_magic));
$row->min_defense=round($row->agi*(1+$row->shield+$base_defend));
$row->max_ar=round($row->dex*(1+$base_attack+$row->feet+$row->level+$races_array[$row->race][1]));
$row->max_mr=round($row->conc*(1+$base_magic+$row->belt+$row->level+$races_array[$row->race][2]));

$bsbonus=array(0,0,0,0,0,0,0);
if($iresult=mysqli_query($link, "SELECT * FROM `$tbl_items` WHERE (`mid`='$row->id' AND `sub`>='6' AND `timer`>='$current_time') ORDER BY `timer` DESC LIMIT 10")){
while($irow=mysqli_fetch_object($iresult)){
	switch ($irow->sub) {
		case 6:
		$bsbonus[0]+=$irow->value;
		$row->min_wd = ($row->min_wd/100)*(100+$irow->value);
		break;
		case 7:
		$bsbonus[1]+=$irow->value;
		$row->max_ar = ($row->max_ar/100)*(100+$irow->value);
		break;
		case 8:
		$bsbonus[2]+=$irow->value;
		$row->min_defense = ($row->min_defense/100)*(100+$irow->value);
		break;

		case 9:
		$bsbonus[3]+=$irow->value;
		$row->min_spell = ($row->min_spell/100)*(100+$irow->value);
		break;
		case 10:
		$bsbonus[4]+=$irow->value;
		$row->max_mr = ($row->max_mr/100)*(100+$irow->value);
		break;
		case 11:
		$bsbonus[5]+=$irow->value;
		$row->min_shield = ($row->min_shield/100)*(100+$irow->value);
		break;

		case 12:
		$bsbonus[6]+=$irow->value;
		$row->min_heal = ($row->min_heal/100)*(100+$irow->value);
		break;
	}
}
mysqli_free_result($iresult);
}



$row->max_wd=round($row->min_wd*2.55);
$row->max_spell=round($row->min_spell*2.55);
$row->max_heal=round($row->min_heal*2.55);
$row->max_shield=round($row->min_shield*2.55);
$row->max_defense=round($row->min_defense*2.55);
$row->min_ar=round($row->max_ar/2.55);
$row->min_mr=round($row->max_mr/2.55);
$row->thievery=round((1+($row->agi/$tot_stats))*$races_array[$row->race][3],2);



print '
<table width="100%"><tr>
<th colspan="4">'.$row->sex.' '.$row->charname.' Natural Battlefields Stats</th></tr>
<tr>
<td><br>Weapon damage<br>Attack spell<br>Heal spell<br>Magic shield<br>Defense<br>Attack Rating<br>Magic Rating</td>
<td>bonus<br>'.$bsbonus[0].'%<br>'.$bsbonus[3].'%<br>'.$bsbonus[6].'%<br>'.$bsbonus[5].'%<br>'.$bsbonus[2].'%<br>'.$bsbonus[1].'%<br>'.$bsbonus[4].'%<br>'.'</td>
<td>min<br>'.lint($row->min_wd).'<br>'.lint($row->min_spell).'<br>'.lint($row->min_heal).'<br>'.lint($row->min_shield).'<br>'.lint($row->min_defense).'<br>'.lint($row->min_ar).'<br>'.lint($row->min_mr).'</td>
<td>max<br>'.lint($row->max_wd).'<br>'.lint($row->max_spell).'<br>'.lint($row->max_heal).'<br>'.lint($row->max_shield).'<br>'.lint($row->max_defense).'<br>'.lint($row->max_ar).'<br>'.lint($row->max_mr).'</td>
</tr></table>
';
if($row->xp >= $next_level){
print '
<b>Congratulations you have experience for the next level.</b><br>
<table width="350" align="center" border="0">
<form method="post">
<input type="hidden" name="sid" value="'.$sid.'">
<tr><th colspan=2>What do you wish to improve?</th>
<tr>
<td align="center" width="50%"><input type="submit" name="stats" value="Strength" style="width:100%;"></td>
<td align="center" width="50%"><input type="submit" name="stats" value="Intelligence" style="width:100%;"></td>
</tr><tr>
<td align="center" width="50%"><input type="submit" name="stats" value="Dexterity" style="width:100%;"></td>
<td align="center" width="50%"><input type="submit" name="stats" value="Concentration" style="width:100%;"></td>
</tr><tr>
<td align="center" width="50%"><input type="submit" name="stats" value="Agility" style="width:100%;"></td>
<td align="center" width="50%"><input type="submit" name="stats" value="Contravention" style="width:100%;"></td>
</td></tr></form></table>
';
}else{
	print 'Next level '.lint($next_level).' XP.<br>You need <b>'.lint($next_level-$row->xp).'</b> XP for the next level.';
}

if($fp>=1 AND !empty($stats) AND $row->xp >= $next_level){
print '<br>Freeplay auto level!</br>';
print '<meta https-equiv="REFRESH" content="1 ; url='.$_SERVER['PHP_SELF'].'?sid='.$sid.'&stats='.$stats.'" target="'.$server.'_main">';
}
require_once($game_footer);
?>