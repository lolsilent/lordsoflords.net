<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($inc_mysql);
include_once($html_header);

$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);

if($wresult=mysqli_query($link, "SELECT * FROM `$tbl_aservers` WHERE `id` ORDER BY `id` DESC LIMIT 1000")){

print '<table cellpadding=2 cellspacing=2 width="100%" border=0><tr><th>Worlds</th><th>Players</th><th>Game Mode</th><th>Online</th><th>Resets</th><th>MKS</th><th>MPPI</th><th>MFP</th><th>Chat</th><th>Ladder</th></tr>';

$total_players=0;
$total_online=0;
while($wrow=mysqli_fetch_object($wresult)){

$wtotal=0;$wonline=0;
if($oresult=mysqli_query($link, "SELECT `id` FROM `$tbl_members` WHERE `server_id`='$wrow->id' and `timer`>=($current_time-900) and `onoff`>='1' LIMIT 10000")){$wonline=mysqli_num_rows($oresult);mysqli_free_result($oresult);}

if ($wonline >=1 or isset($_GET['show_all'])) {

if($presult=mysqli_query($link, "SELECT `id` FROM `$tbl_members` WHERE `server_id`='$wrow->id' LIMIT 10000")){$wtotal=mysqli_num_rows($presult);mysqli_free_result($presult);}

if($wrow->chat_timer >= 1) {$chat_timer='Y';}else{$chat_timer='N';}

print '<tr><td nowrap>'.($wrow->timer<=$current_time-(84600*7)?ucfirst($wrow->world_name).' <sup>INACTIVE</sup>':'<a href="signup.php?served='.$wrow->id.'" title="Controlled by '.$wrow->admin_name.'">'.ucfirst($wrow->world_name).'</a>').'</td>';




print '<td>'.lint($wtotal).'</td><td>'.$game_modes[$wrow->game_mode].'</td><td>'.lint($wonline).'</td><td>';

//RESET OR NOT
if($wrow->reset_days >= 1){
$reset_days_past = ($current_time-$wrow->updated);
$reset_days = 86400*$wrow->reset_days;

if ($reset_days_past >= $reset_days) {

mysqli_query($link, "UPDATE `$tbl_aservers` SET `updated`='$current_time' WHERE `id`='$wrow->id' LIMIT 1");
mysqli_query($link, "UPDATE LOW_PRIORITY `$tbl_members` SET `level`=100, `xp`=50000, `gold`=25000, `stash`=100000, `life`=10000, `str`=200, `dex`=100, `agi`=50, `intel`=200, `conc`=100, `cont`=50, `weapon`=1, `spell`=1, `heal`=1, `helm`=1, `shield`=1, `amulet`=1, `ring`=1, `armor`=1, `belt`=1, `pants`=1, `hand`=1, `feet`=1, `rounds`=`rounds`+1 WHERE `server_id`='$wrow->id' LIMIT 100000");

mysqli_query($link, "DELETE LOW_PRIORITY FROM `$tbl_save` WHERE (`server_id`='$wrow->id') LIMIT 100000");

//mysqli_query($link, "UPDATE `$tbl_history` SET `kills`=0,`deads`=0,`duelsw`=0,`duelsl`=0 WHERE `server_id`='$wrow->server_id'");
//mysqli_query($link, "DELETE LOW_PRIORITY FROM `$tbl_charms` WHERE (`name`!='Gods Charm' or `name`!='Heavenly Charm')");



print 'Restarted!';
} else {
if ($current_month <=11) {$current_month+=1;} else {$current_month=1;}
print sdater($current_time+($reset_days-$reset_days_past));
}

print ' / '.$wrow->reset_days.' days';
}else{ print 'Never';}
//RESET OR NOT

print '</td><td>'.$wrow->killing_spree_max.'</td><td>'.$wrow->max_player_per_ip.'</td><td>'.$wrow->fp_bonus_max.'</td><td>';

print ($chat_timer == 'N')?'N':'<a href="chats.php?served='.$wrow->id.'">'.$chat_timer.'</a>';

print '</td><td><a href="ladder.php?served='.$wrow->id.'">Y</a></td></tr>';
}//online
$total_players+=$wtotal;
$total_online+=$wonline;

}
mysqli_free_result($wresult);
print '<tr><td colspan=10 align=center><a href="?show_all">Show All Worlds</a></td></tr></table>
<table border=0 width=100%><tr><td align=center>More than '.lint($total_players).' players alive!<br>
More than '.lint($total_online).' players are playing now!
</td></tr></table>';



//battles took place //date `total` and `timer`=days on id 1!!
if($fresult=mysqli_query($link, "SELECT * FROM `$tbl_index` WHERE `id` ORDER BY `id` DESC LIMIT 50")){
if(mysqli_num_rows($fresult) >= 1){

if($mfresult=mysqli_query($link, "SELECT * FROM `$tbl_index` WHERE `id` ORDER BY `fights` DESC LIMIT 1")){
$most_fobj=mysqli_fetch_object($mfresult);mysqli_free_result($mfresult);}

print '<table width="100%"><tr><th>Battles</th></tr>';
$i=0;$oldtotal=0;$tdays=0;$sumbattles=0;while($fobj=mysqli_fetch_object($fresult)){$i++;
if($fobj->date == 'total'){$oldtotal=$fobj->fights;$tdays+=$fobj->timer;}else{$sumbattles+=$fobj->fights;$tdays++;}

if($fobj->date !== 'total' and $i<=7){print '<tr><td>'; if(date('m d Y')==$fobj->date){print 'Today';}else{print $fobj->date;}print ' with '.lint($fobj->fights).' battles!</td></tr>';}

if($i>7 and $fobj->date !== 'total' and $fobj->fights < $most_fobj->fights){
	mysqli_query($link, "DELETE FROM `$tbl_index` WHERE `id` and `id`=$fobj->id and `date`!='total' LIMIT 1");
	mysqli_query($link, "UPDATE `$tbl_index` SET `fights`=`fights`+$fobj->fights WHERE `id` and `date`='total' and `timer`=`timer`+1 LIMIT 1");
}

}mysqli_free_result($fresult);
print '</table>
<table border=0 width=100%><tr><td align=center>With a grandtotal of '. lint($oldtotal+$sumbattles).' battles in the past '. lint($tdays).' day'.($tdays>1?'s':'').'!<br>Most slaughtering happened on '.$most_fobj->date.' with '.lint($most_fobj->fights).' battles.</td></tr></table>';}}
//battles took place //date `total` and `timer`=days on id 1!!


//BOTTOM
if($tresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `id` ORDER BY `id` DESC LIMIT 1")){
$nobj=mysqli_fetch_object($tresult);mysqli_free_result($tresult);
}else{$total=0;}
print '<table border=0 width=100%><tr><td align=center>Their are already <b>'. lint($nobj->id).'</b> players that have played this game.<br>';
if(!empty($nobj)){print 'We welcome our newest adventurer <b>'.$nobj->sex.' '.$nobj->charname.'</b>.<br>';}
print '<br><b>Configure and rule your own free online web RPG game!</b><br>Tell all your friends to come and visit your world!</td></tr></table>';
//BOTTOM

}
mysqli_close($link);


include_once($html_footer);
?>