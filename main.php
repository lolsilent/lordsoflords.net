<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

print '<table width="100%">';
if($hresult=mysqli_query($link, "SELECT * FROM `$tbl_history` WHERE `charname`='$row->charname' LIMIT 1")){
if($hobj=mysqli_fetch_object($hresult)){
mysqli_free_result($hresult);

print '<tr><th colspan="2">World Configurations of '.$wrow->world_title.'<br>'.$wrow->world_description.'</th></tr><tr><td valign=top>World Name<br>World Admin<br>World Birthday<br>Max Killing Spree<br>Max Players Per IP<br>Max FP bonus<br>Max Muted<br>Max Jailed<br>World Resets<br>Game mode<br>Chat Timer</td><td valign=top>';
print ucfirst($wrow->world_name).'<br>'.$wrow->admin_name.'<br>'.$wrow->world_date.'<br>'.lint($killing_spree_max).'<br>'.$max_player_per_ip.'<br>'.lint($fp_bonus_max).'<br>'.lint($wrow->max_muted).'<br>'.lint($wrow->max_jailed).'<br>';
print ($wrow->reset_days>=1)?$wrow->reset_days.' days':'Never';
print '<br>'.$game_modes[$wrow->game_mode].'<br>'.$chat_timer.'</td></tr>';


print '<tr><th><a href="rules.php?sid='.$sid.'">World ruleS</a> - <a href="races.php?sid='.$sid.'">World raceS</a></th><th><a href="create.php?sid='.$sid.'">Create your own worlD</a></th></tr>';


$jail_time=$row->jail-$current_time;if($jail_time<=0){$jail_time=0;}
$mute_time=$row->mute-$current_time;if($mute_time<=0){$mute_time=0;}
$stealth=$row->stealth-$current_time;if($stealth<=0){$stealth=0;}
print '<tr><th colspan="2">Main overview of '.(!empty($row->clan)?"[$row->clan]":'').' '.$row->sex.' '.$row->charname.' at '.(!empty($server)?$server:'').'</th></tr>
<tr><td>
'.(!empty($row->clan)?"Clan<br>":'').'
Race<br>
Level<br>
Life<br>
Xp<br>
Gold<br>
Stash<br>
Freeplay<br>
Monsters killed<br>
Deads by monster<br>
Duels won<br>
Duels lost<br>
Total fights<br>
Total duels<br>
Last activity<br>
First login<br>
'.(isset($_COOKIE['pc'])?'Personal color':'').'
</td><td>
';
print (!empty($row->clan)?"[$row->clan]<br>":'').$row->race.'<br>'.lint($row->level).'<br>'.lint($row->life).'<br>'.lint($row->xp).'<br>$'.lint($row->gold).'<br>'.lint($row->stash).'<br>'.lint($fp).'<br>'.lint($hobj->kills).'<br>'.lint($hobj->deads).'<br>'.lint($hobj->duelsw).'<br>'.lint($hobj->duelsl).'<br>'.lint($hobj->kills+$hobj->deads).'<br>'.lint($hobj->duelsw+$hobj->duelsl).'<br>'.($row->timer>1?dater($row->timer).' ago':'none').'<br>'.($hobj->timer>1?dater($hobj->timer).' ago':'never').'<br>'.(isset($_COOKIE['pc'])?'<font color="'.$_COOKIE['pc'].'">EXAMPLE</a> color':'');
print '</td></tr>
<tr><th width="50%"><a href="preference.php?sid='.$row->sid.'">Preference</a></th><th width="50%"><a href="friends.php?sid='.$row->sid.'">Friends</a></th></tr>
';

if(!empty($row->fail)){
print '<tr><th colspan="2">WARNING : Login failed attempted '.$row->fail.' times!</th></tr>';
$update_it.=", `fail`=0";
}

if(empty($row->email)){
print '<tr><th colspan="2">WARNING : Your email address is unknown, if you lose or forget your password or account then there is nobody that can help you! Please click preference to add an email address.</th></tr>';
}

if (in_array($row->sex,$operators) and $row->sex !== 'Support'){
print '<tr><th colspan="2"><a href="admin.php?sid='.$sid.'">Game control panel for '.$row->sex.'</a></th></tr>';}

	//print "$row->charname == $wrow->admin_name";
	//print_r($wrow);
if ($row->charname == $wrow->admin_name or $row->id == 1){
	//IN CASE ADMIN WAS NOT AUTOMATED from create.php TRY THIS ONE
	if($row->sex != 'Admin' and empty($_GET['super_admin'])){
	print '<tr><th colspan=2>To access all control panels of your world you char needs to become an admin.<a href="?sid='.$sid.'&super_admin=1">Click here to become the Admin of your world.</a></th></tr>';
	}
	if(!empty($_GET['super_admin'])){
		$update_it .= ", `sex`='Admin'";
	}
//IN CASE ADMIN WAS NOT AUTOMATED from create.php TRY THIS ONE

print '<tr><th><a href="admin_world.php?sid='.$sid.'">World control panel.</a></th><th><a href="admin_super.php?sid='.$sid.'">Super Admin Toys.</a></th></tr>';
}

}else{
print '<tr><th>Welcome to '.$title.' '.$row->sex.' '.$row->charname.'</th></tr><tr><td>
<br>
Please always remember that this is a game, there are things you can do in games that you can not and may not do in the real world.<br>
Please notice that a game is supposed to be fun so please always be nice to each other.<br>
Please do not complain if you dislike this game please leave the game alone.<br>
Please do not use foul language in anyway.<br>
<br>
I compared a online game like a bar, pub, disco a place where you can make friends or meet friends.<br>
Sometimes I get angry too I\'m only human if you do so please try to tell your mood first if to late cool down and go to sleep tell it tommorow.<br>
You are not going to shout F*CK all the time in the disco, if you do so you will be trown out.<br>
<br>
Please take some time to read our privacy, terms, rules and forums for updated information about this virtual world.<br>
<br>
Please remember this when you play this game!<br>
<b>I DO NOT CONTROL THE GAME THE PLAYERS DO! I ONLY MADE IT!</b><br>
<br>
Have fun and thank you for your time,<br>'.$admin_name.'<br>
<br>
<a href="main.php?sid='.$row->sid.'">Click here to start playing '.$title.'!</a></td></tr>';
mysqli_query($link, "INSERT INTO `$tbl_history` VALUES (NULL,'$row->charname',0,0,0,0,$current_time)");
}
}
print '</table>';

require_once($game_footer);
?>