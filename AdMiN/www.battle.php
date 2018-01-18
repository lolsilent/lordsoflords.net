<?php 
#!/usr/local/bin/php

function myfriend($xp,$gold,$friend){
global $link,$tbl_members,$row;

if($fresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `charname`='$friend' LIMIT 100")){
if($fobj=mysqli_fetch_object($fresult)){
mysqli_free_result($fresult);
$xp/=100;$gold/=100;
if($xp>$fobj->xp or $xp<$fobj->level){$xp=$fobj->level;}
if($gold>$fobj->gold or $gold<$fobj->level){$gold=$fobj->level;}

if($row->friend !== $fobj->friend){
mysqli_query($link, "UPDATE `$tbl_members` SET `xp`=`xp`+$xp,`gold`=`gold`+$gold WHERE `id`=$fobj->id LIMIT 1");
}

}}

}

function battlestats($row){
global $link,$races_array,$tbl_charms,$tbl_items,$tbl_members,$current_time;

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

if(!empty($row->clan)){
	if($mresult=mysqli_query($link, "SELECT `sex`,`charname`,`str`,`dex`,`agi`,`intel`,`conc`,`cont` FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `clan`='$row->clan' and `level` <= '$row->level' and `id`!='$row->id') LIMIT 5")){
while($mrow=mysqli_fetch_object($mresult)){
if($mrow->str >= 10){$row->str+=$mrow->str/10;}
if($mrow->dex >= 10){$row->dex+=$mrow->dex/10;}
if($mrow->agi >= 10){$row->agi+=$mrow->agi/10;}
if($mrow->intel >= 10){$row->intel+=$mrow->intel/10;}
if($mrow->conc >= 10){$row->conc+=$mrow->conc/10;}
if($mrow->cont >= 10){$row->cont+=$mrow->cont/10;}
}
mysqli_free_result($mresult);
	}
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

if(array_sum($bonus)>=1){
if($bonus[0]){$row->str+=($row->str/100)*$bonus[0];}
if($bonus[1]){$row->dex+=($row->dex/100)*$bonus[1];}
if($bonus[2]){$row->agi+=($row->agi/100)*$bonus[2];}
if($bonus[3]){$row->intel+=($row->intel/100)*$bonus[3];}
if($bonus[4]){$row->conc+=($row->conc/100)*$bonus[4];}
if($bonus[5]){$row->cont+=($row->cont/100)*$bonus[5];}
}

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

return $row;
}
//battlestats

//weapon
function weapon($row,$mon){
print '<font color="#FF3333">';

$row->max_ar/=(rand(100,255)/100);
$mon->max_ar/=(rand(100,255)/100);

if($row->max_ar<$row->min_ar){$row->max_ar=$row->min_ar;}
if($mon->max_ar<$mon->min_ar){$mon->max_ar=$mon->min_ar;}

if($row->max_ar>=$mon->max_ar){
$row->max_wd/=(rand(100,255)/100);
$mon->max_defense/=(rand(100,255)/100);
if($row->max_wd<$row->min_wd){$row->max_wd=$row->min_wd;}
if($mon->max_defense<$mon->min_defense){$mon->max_defense=$mon->min_defense;}

if($row->max_wd<=0){
print $mon->sex.' '.$mon->charname.' blocks the strike!';
}else{
$mon->life-=($row->max_wd-$mon->max_defense);
print $row->sex.' '.$row->charname;
print ' hits for '. number_format($row->max_wd).'! '. $mon->sex.' '.$mon->charname.' blocks for '. number_format($mon->max_defense).'.';
}
}else{
print $row->sex.' '.$row->charname.' misses!';
}
print '</font><br>';
return $mon->life;
}
//weapon

//magic
function magic($row,$mon){
print '<font color="#8888FF">';

$row->max_mr/=(rand(100,255)/100);
$mon->max_mr/=(rand(100,255)/100);

if($row->max_mr<$row->min_mr){$row->max_mr=$row->min_mr;}
if($mon->max_mr<$mon->min_mr){$mon->max_mr=$mon->min_mr;}

if($row->max_mr>=$mon->max_mr){
$row->max_spell/=(rand(100,255)/100);
$mon->max_shield/=(rand(100,255)/100);
if($row->max_spell<$row->min_spell){$row->max_spell=$row->min_spell;}
if($mon->max_shield<$mon->min_shield){$mon->max_shield=$mon->min_shield;}

if($row->max_spell<=0){
print $mon->sex.' '.$mon->charname.' contravents the spell!';
}else{
$mon->life-=($row->max_spell-$mon->max_shield);
print $row->sex.' '.$row->charname.' cast for '.number_format($row->max_spell).'! '. $mon->sex.' '.$mon->charname.' contravents for '. number_format($mon->max_shield).'.';
}
}else{
print $row->sex.' '.$row->charname.' spell fizzles!';
}
print '</font><br>';
return $mon->life;
}
//magic

//heal
function heal($row,$mon){
print '<font color="#88FF88">';

$row->max_mr/=(rand(100,255)/100);
$mon->max_mr/=(rand(100,255)/100);

if($row->max_mr<$row->min_mr){$row->max_mr=$row->min_mr;}
if($mon->max_mr<$mon->min_mr){$mon->max_mr=$mon->min_mr;}

if($row->max_mr>=$mon->max_mr){
$row->max_heal/=(rand(100,255)/100);
$mon->max_shield/=(rand(100,255)/100);
if($row->max_heal<$row->min_heal){$row->max_heal=$row->min_heal;}
if($mon->max_shield<$mon->min_shield){$mon->max_shield=$mon->min_shield;}

if($row->max_heal<=0){
print $mon->sex.' '.$mon->charname.' contravents the healspell!';
}else{
$mon->life-=($row->max_heal-$mon->max_shield);
print $row->sex.' '.$row->charname.' heals for '. number_format($row->max_heal).'! '. $mon->sex.' '.$mon->charname.' contravents for '. number_format($mon->max_shield).'.';
}
}else{
print $row->sex.' '.$row->charname.' spell fizzles!';
}
print '</font><br>';
return $mon->life;
}
//heal

//PET
function pet($row,$mon){
$tbl_pets='lol_pets';

if($petres=mysqli_query($link, "SELECT * FROM `$tbl_pets` WHERE `charname`='$row->charname' ORDER BY `id` ASC LIMIT 1")){
if(mysqli_numrows($petres) >= 1){
if($petobj=mysqli_fetch_object($petres)){
mysqli_free_result($petres);
print '<font color="#CCCCCC">';
$pet_damage = $petobj->level*($petobj->str+$petobj->dex+$petobj->agi);
$pet_magic = $petobj->level*($petobj->intel+$petobj->conc+$petobj->cont);

if($petobj->hunger >= 3 or $petobj->mood >= 3){
if($pet_damage >= $pet_magic and $pet_damage > $mon->min_defense){
	$mon->life -= ($pet_damage-$mon->min_defense); print $petobj->petname.' hits for '.number_format($pet_damage-$mon->min_defense).' damage.';
}elseif($pet_damage < $pet_magic and $pet_magic > $mon->min_shield){
	$mon->life -= ($pet_magic-$mon->min_shield);print $petobj->petname.' cast for '.number_format($pet_magic-$mon->min_shield).' damage.';
}else{
print 'Your pet is too weak to do anything.';
}
}else{
	print 'Your pet is not in the mood or to hungry to do anything at this moment.';
	}
print '</font><br>';
}
}
}

return $mon->life;
}
//PET
?>