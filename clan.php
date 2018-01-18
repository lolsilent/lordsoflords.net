<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($inc_races);
require_once($inc_battle);
require_once($game_header);
if($row->level >= 100) {
$max_per_clan=100;

if(empty($row->clan) and !empty($_POST)){
if(!empty($_POST['action'])){$action=clean_post($_POST['action']);}else{$action='';}
if(!empty($_POST['clan_name'])){$clan_name=clean_post($_POST['clan_name']);if(strlen($clan_name)>15){$clan_name='';}}else{$clan_name='';}
if(!empty($_POST['clan'])){$clan=clean_post($_POST['clan']);if(!ctype_alnum($clan) and strlen($clan)>=2 and strlen($clan)<=3){$clan='';}}else{$clan='';}
if(!empty($_POST['password'])){$password=clean_post($_POST['password']);}else{$password='';}

if($action == 'Start' and !empty($clan_name) and !empty($clan) and !empty($password) and $row->level>=100){

mysqli_query($link, "INSERT INTO `$tbl_clans` VALUES(NULL,'$row->sex','$row->charname','$password','$clan','$clan_name',0,0,0,0,0,'$current_time')");
if(!mysqli_errno($link)){
$update_it.=", `clan`='$clan'";
print 'You are now the leader of the clan ['.$clan.'] '.$clan_name.' ';
$row->clan=$clan;
}else{print mysqli_error($link).mysqli_errno($link).'Clan name already exist.';}

}elseif($action == 'Join' and !empty($clan) and !empty($password)){

if($jresult=mysqli_query($link, "SELECT * FROM `$tbl_clans` WHERE `clan`='$clan' and `password`='$password' ORDER BY `id` DESC LIMIT 1")){
if($jobj=mysqli_fetch_object($jresult)){

mysqli_free_result($jresult);
$update_it.=", `clan`='$jobj->clan'";
$row->clan=$clan;

}else{print 'There is no such clan or you don\'t have the correct password!';}
}

}
}

print '<form method="post" action="clan.php?sid='.$sid.'"><table width="100%">';

/*_______________-=TheSilenT.CoM=-_________________*/

	//mysqli_query($link, "UPDATE `$tbl_members` SET clan='ND' WHERE `clan`=''  LIMIT 20");
	//mysqli_query($link, "UPDATE `$tbl_clans` SET `tourney`=1 WHERE `id` LIMIT 100");

if(!empty($row->clan)){

if($gresult=mysqli_query($link, "SELECT * FROM `$tbl_clans` WHERE `clan`='$row->clan' ORDER BY `id` DESC LIMIT 1")){
if($gobj=mysqli_fetch_object($gresult)){
mysqli_free_result($gresult);

if(!empty($_POST['leave']) and $gobj->charname !== $row->charname){
	$update_it.=", `clan`=''"; print 'You have left clan life.';
}elseif(!empty($_POST['stop']) and $gobj->charname == $row->charname){
	$update_it.=", `clan`=''"; print 'You have ended your clan.';
	mysqli_query($link, "DELETE FROM `$tbl_clans` WHERE `id`=$gobj->id LIMIT 1");
}elseif(!empty($_POST['npass']) and $gobj->charname == $row->charname){
	$npass=clean_input($_POST['npass']);
	if(!empty($npass)){
	mysqli_query($link, "UPDATE `$tbl_clans` SET `password`='$npass' WHERE `id`='$gobj->id' LIMIT 1");
print 'Change password to '.$npass.' ';
}else{print 'Invalid password!';}
}elseif(!empty($_GET['kick']) and $gobj->charname == $row->charname){
	$kick=clean_input($_GET['kick']);
mysqli_query($link, "UPDATE `$tbl_members` SET clan='' WHERE (`charname`='$kick' and `clan`='$row->clan') LIMIT 1");
print 'Kicked a member out of your clan!';
}else{

print '<tr><th colspan="3">Clan '.'['.$gobj->clan.'] '.$gobj->name.'</th></tr>
<tr><td colspan="3">We achieved '.lint($gobj->won).' victor'.($gobj->won>1?'ies':'y').', '.lint($gobj->lost).' loss'.($gobj->lost<>1?'es':'').' and '.lint($gobj->tied).' tie'.($gobj->tied<>1?'s':'').' in the current round of the tournament.</td></tr><tr><th colspan="3">Clan members</th><tr><td colspan="3">';


/*_______________-=TheSilenT.CoM=-_________________*/

$cpar=array('level'=>0,'life'=>0,'min_wd'=>0,'max_wd'=>0,'min_spell'=>0,'max_spell'=>0,'min_heal'=>0,'max_heal'=>0,'min_shield'=>0,'max_shield'=>0,'min_defense'=>0,'max_defense'=>0,'min_ar'=>0,'max_ar'=>0,'max_mr'=>0,'min_mr'=>0);

if($mresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE (clan='$gobj->clan' or `id`=$row->id) ORDER BY `level` DESC LIMIT $max_per_clan")){
$total=lint(mysqli_num_rows($mresult));
while($mobj=mysqli_fetch_object($mresult)){
print ($mobj->twin>=1?'-':'+').'<a href="members.php?sid='.$sid.'&amp;info='.$mobj->charname.'">'.(($gobj->charname == $mobj->charname) ? 'Leader ':'').$mobj->sex.' '.$mobj->charname.'</a> <sup><font size=-1><a href="?sid='.$sid.'&kick='.$mobj->charname.'">kick</a></font></sup>, ';
if($mobj->twin<=0){
$mss=battlestats($mobj);
$cpar['level']+=$mobj->level;$cpar['life']+=$mobj->life;
$cpar['min_wd']+=$mss->min_wd;$cpar['max_wd']+=$mss->max_wd;
$cpar['min_spell']+=$mss->min_spell;$cpar['max_spell']+=$mss->max_spell;
$cpar['min_heal']+=$mss->min_heal;$cpar['max_heal']+=$mss->max_heal;
$cpar['min_shield']+=$mss->min_shield;$cpar['max_shield']+=$mss->max_shield;
$cpar['min_defense']+=$mss->min_defense;$cpar['max_defense']+=$mss->max_defense;
$cpar['min_ar']+=$mss->min_ar;$cpar['max_ar']+=$mss->max_ar;
$cpar['min_mr']+=$mss->min_mr;$cpar['max_mr']+=$mss->max_mr;
}
}
mysqli_free_result($mresult);
}

print lint($total).' member'.$total>1?'s':''.'
</td></tr>
<tr><th colspan="3">Clan battle stats!</th></tr>
<tr>
<td><br>Weapon damage<br>Attack spell<br>Heal spell<br>Magic shield<br>Defense<br>Attack Rating<br>Magic Rating</td>
<td>min<br>'.lint($cpar['min_wd']).'<br>'.lint($cpar['min_spell']).'<br>'.lint($cpar['min_heal']).'<br>'.lint($cpar['min_shield']).'<br>'.lint($cpar['min_defense']).'<br>'.lint($cpar['min_ar']).'<br>'.lint($cpar['min_mr']).'</td>
<td>max<br>'.lint($cpar['max_wd']).'<br>'.lint($cpar['max_spell']).'<br>'.lint($cpar['max_heal']).'<br>'.lint($cpar['max_shield']).'<br>'.lint($cpar['max_defense']).'<br>'.lint($cpar['max_ar']).'<br>'.lint($cpar['max_mr']).'</td>
</tr>
'.'<tr><th colspan="2">Level '.lint($cpar['level']).'</th><th colspan="2">Life '.lint($cpar['life']).'</th></tr>'.'
<tr><th colspan="3">Total clan power: '.lint(array_sum($cpar)).'</th></tr>
';

/*_______________-=TheSilenT.CoM=-_________________*/


if($gobj->charname == $row->charname){
print '<tr><th><input type=text name=npass><input type=submit name=action value="Change Pass!"></th><th>';
if(!$gobj->tourney and empty($_POST['tourney_join']) xor !empty($_POST['tourney_leave'])){
	print '<input type="submit" name="tourney_join" value="Join the tournament!">';
	}else{
		print '<input type="submit" name="tourney_leave" value="Leave the tournament!">';
		}
		print '</th><th><input type="submit" name="stop" value="Stop my clan"></th></tr>';

if(!empty($_POST['tourney_join'])){mysqli_query($link, "UPDATE `$tbl_clans` SET `won`='0',`lost`='0',`tied`='0',`points`='0',`tourney`=1 WHERE `id`=$gobj->id LIMIT 1");}elseif(!empty($_POST['tourney_leave'])){mysqli_query($link, "UPDATE `$tbl_clans` SET `won`='0',`lost`='0',`tied`='0',`points`='0',`tourney`=0 WHERE `id`=$gobj->id LIMIT 1");}
}else{
print '<tr><th colspan="3"><input type="submit" name="leave" value="Leave '.'['.$gobj->clan.']'.' clan"></th></tr>';
}

}
}else{$update_it.=", `clan`=''";}}else{$update_it.=", `clan`=''";}

}

/*_______________-=TheSilenT.CoM=-_________________*/

if(empty($row->clan)){
if($row->level >=100){
	print '<tr><th colspan="2">Start your own clan</th></tr>
<tr><td width="50%">Clan name<br><font size="1">Maxlength is 15 chars</font></td><td><input type="text" name="clan_name" size="15" maxlength="15"></td></tr>
<tr><td width="50%">Abbreviation<br><font size="1">Short name in 2 or 3 characters for your clan name</font></td><td><input type="text" name="clan" size="15" maxlength="3"></td></tr>
<tr><td>Clan Password<br><font size="1">A clan password that\'s required for other members to join</font></td><td><input type="password" name="password" size="15" maxlength="10"></td></tr>
<tr><th colspan=2><input type="submit" name="action" value="Start"></th></tr>
</table></form><form method="post" action="clan.php?sid='.$sid.'"><table width="100%">
';}
print '
<tr><th colspan="2">Joining a clan</th></tr>
<tr><td width="50%">Abbreviation</td><td><input type="text" name="clan" size="15" maxlength="3"></td></tr>
<tr><td>Clan Password<br><font size="1">A clan password that has been provided by your clan leader</font></td><td><input type="password" name="password" size="15" maxlength="10"></td></tr>
<tr><th colspan=2><input type="submit" name="action" value="Join"></th></tr>';
}

print '</table></form>';
}else{
	print 'Require level 100 to start or join a clan!';}
print '<hr>
To fight in the tournament you must have higher amount of xp and gold than your level.<br>
End of the tournament round, leaving or joining the tournament resets your clan achievement.<br>
Leaving or stopping your clan won\'t prevent you to stop from playing in the upcoming matches.<br>
- Must rest a round in tournament.<br>
+ Available for the tournament.<hr>';

require_once($game_footer);
?>