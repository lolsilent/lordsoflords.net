<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

$min_level_pet = 250;
$pet_price= $row->level * 1000000;

if($row->level >= $min_level_pet){
	//MYSQL INFO
$tbl_pets='lol_pets';
$fld_pets='`id`, `charname`, `petname`, `petrace`, `mother`, `level`, `xp`, `str`, `dex`, `agi`, `intel`, `conc`, `cont`, `mood`, `hunger`, `age`, `timer`';
$pet_races = array('Lion','Anaconda','Eagle');

$pet_moods= array('Super Unhappy','Verry Verry Unhappy','Very Unhappy','Unhappy','Happy','Very Happy','Very Very Happy','Super Happy','Super Mega Happy','Incredible Super Mega Happy');
$pet_moods_max = count($pet_moods)-1;
$pet_hunger= array('Walking Bones','Almost Skeleton','Verry Verry Hungry','Very Hungry','Hungry','Good','Very Full','Very Very Full','Super Fat','Super Summo Fat');
$pet_hunger_max = count($pet_hunger)-1;
	//MYSQL INFO

	//POST INFO
if(!empty($_POST['petname'])){$petname=clean_post($_POST['petname']);if(empty($petname)){$petname='';}}else{$petname='';}
if(!empty($_POST['petrace'])){$petrace=clean_post($_POST['petrace']);if(empty($petrace)){$petrace='';}}else{$petrace='';}
if(!empty($_POST['str'])){$str=clean_post($_POST['str']);if($str <= 0){$str=0;}}else{$str=0;}
if(!empty($_POST['dex'])){$dex=clean_post($_POST['dex']);if($dex <= 0){$dex=0;}}else{$dex=0;}
if(!empty($_POST['agi'])){$agi=clean_post($_POST['agi']);if($agi <= 0){$agi=0;}}else{$agi=0;}
if(!empty($_POST['intel'])){$intel=clean_post($_POST['intel']);if($intel <= 0){$intel=0;}}else{$intel=0;}
if(!empty($_POST['conc'])){$conc=clean_post($_POST['conc']);if($conc <= 0){$conc=0;}}else{$conc=0;}
if(!empty($_POST['cont'])){$cont=clean_post($_POST['cont']);if($cont <= 0){$cont=0;}}else{$cont=0;}
	//POST INFO

print '<table width="100%"><tr><th colspan=2>'.$title.' Pets</th></tr>';

if($petres=mysqli_query($link, "SELECT * FROM `$tbl_pets` WHERE `charname`='$row->charname' ORDER BY `age` ASC LIMIT 1")){

if(mysqli_num_rows($petres) >= 1){
if($petobj=mysqli_fetch_object($petres)){
mysqli_free_result($petres);

if($current_time - $petobj->timer >= 5000){
mysqli_query($link, "UPDATE `$tbl_pets` SET `mood`=`mood`-1,`hunger`=`hunger`-1 WHERE `charname`='$row->charname' LIMIT 1");
}elseif($current_time - $petobj->timer >= 25000){
mysqli_query($link, "UPDATE `$tbl_pets` SET `mood`=`mood`-2,`hunger`=`hunger`-2 WHERE `charname`='$row->charname' LIMIT 1");
}elseif($current_time - $petobj->timer >= 75000){
mysqli_query($link, "UPDATE `$tbl_pets` SET `mood`=1,`hunger`=1 WHERE `charname`='$row->charname' LIMIT 1");
}

$pet_next_level = ((($petobj->level/10)*500)+$petobj->level)*($petobj->level*$petobj->level)+449;

if($petobj->mood >= $pet_moods_max) {$petobj->mood=$pet_moods_max;}
if($petobj->hunger >= $pet_hunger_max) {$petobj->hunger=$pet_hunger_max;}
$food_price=($row->level*(2.5123452+$petobj->level));

	//pet actions
if(!empty($_POST['action'])){$action=$_POST['action'];
print '<tr><td colspan=2>';

if($petobj->xp >= $pet_next_level) {
	$multi=$petobj->level/100;
	if($fp >= 1){$multi *= 10;}
	if($multi <= 1 ){$multi=1;}

mysqli_query($link, "UPDATE `$tbl_pets` SET `level`=`level`+$multi, `str`=`str`+((`str`/`level`)*$multi), `dex`=`dex`+((`dex`/`level`)*$multi), `agi`=`agi`+((`agi`/`level`)*$multi), `intel`=`intel`+((`intel`/`level`)*$multi), `conc`=`conc`+((`conc`/`level`)*$multi), `cont`=`cont`+((`cont`/`level`)*$multi) WHERE `charname`='$row->charname' LIMIT 1");
}

if($action == 'Buy food'){	$update_it.=", `gold`=`gold`-$food_price";
	mysqli_query($link, "UPDATE `$tbl_pets` SET `hunger`=`hunger`+1, `timer`='$current_time' WHERE `charname`='$row->charname' LIMIT 1");
if($petobj->hunger < $pet_hunger_max) {$petobj->hunger++;}
		print 'Your pet is '. $pet_hunger[$petobj->hunger].' now!';
}elseif($action == 'Play'){	$update_it.=", `gold`=`gold`-$food_price";
	mysqli_query($link, "UPDATE `$tbl_pets` SET `mood`=`mood`+1 WHERE `charname`='$row->charname' LIMIT 1");
if($petobj->mood < $pet_moods_max) {$petobj->mood++;}
		print 'Your pet is '. $pet_moods[$petobj->mood].' now!';
}elseif($action == 'Take out for a walk'){
	if(rand(1,100) <= 20){
		print 'Your pet did poo poo!';
	}else{
		print 'Your pet did pee pee!';
	}
}elseif($action == 'Train'){	$update_it.=", `gold`=`gold`-$food_price";
	if($petobj->hunger >= 3 or $petobj->mood >= 3){
	mysqli_query($link, "UPDATE `$tbl_pets` SET `xp`=`xp`+`level`,`hunger`=`hunger`-1,`mood`=`mood`-1 WHERE `charname`='$row->charname' LIMIT 1");
	if(rand(1,10) < 5){print 'Can we play after this?';}else{print 'Give me a cookie.';}
	}else{print 'Your pet is not in the mood or to hungry to do anything at this moment.';}
}elseif($action == 'Release' and !empty($_POST['confirm'])){
	mysqli_query($link, "DELETE FROM `$tbl_pets` WHERE `charname`='$row->charname' LIMIT 1");
	print 'You let your pet free and he walks away into the Forest of Freedom. You didn\'t killed your pet did you?';
}elseif($action == 'Give to' and !empty($_POST['recipient'])){
$recipient=$_POST['recipient'];
	mysqli_query($link, "UPDATE `$tbl_pets` SET `charname`=`$recipient` WHERE `charname`='$row->charname' LIMIT 1") or print('One pet per player.');
}
print '</td></tr>';
}

	//pet actions
print '<tr><td><table width=100%>';

print
'<tr><td width=25%>Name</td><td>'.$petobj->petname.'</td></tr>'.
'<tr bgcolor="'.$colth.'"><td>Mother</td><td>'.$petobj->mother.'</td></tr>'.
'<tr><td>Race</td><td>'.$petobj->petrace.'</td></tr>'.
'<tr bgcolor="'.$colth.'"><td>Level</td><td>'.lint($petobj->level).'</td></tr>'.
'<tr><td>XP</td><td>'.lint($petobj->xp).'</td></tr>'.
'<tr bgcolor="'.$colth.'"><td>Strength</td><td>'.lint($petobj->str).'</td></tr>'.
'<tr><td>Dexterity</td><td>'.lint($petobj->dex).'</td></tr>'.
'<tr bgcolor="'.$colth.'"><td>Agility</td><td>'.lint($petobj->agi).'</td></tr>'.
'<tr><td>Intelligence</td><td>'.lint($petobj->intel).'</td></tr>'.
'<tr bgcolor="'.$colth.'"><td>Concentration</td><td>'.lint($petobj->conc).'</td></tr>'.
'<tr><td>Contravention</td><td>'.lint($petobj->cont).'</td></tr>'.

'<tr bgcolor="'.$colth.'"><td>Attack Damage</td><td>'.lint($petobj->level*($petobj->str+$petobj->dex+$petobj->agi)).'</td></tr>'.
'<tr><td>Magic Damage</td><td>'.lint($petobj->level*($petobj->intel+$petobj->conc+$petobj->cont)).'</td></tr>'.

'<tr bgcolor="'.$colth.'"><td>Mood</td><td>'.$pet_moods[$petobj->mood].' '.($petobj->mood>0?'+':'-').lint($petobj->mood).'</td></tr>'.
'<tr><td>Hunger</td><td>'.$pet_hunger[$petobj->hunger].' '.($petobj->hunger>0?'+':'-').lint($petobj->hunger).'</td></tr>'.
'<tr bgcolor="'.$colth.'"><td>Age</td><td>'.dater($petobj->age).'</td></tr>'.
'<tr><td>Last eaten</td><td>'.dater($petobj->timer).'</td></tr>';

print '</table></td></tr>';

print '<tr><td colspan=2><form method=post>
You can give or do these things with your pet:<br>
<input type=submit name=action value="Buy food">
<input type=submit name=action value="Play">
<input type=submit name=action value="Train">
<input type=submit name=action value="Take out for a walk">
<br><br>
<input type=submit name=action value="Release"><input type=checkbox name=confirm> Confirm to release you pet into the forest of freedom.
<br><br>
<input type=submit name=action value="Give to">
<select name="recipient"><option value="">Nobody</option>
';
if($oresult=mysqli_query($link, "SELECT `sex`,`charname` FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `timer`>$current_time-1000 and `charname`!='$row->charname') ORDER BY `level` ASC LIMIT 100")){
while($pobj=mysqli_fetch_object($oresult)){
print '<option value="'.$pobj->charname.'">'.$pobj->sex.' '.$pobj->charname.'</option>';
}
mysqli_free_result($oresult);
}

print '
</select>

</form>
<br>You pet must eat everyday or else he will lose XP.<br>Food, toy and training camp cost $'.lint($food_price).' gold.<br>Next pet level '. number_format($pet_next_level).'</td></th>';


}//getobj

}else{
	//BUY
if(!empty($petname) and in_array($petrace,$pet_races) and ($str+$dex+$agi+$intel+$conc+$cont) <= 100){
mysqli_query($link, "INSERT INTO `$tbl_pets` VALUES ('', '$row->charname', '$petname', '$petrace', '$row->charname', '1', '0', '$str', '$dex', '$agi', '$intel', '$conc', '$cont', '2', '2', '$current_time', '$current_time')") or print(mysqli_error($link));
print 'You have adopted a new pet.';
}else{
	//BUY

print '<tr><td valign=top colspan=2><form method=POST>';
if($row->gold >= $pet_price){

print '<table><tr><td valign=top>Petname</td><td valign=top><input type=text name=petname maxlength=13></td></tr>
<tr><td valign=top>Petrace</td><td valign=top><select name=petrace>';
foreach ($pet_races as $val){echo '<option value="'.$val.'">'.$val.'</option>';}
print '</select></td></tr>
<tr><th colspan=2>You have have 100 stats points to split.</th></tr>
<tr><td valign=top>Strength</td><td valign=top><input type=text name=str maxlength=2></td></tr>
<tr><td valign=top>Dexterity</td><td valign=top><input type=text name=dex maxlength=2></td></tr>
<tr><td valign=top>Agility</td><td valign=top><input type=text name=agi maxlength=2></td></tr>
<tr><td valign=top>Intelligence</td><td valign=top><input type=text name=intel maxlength=2></td></tr>
<tr><td valign=top>Concentration</td><td valign=top><input type=text name=conc maxlength=2></td></tr>
<tr><td valign=top>Contravention</td><td valign=top><input type=text name=cont maxlength=2></td></tr>
<tr><td valign=top colspan=2 align=center><input type=submit name=action value="Adopt a pet for $'. lint($pet_price).' gold"></td></tr></table>
Your pet will only improve the stats that you give, this happens when your pet ages and wins fights with you.';

}else{echo 'You need $'.lint($pet_price).' gold to buy a pet.'; }
print '</form></td></tr>';
}

}

}
print '</table>';

}else{
	echo 'You need to be at least level '.number_format($min_level_pet).' to control a battle pet.';
	}
print '
<br>A pet will always attack first when possible. <a href="ladder_pets.php">Most dealy pets.</a><br>
';
require_once($game_footer);
?>