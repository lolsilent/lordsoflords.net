<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);

if(!empty($_GET['info']) and empty($info)){$info=clean_post($_GET['info']);}
if(!empty($_POST['info']) and empty($info)){$info=clean_post($_POST['info']);}
if(empty($info)){header("Location: $root_url/login.php");exit;}

if(!empty($_GET['sid'])){require_once($game_header);}else{include_once($html_header);
$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);}

if($iresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `charname`='$info' LIMIT 1")){
if($iobj=mysqli_fetch_object($iresult)){
mysqli_free_result($iresult);

if($hresult=mysqli_query($link, "SELECT * FROM `$tbl_history` WHERE `charname`='$iobj->charname' LIMIT 1")){
if($hobj=mysqli_fetch_object($hresult)){
mysqli_free_result($hresult);

print '<table width="100%">
<tr><th colspan="2">Player information and statisticS</th></tr>
<tr><td>
Clan<br>
Race<br>
Sex<br>
Charname<hr>
Level<br>
Life<br>
Xp<br>
Gold<br>
Stash<hr>
Monsters killed<br>
Deads by monster<br>
Duels won<br>
Duels lost<br>
Total fights<br>
Total duels<hr>
Last activity<br>
First login<hr>
</td><td>
';
print (!empty($iobj->clan)?"[$iobj->clan]":'none').'<br>'.$iobj->race.'<br>'.$iobj->sex.'<br>'.$iobj->charname.(($iobj->fp-$current_time)>1?'<img src="'.$emotions_url.'/star.gif" border="0" alt="Premium member">':'').'<hr>'.lint($iobj->level).'<br>'.lint($iobj->life).'<br>'.lint($iobj->xp).'<br>'.lint($iobj->gold).'<br>'.lint($iobj->stash).'<hr>'.lint($hobj->kills).'<br>'.lint($hobj->deads).'<br>'.lint($hobj->duelsw).'<br>'.lint($hobj->duelsl).'<br>'.lint($hobj->kills+$hobj->deads).'<br>'.lint($hobj->duelsw+$hobj->duelsl).'<hr>'.($iobj->timer>1?dater($iobj->timer).' ago':'none').'<br>'.($hobj->timer>1?dater($hobj->timer).' ago':'never').'<hr>';
print '</td><tr><td colspan="2">';

if($iobj->str>$iobj->intel){
print 'More muscles than brains and slams into combat.';
}elseif($iobj->str<$iobj->intel){
print 'Very intelligent and chooses the mystical power in combat.';
}else{
print 'Fights with hand and magic.';
}

if($iobj->dex>$iobj->conc){
print 'Aims very well with a melee weapon.';
}elseif($iobj->dex<$iobj->conc){
print 'Spells are not fizzling here.';
}else{
print 'Cool minded aimer.';
}

if($iobj->agi>$iobj->cont){
print 'Defense is stronger than his magic shield.';
}elseif($iobj->agi<$iobj->cont){
print 'Magic shield is stronger than his defense.';
}else{
print 'Defending agains a weapon or spell should be no problem.';
}

if($iobj->weapon>$iobj->spell){
print 'Prefers using a weapon in combat.';
}elseif($iobj->weapon<$iobj->spell){
print 'Prefers using a spell in combat.';
}else{
print 'Uses whatever is at hand.';
}

print '</td></tr></table>';

	//EXIST OR DELETE
print '<hr>';
$uploaddir = '/home/lolnet/public_html/photos/';
$pic_name = strtolower($server).$iobj->id.'.jpg';

$picurl='https://lordsoflords.net/photos/'.$pic_name;

if(file_exists($uploaddir.$pic_name)){
		print '<img src="'.$picurl.'" border=0>';
}else{
	print 'No current photo';
}
print '<hr>';
	//EXIST OR DELETE

//ADDED STATS 16-11-2009 23:47:18
require_once($inc_races);
require_once($inc_battle);
$stats_array=array('Strength'=>'str','Dexterity'=>'dex','Agility'=>'agi','Intelligence'=>'intel','Concentration'=>'conc','Contravention'=>'cont');

$bonus=array(0,0,0,0,0,0);

if($iresult=mysqli_query($link, "SELECT * FROM `$tbl_items` WHERE (`mid`='$iobj->id' AND `sub`<='5' AND `timer`>='$current_time') ORDER BY `timer` DESC LIMIT 10")){
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

if($cmresult=mysqli_query($link, "SELECT * FROM `$tbl_charms` WHERE `charname`='$iobj->charname' and `timer`>=$current_time LIMIT 5")){
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

if(!in_array($iobj->race,array_keys($races_array))){$iobj->race='Human';}

$tot_stats=$iobj->str+$iobj->dex+$iobj->agi+$iobj->intel+$iobj->conc+$iobj->cont;
$base_attack=($iobj->str/$tot_stats)*$races_array[$iobj->race][0];
$base_defend=($iobj->agi/$tot_stats)*$races_array[$iobj->race][1]+($iobj->armor+$iobj->helm+$iobj->shield+$iobj->belt+$iobj->pants+$iobj->hand+$iobj->feet);
$base_magic=($iobj->intel/$tot_stats)*$races_array[$iobj->race][2];
if($tot_stats<=0){$tot_stats=100;}
if($base_attack<=0){$base_attack=5;}
if($base_defend<=0){$base_defend=5;}
if($base_magic<=0){$base_magic=5;}

$iobj->min_wd=round($iobj->str*(1+$base_attack+$iobj->hand+$iobj->weapon));
$iobj->min_spell=round($iobj->intel*(1+$iobj->ring+$base_magic+$iobj->spell));
$iobj->min_heal=round($iobj->intel*(1+$iobj->amulet+$base_magic+$iobj->heal));
$iobj->min_shield=round($iobj->cont*(1+$iobj->ring+$iobj->amulet+$base_magic));
$iobj->min_defense=round($iobj->agi*(1+$iobj->shield+$base_defend));
$iobj->max_ar=round($iobj->dex*(1+$base_attack+$iobj->feet+$iobj->level+$races_array[$iobj->race][1]));
$iobj->max_mr=round($iobj->conc*(1+$base_magic+$iobj->belt+$iobj->level+$races_array[$iobj->race][2]));

$bsbonus=array(0,0,0,0,0,0,0);
if($iresult=mysqli_query($link, "SELECT * FROM `$tbl_items` WHERE (`mid`='$iobj->id' AND `sub`>='6' AND `timer`>='$current_time') ORDER BY `timer` DESC LIMIT 10")){
while($irow=mysqli_fetch_object($iresult)){
	switch ($irow->sub) {
		case 6:
		$bsbonus[0]+=$irow->value;
		$iobj->min_wd = ($iobj->min_wd/100)*(100+$irow->value);
		break;
		case 7:
		$bsbonus[1]+=$irow->value;
		$iobj->max_ar = ($iobj->max_ar/100)*(100+$irow->value);
		break;
		case 8:
		$bsbonus[2]+=$irow->value;
		$iobj->min_defense = ($iobj->min_defense/100)*(100+$irow->value);
		break;

		case 9:
		$bsbonus[3]+=$irow->value;
		$iobj->min_spell = ($iobj->min_spell/100)*(100+$irow->value);
		break;
		case 10:
		$bsbonus[4]+=$irow->value;
		$iobj->max_mr = ($iobj->max_mr/100)*(100+$irow->value);
		break;
		case 11:
		$bsbonus[5]+=$irow->value;
		$iobj->min_shield = ($iobj->min_shield/100)*(100+$irow->value);
		break;

		case 12:
		$bsbonus[6]+=$irow->value;
		$iobj->min_heal = ($iobj->min_heal/100)*(100+$irow->value);
		break;
	}
}
mysqli_free_result($iresult);
}



$iobj->max_wd=round($iobj->min_wd*2.55);
$iobj->max_spell=round($iobj->min_spell*2.55);
$iobj->max_heal=round($iobj->min_heal*2.55);
$iobj->max_shield=round($iobj->min_shield*2.55);
$iobj->max_defense=round($iobj->min_defense*2.55);
$iobj->min_ar=round($iobj->max_ar/2.55);
$iobj->min_mr=round($iobj->max_mr/2.55);
$iobj->thievery=round((1+($iobj->agi/$tot_stats))*$races_array[$iobj->race][3],2);



print '
<table width="100%"><tr>
<th colspan="4">'.$iobj->sex.' '.$iobj->charname.' Natural Battlefields Stats</th></tr>
<tr>
<td><br>Weapon damage<br>Attack spell<br>Heal spell<br>Magic shield<br>Defense<br>Attack Rating<br>Magic Rating</td>
<td>bonus<br>'.$bsbonus[0].'%<br>'.$bsbonus[3].'%<br>'.$bsbonus[6].'%<br>'.$bsbonus[5].'%<br>'.$bsbonus[2].'%<br>'.$bsbonus[1].'%<br>'.$bsbonus[4].'%<br></td>
<td>min<br>'.lint($iobj->min_wd).'<br>'.lint($iobj->min_spell).'<br>'.lint($iobj->min_heal).'<br>'.lint($iobj->min_shield).'<br>'.lint($iobj->min_defense).'<br>'.lint($iobj->min_ar).'<br>'.lint($iobj->min_mr).'</td>
<td>max<br>'.lint($iobj->max_wd).'<br>'.lint($iobj->max_spell).'<br>'.lint($iobj->max_heal).'<br>'.lint($iobj->max_shield).'<br>'.lint($iobj->max_defense).'<br>'.lint($iobj->max_ar).'<br>'.lint($iobj->max_mr).'</td>
</tr></table>';

//ADDED STATS 16-11-2009 23:47:18


//DEBLAB ONLY ADDED 03-08-07 01:41
$tbl_pets='lol_pets';
if($petres=mysqli_query($link, "SELECT * FROM `$tbl_pets` WHERE `charname`='$iobj->charname' ORDER BY `age` ASC LIMIT 1")){

if(mysqli_num_rows($petres) >= 1){
if($petobj=mysqli_fetch_object($petres)){
mysqli_free_result($petres);

print '<table width=100%>';

print
'<tr bgcolor="'.$colth.'"><td>Pet Name</td><td>'.$petobj->petname.'</td></tr>
<tr><td>Race</td><td>'.$petobj->petrace.'</td></tr>'.
'<tr bgcolor="'.$colth.'"><td>Level</td><td>'.lint($petobj->level).'</td></tr>'.
'<tr><td>XP</td><td>'.lint($petobj->xp).'</td></tr>'.
'<tr bgcolor="'.$colth.'"><td>Strength</td><td>'.lint($petobj->str).'</td></tr>'.
'<tr><td>Dexterity</td><td>'.lint($petobj->dex).'</td></tr>'.
'<tr bgcolor="'.$colth.'"><td>Agility</td><td>'.lint($petobj->agi).'</td></tr>'.
'<tr><td>Intelligence</td><td>'.lint($petobj->intel).'</td></tr>'.
'<tr bgcolor="'.$colth.'"><td>Concentration</td><td>'.lint($petobj->conc).'</td></tr>'.
'<tr><td>Contravention</td><td>'.lint($petobj->cont).'</td></tr>'.

'<tr bgcolor="'.$colth.'"><td>Attack Damage</td><td>'.lint($petobj->level*($petobj->str+$petobj->dex+$petobj->agi)).'</td></tr>'.
'<tr><td>Magic Damage</td><td>'.lint($petobj->level*($petobj->intel+$petobj->conc+$petobj->cont)).'</td></tr>';

print '</table>';

}
}else{print 'Does not have a pet!<hr>';}
}
//DEBLAB ONLY ADDED 03-08-07 01:41

}else{print 'Player didn\'t start playing yet.';}}}else{print 'Can\'t find player.';}}

if(!empty($_GET['sid'])){require_once($game_footer);}else{mysqli_close($link);include_once($html_footer);}
?>