<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

if($wrow->game_mode == 2) {
print 'Control panel not allowed in this game mode!';
require_once($game_footer);
exit;
}

if (in_array($row->sex,$operators) and $row->sex !== 'Support') {

if ($row->id <> 1){$where_server="`server_id`='$row->server_id' and ";}else{$where_server='';}

$apower=1;
if($row->sex == 'Admin'){$apower=5;}
if($row->sex == 'Cop'){$apower=3;}
if($row->sex == 'Mod'){$apower=2;}
if($row->sex == 'Support'){$apower=1;}


$actions = array('mute'=>2,'unmute'=>2,'jail'=>3,'bail'=>3,'lord'=>5,'lady'=>5,'kick'=>1,'login'=>5,'logs'=>3);
$actions_keys = array_keys($actions);

if(!empty($_GET['player'])){$player=clean_post($_GET['player']);}else{$player='';}
if(!empty($_GET['action'])){$action=clean_post($_GET['action']);}else{$action='';}
	
/*_______________-=TheSilenT.CoM=-_________________*/

//SUPER WORLD ADMIN ATTACK
if ($player == $wrow->admin_name) {
if ($action == 'mute' or $action == 'jail' or $action == 'kick') {
	//if ($row->mute <= $current_time) {
require_once($_SERVER['DOCUMENT_ROOT'].'/AdMiN/functions.messaging2.php');

$gid=message_gid($server);
$message='<b><font color=red>SUPER ADMIN ATTACK ATTEMPTED BY '.$row->sex.' '.$row->charname.'</b></font>';
$recipient=$wrow->admin_name;

//print "$gid $message $recipient == $wrow->admin_name";

if($sares=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE (`charname`='$recipient') ORDER BY `id` ASC LIMIT 1")){
if($srow=mysqli_fetch_object($sares)){mysqli_free_result($sares);
}}

mysqli_select_db($link,$dbm_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);

mysqli_query($link, "INSERT INTO `$tbl_messages` ($fld_messages) VALUES (NULL, '$gid', '$row->id', '$srow->id', '$message', '0', '0', '$current_date', $current_time, $current_time)") or print(mysqli_error($link));

mysqli_select_db($link,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);

$player='';

$update_it .= ", `sex`='SaD',`mute`=$current_time+1000000, `jail`=$current_time+1000000";

mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','<b><font color=red>World Protection jailed and muted $row->sex $row->charname!</font></b>',$current_time)") or die(mysqli_error($link));


	//}
}
}

/*_______________-=TheSilenT.CoM=-_________________*/

if ($action !== 'logs' and !empty($player) and $player !== $row->charname){

if($poresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE ($where_server`charname`='$player') ORDER BY `id` ASC LIMIT 1")){
if($ppobj=mysqli_fetch_object($poresult)){
	mysqli_free_result($poresult);

print 'Found player '.$ppobj->sex.' '.$ppobj->charname;

if(in_array($action,$operators) or in_array($action,$punished_sex)){
if ($apower >= 5){
	$uppap='casted a sex change spell '.$action;
	$upmin="`sex`='$action'";
}
}elseif(in_array($action,$actions_keys)){
	$mute_timer=60*15;
if($row->sex == 'Admin'){
	$mute_timer=$wrow->max_muted;
	$jail_timer=$wrow->max_jailed;
}elseif($row->sex == 'Cop'){
	$mute_timer=$wrow->max_muted/2;
	$jail_timer=$wrow->max_jailed/2;
}
switch($action){
case $action == $actions_keys[0]:$uppap='muted';$upmin="`mute`=$current_time+$mute_timer";break;
case $action == $actions_keys[1]:$uppap='unmuted';$upmin="`mute`='0'";break;
case $action == $actions_keys[2] and $apower >= 3:$uppap='jailed';$upmin="`jail`=$current_time+$jail_timer";break;
case $action == $actions_keys[3] and $apower >= 3:$uppap='bailed';$upmin="`jail`='0'";break;
case $action == $actions_keys[4] and $apower >= 5:$uppap='casted a sex change spell Lord';$upmin="`sex`='Lord'";break;
case $action == $actions_keys[5] and $apower >= 5:$uppap='casted a sex change spell Lady';$upmin="`sex`='Lady'";break;
case $action == $actions_keys[6] and $apower >= 2:$uppap='kicked';$isid=md5(crypt($current_time,$ppobj->username));$upmin="`sid`='$isid'";break;
case $action == $actions_keys[7] and $apower >= 2:$uppap='forced login of';$upmin="`timer`='$current_time'";break;
default:$uppap='';$upmin='';
}
}

if(!empty($upmin) and !empty($uppap)){
	if($ppobj->id == 1){
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname muted and jailed for 1 million seconds for attempting to mess with $ppobj->sex $ppobj->charname',$current_time)");
$update_it .= ", `mute`=$current_time+1000000, `jail`=$current_time+1000000";
	}else{
print ' '.$uppap.'.';
mysqli_query($link, "UPDATE `$tbl_members` SET $upmin WHERE ($where_server`id`='$ppobj->id') LIMIT 1") or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__.mysqli_error($link));
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname $uppap $ppobj->sex $ppobj->charname',$current_time)");
	}
}

}}}

/*_______________-=TheSilenT.CoM=-_________________*/

if ($action == 'logs' and !empty($player)){

	//LOGS
if(!empty($_GET['limited'])){$limited=clean_post($_GET['limited']);if($limited>=500){$limited=50;}}else{$limited=50;}
if(!empty($_GET['last_id'])) {$last_id=clean_post($_GET['last_id']);$where_id="id<=$last_id";} else {$where_id='id';}
if(!empty($_GET['file'])){$file=clean_post($_GET['file']);$where_id .= " and `file`='$file'";}
$where_id .=" and charname='$player'";

if($logresult = mysqli_query($link, "SELECT * FROM `$tbl_zlogs` WHERE ($where_id) ORDER BY `id` desc LIMIT $limited")){

print '<table width=100%><tr><th>id</th><th>charname</th><th>logs</th><th>file</th><th>ip</th><th>timer</th></tr>';
while ($drow = mysqli_fetch_object ($logresult)) {
if (empty($bgcolor)) {$bgcolor=" bgcolor=\"$colth\"";} else {$bgcolor='';}
print '<tr'.$bgcolor.'><td>'.$drow->id.'</td><td><a href="?sid='.$sid.'&find='.$drow->charname.'&type=charname" title="Take actions">'.$drow->charname.'</a></td><td>'.$drow->logs.'</td><td><a href="?sid='.$sid.'&action=logs&player='.$player.'&file='.$drow->file.'">'.$drow->file.'</a></td><td><a href="?sid='.$sid.'&find='.$drow->ip.'&type=ip" title="Find all players with this ip">'.$drow->ip.'</a></td><td>'.dater($drow->timer).'</td></tr>';
$lastid=$drow->id;
}
mysqli_free_result ($logresult);
print '</table><a href="?sid='.$sid.'">Admin area</a>';
print !empty($lastid)?' Next <a href="?sid='.$sid.'&action=logs&player='.$player.'&limited='.$limited.'&last_id='.$lastid.'">'.$limited.'</a> <a href="?sid='.$sid.'&action=logs&player='.$player.'&limited='.($limited*2).'&last_id='.$lastid.'">'.($limited*2).'</a> <a href="?sid='.$sid.'&action=logs&player='.$player.'&limited='.($limited*3).'&last_id='.$lastid.'">'.($limited*3).'</a>':'';
}else{print 'No logs available.';}
	//LOGS

}else {

$find='';$type='';
if (!empty($_GET['find']) and !empty($_GET['type'])) {$find=clean_post($_GET['find']);$type=clean_post($_GET['type']);}
if (!empty($_POST['find']) and !empty($_POST['type'])) {$find=clean_post($_POST['find']);$type=clean_post($_POST['type']);}

$founder=array('charname','ip','sex');
print '<form method="post"><table width=100%><tr><th>Game control panel</th></tr><tr><th>Find member by <select name=type>';
foreach ($founder as $val){
if($val == $type){
print '<option value="'.$val.'" selected>'.ucfirst($val).'</option>';
}else{print '<option value="'.$val.'">'.ucfirst($val).'</option>';}
}
print '</select><input type=text name=find value="'.((!empty($find))?$find:'').'"><input type=submit value=Find></th></tr><tr><th>
Show '.'<a href="?sid='.$sid.'&find=Admin&type=sex">Admins</a> <a href="?sid='.$sid.'&find=Cop&type=sex">Cops</a> <a href="?sid='.$sid.'&find=mod&type=sex">Mods</a> <a href="?sid='.$sid.'&find=support&type=sex">Supports</a> <a href="?sid='.$sid.'&find=online&type=online">Online</a> <a href="?sid='.$sid.'">Muted/Jailed</a> <a href="ladder_charms.php?cids=" target="_charms">Charm Finder</a>';
print '</th></tr><tr><th>';
foreach ($punished_sex as $val) {
print '<a href="?sid='.$sid.'&find='.$val.'&type=sex">'.$val.'</a> ';
}
print '</th></tr></table></form>Partial charname or ip is accepted with at least 2 chars.<br>';

if (!empty($find) and !empty($type)) {

if (in_array($type,$founder) or $type == 'online') {

	if($type == 'online'){
			$wherefind = "`timer`>=$current_time-1000";
	}elseif($type == 'ip'){
		if (strlen($find) >= 2) {
			$wherefind = "`ip` LIKE CONVERT (_utf8 '%$find%' USING latin1) COLLATE latin1_swedish_ci";
		}else{$wherefind="`ip` = ''";}
	}elseif($type == 'charname'){
		if (strlen($find) >= 2) {
			$wherefind = "`charname` LIKE CONVERT (_utf8 '%$find%' USING latin1) COLLATE latin1_swedish_ci";
		}else{$wherefind="`charname` = ''";}
	}else{
			$wherefind = "`$type`='$find'";
	}

if($aresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE ($where_server$wherefind) ORDER BY `server_id`,`timer` desc LIMIT 100")){
print '<table width=100%><tr><th>Member</th><th>IP</th><th>Active</th>';
if ($row->id == 1) {print '<th>WID</th>';}
print '<th>Actions</th></tr>';
while ($frow=mysqli_fetch_object ($aresult)) {
print '<tr><td valign=_top><a href="?sid='.$sid.'&find='.$frow->sex.'&type=sex">'.$frow->sex.'</a> <a href="?sid='.$sid.'&find='.$frow->charname.'&type=charname">'.$frow->charname.'<sup>!</sup</a></td><td valign=_top><a href="?sid='.$sid.'&find='.$frow->ip.'&type=ip">'.$frow->ip.'</a></td><td valign=_top>'.($frow->timer?dater($frow->timer):'No').'</td>';
if ($row->id == 1) {print '<td valign=_top>'.$frow->server_id.'</td>';}
print '<td valign=_top>';
foreach ($actions as $val=>$apow) {
	if($apower >= $apow){
print '<a href="?sid='.$sid.'&action='.$val.'&player='.$frow->charname.'">'.ucfirst($val).'</a> ';
	}
}

if($type == 'charname' and $apower >= 5){print '<br>';
foreach ($punished_sex as $val) {
print '<a href="?sid='.$sid.'&action='.$val.'&player='.$frow->charname.'">'.ucfirst($val).'</a> ';
}print '<br>';
foreach ($operators as $val) {
print '<a href="?sid='.$sid.'&action='.$val.'&player='.$frow->charname.'">'.ucfirst($val).'</a> ';
}
}
print '</td></tr>';
}
mysqli_free_result ($aresult);
print '</table>';
}
}

}else{

//MUTED JAILED
if(!empty($_POST['member_id'])) {
$member_id=clean_post($_POST['member_id']);

if($poresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE ($where_server`charname`='$member_id') ORDER BY `id` ASC LIMIT 1")){
if($ppobj=mysqli_fetch_object($poresult)){mysqli_free_result($poresult);

if(!empty($_POST['jailtimer'])) {
$jailtimer=clean_post($_POST['jailtimer']);$jailtimer *= 60;
if ($jailtimer >= 1) {
mysqli_query($link, "UPDATE `$tbl_members` SET `jail`=$current_time+$jailtimer WHERE ($where_server`id`='$ppobj->id') LIMIT 1") or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__.mysqli_error($link));
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname changed the jail timer for $ppobj->sex $ppobj->charname to ".lint($jailtimer/60)." minutes',$current_time)");
}
}

if(!empty($_POST['mutetimer'])) {
$mutetimer=clean_post($_POST['mutetimer']);$mutetimer *= 60;
if ($mutetimer >= 1) {
mysqli_query($link, "UPDATE `$tbl_members` SET `mute`=$current_time+$mutetimer WHERE ($where_server`id`='$ppobj->id') LIMIT 1") or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__.mysqli_error($link));
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname changed the mute timer for $ppobj->sex $ppobj->charname to ".lint($mutetimer/60)." minutes',$current_time)");
}
}

}
}

}

if($aresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE ($where_server`mute`>=$current_time or `jail`>=$current_time) ORDER BY `timer` ASC LIMIT 100")){
print '<table width=100%><tr><th colspan=8>Mute and Jail register</th></tr><tr><th>Member</th><th>IP</th><th>Active</th><th>Jailed</th><th>Jail</th><th>Muted</th><th>Mute</th><th>Actions</th></tr>';
while ($frow=mysqli_fetch_object ($aresult)) {
print '<form method=post><input type=hidden name=member_id value="'.$frow->charname.'"><tr><td><a href="?sid='.$sid.'&find='.$frow->sex.'&type=sex">'.$frow->sex.'</a> <a href="?sid='.$sid.'&find='.$frow->charname.'&type=charname">'.$frow->charname.'<sup>!</sup</a></td><td><a href="?sid='.$sid.'&find='.$frow->ip.'&type=ip">'.$frow->ip.'</a></td><td>'.($frow->timer?dater($frow->timer):'No').'</td><td>'.($frow->jail?dater($frow->jail):'No').'</td><td><input type=text name=jailtimer maxlength=10 size=5></td><td>'.($frow->mute?dater($frow->mute):'No').'</td><td><input type=text name=mutetimer maxlength=10 size=5></td><td>'.'<input type=submit name=action value="Change Timers"></td></tr></form>';
}
mysqli_free_result ($aresult);
print '</table>Inputs are in minutes.';
}
//MUTED JAILED

}

}//logs
} else {
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname Jailed for attempting unautorized accessing the game control panel in $wrow->world_name!','$current_time')");
$update_it .= ", `jail`=$current_time+300";
}
require_once($game_footer);
?>