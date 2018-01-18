<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

	require_once($inc_races);
	$races_array_keys=array_keys($races_array);
	$races_array_keys[]='Crazy';
	$races_array_keys[]='Admin';
	$races_array_keys[]='Cop';
	sort($races_array_keys);

//INJECT RACES
	if($rresult=mysqli_query($link, "SELECT * FROM `$tbl_races` WHERE `server_id`='$row->server_id' ORDER BY `race` ASC LIMIT 100")){
		while ($robj=mysqli_fetch_object($rresult)){
$races_array_keys[]=$robj->race;
		}
		mysqli_free_result($rresult);
	}
sort($races_array_keys);
//INJECT RACES

if($wrow->game_mode == 2) {
print 'Control panel not allowed in this game mode!';
require_once($game_footer);
exit;
}

if ($row->charname == $wrow->admin_name  or $row->id == 1){

$optionz = array('SAT Home','Chat As Anybody','Super Sex Machine', 'Race Changer', 'Race Manager','Merge Worlds');

print '<table width=100% border=1><tr><th colspan=2>Super Admin Toys</th></tr><tr><td valign=top width=125>';
foreach ($optionz as $val) {
	print '<a href="?sid='.$sid.'&aid='.$val.'">'.$val.'</a><br>';
}
print '</td><td valign=top>';

if (isset($_GET['aid'])) {
$aid=$_GET['aid'];
}else{$aid='';}
if (isset($_POST['aid'])) {
$aid=$_POST['aid'];
}

/*_______________-=TheSilenT.CoM=-_________________*/

if (empty($aid) or $aid == $optionz[0]) {
$admin_toys = array ('Change my level to', 'Chat as nobody', 'Scream as nobody','Give Crazy race to','Change my race to');

if (!empty($_POST['valuer'])){$valuer = clean_post($_POST['valuer']);}else{$valuer='';}
if (!empty($_POST['valued'])){$valued = clean_post($_POST['valued']);}else{$valued='';}
if (!empty($_POST['race'])){$race = clean_post($_POST['race']);}else{$race='';}

if (!empty($valuer)) {

if($admin_toys[0] == $valuer){
if ($valued >= 1){$update_it .= ", `level`='$valued'";}
}elseif($admin_toys[1] == $valuer){
mysqli_query($link, "INSERT INTO `$tbl_board` VALUES(NULL,'$row->server_id','','','','','','','$valued','','$current_time+60')") or die(mysqli_error($link));
}elseif($admin_toys[2] == $valuer){
mysqli_query($link, "INSERT INTO `$tbl_board` VALUES(NULL,'$row->server_id','','','','','','','<center><font size=+2 color=red>$valued</font></center>','','$current_time+60')") or die(mysqli_error($link));
}elseif($admin_toys[3] == $valuer){
mysqli_query($link, "UPDATE `$tbl_members` SET `race`='Crazy' WHERE (`server_id`='$row->server_id' and `charname`='$valued') LIMIT 1") or print("Member doesn't exist or isn't under your controlled world!");
	print 'Crazy race executed..';
}elseif($admin_toys[4] == $valuer){

if(in_array($race,$races_array_keys)) {
mysqli_query($link, "UPDATE `$tbl_members` SET `race`='$race' WHERE (`server_id`='$row->server_id' and `id`='$row->id') LIMIT 1") or print("Member doesn't exist or isn't under your controlled world!");
	print 'Race change applied.';
}else{
	print 'Race is not allowed or does not exist.';
}
}

}
print '<form method=post>
<table width="100%"><tr><th colspan=3>Be aware some options are not reverse able!</th></tr>
<tr><td><select name=valuer>
';
foreach ($admin_toys as $val) {
if($valuer == $val) {
print '<option selected>'.$val.'</option>';
}else{
print '<option>'.$val.'</option>';
}
}
print '
</select></td><td><input type=text name=valued size=32 maxlength=32></td><td><input type=submit name=action value="Do this and do that!"></td></tr></table><select name=race><option>Available Races</option>';

foreach ($races_array_keys as $val) {
	print '<option value="'.$val.'">'.$val.'</option>';
}
print '</select></form>';

/*_______________-=TheSilenT.CoM=-_________________*/
}elseif($aid == $optionz[1]) {
//CHAT as somebody else
print '
<form method=post>
<table width="100%"><tr><th colspan=3>Chat As</th><th><select name=rid>';

if($oresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `timer`>$current_time-10000 and `charname`!='$row->charname' and `ip`!='$row->ip') ORDER BY `level` ASC LIMIT 100")){
$selected='';
while($pobj=mysqli_fetch_object($oresult)){
if (isset($_POST['rid']) and isset($_POST['message']) and !empty($_POST['rid'])) {
	if ($_POST['rid'] == $pobj->id) {
$selected=' SELECTED';
$message = clean_post($_POST['message']);

if($pobj->fp-$current_time>=1){
	if($pobj->id <> 1){
		$star=1;
	}else{
		$star=rand(1,5);
	}
}else{$star=0;}
mysqli_query($link, "INSERT INTO `$tbl_board` VALUES(NULL,'$pobj->server_id','$star','$pobj->clan','$pobj->sex','$pobj->charname','$pobj->race','$pobj->level','$message','$ip','".round($current_time)."')") or die(mysqli_error($link));

$txt_message = 'Chatting as '.$pobj->sex.' '.$pobj->charname;

	}
}
print '<option value="'.$pobj->id.'"'.$selected.'>'.lint($pobj->level).' - '.$pobj->sex.' '.$pobj->charname.' - '.$pobj->race.'</option>';
}
mysqli_free_result($oresult);
}
print '</select></th><th><input type=text name=message maxlength=250></th><th><input type=submit value=Chat></th></tr></table>
</form>
';

if (isset($txt_message)) {
print $txt_message;
}

//CHAT as somebody else
/*_______________-=TheSilenT.CoM=-_________________*/
}elseif($aid == $optionz[2]) {
//SUPER SEX MACHINE
print '
<form method=post>
<table width="100%"><tr><th colspan=3>SSM</th><th><select name=rid>';

if($oresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `timer`>$current_time-10000 and `charname`!='$row->charname' and `ip`!='$row->ip') ORDER BY `level` ASC LIMIT 100")){
$selected='';
while($pobj=mysqli_fetch_object($oresult)){
if (isset($_POST['rid']) and isset($_POST['message']) and !empty($_POST['rid'])) {
	if ($_POST['rid'] == $pobj->id) {
$selected=' SELECTED';
$message = clean_post($_POST['message']);

if (isset($_POST['SSM'])) {
$message = preg_replace("/[^a-zA-Z0-9\s]/", "" , $message);
if (strlen($message) >= 1) {
mysqli_query($link, "UPDATE `$tbl_members` SET `sex`='$message' WHERE `id`=$pobj->id LIMIT 1") or print(mysqli_error($link));
$txt_message = 'Sex spell casted.';
}else{
$txt_message = 'ABORT.';
}
}
	}
}
print '<option value="'.$pobj->id.'"'.$selected.'>'.lint($pobj->level).' - '.$pobj->sex.' '.$pobj->charname.' - '.$pobj->race.'</option>';
}
mysqli_free_result($oresult);
}
print '</select></th><th><input type=text name=message maxlength=12></th><th><input type=submit name=SSM value="Sex Change"></th></tr></table>
</form>Only alpha numeric chars allowed.
';
if (isset($txt_message)) {
print $txt_message;
}
//SUPER SEX MACHINE
/*_______________-=TheSilenT.CoM=-_________________*/
}elseif($aid == $optionz[3]) {
//RACE CHANGER

print '
<form method=post>
<table width="100%"><tr><th colspan=3>Race Changer</th><th><select name=rid>';

if($oresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `timer`>$current_time-10000 and `charname`!='$row->charname' and `ip`!='$row->ip') ORDER BY `level` ASC LIMIT 100")){
$selected='';
while($pobj=mysqli_fetch_object($oresult)){
if (isset($_POST['rid']) and isset($_POST['race']) and !empty($_POST['rid'])) {
	if ($_POST['rid'] == $pobj->id) {
$selected=' SELECTED';
$race = clean_post($_POST['race']);

if (isset($_POST['RCM'])) {

if (strlen($race) >= 1 AND in_array($race,$races_array_keys)) {
mysqli_query($link, "UPDATE `$tbl_members` SET `race`='$race' WHERE `id`=$pobj->id LIMIT 1") or print(mysqli_error($link));
$txt_message ='Race change casted on '.$pobj->sex.' '.$pobj->charname;
}else{
$txt_message = $race.' ABORT.';
}
}
	}
}
print '<option value="'.$pobj->id.'"'.$selected.'>'.lint($pobj->level).' - '.$pobj->sex.' '.$pobj->charname.' - '.$pobj->race.'</option>';
}
mysqli_free_result($oresult);
}
print '</select></th><th><select name=race><option>Available Races</option>';

if (!isset($race)){$race='';}

foreach ($races_array_keys as $val) {
	$selected='';
	if ($race == $val) {$selected=' SELECTED';}
	print '<option value="'.$val.'"'.$selected.'>'.$val.'</option>';
}
print '</select></th><th><input type=submit name=RCM value="Race Change"></th></tr></table></form>';

if (isset($txt_message)) {
print $txt_message;
}

//RACE CHANGER
}elseif($aid == $optionz[4]) {
//CREATE MANAGER

$ppower = array('race'=>'race name','ap'=>'attack power','dp'=>'defending powers','mp'=>'mystical powers','tp'=>'thievery powers','rp'=>'race power','pp'=>'pimp power');

print '<table><tr><td valign=top><table><tr><th colspan=7>MY WORLD RACES</th></tr><tr>';
foreach ($ppower as $key=>$val) {
	print '<th>'.strtoupper($key).'</th>';
}
print '</tr>';

$have_races=0;
$max_races=50;
if (isset($_GET['drid'])) {
	$drid=clean_post($_GET['drid']);
}else{
	$drid='';
}
if($rresult=mysqli_query($link, "SELECT * FROM `$tbl_races` WHERE `server_id`='$row->server_id' ORDER BY `race` ASC LIMIT 10")){
while($robj=mysqli_fetch_object($rresult)){$have_races++;
	if ($drid == $robj->id) {
		mysqli_query($link, "DELETE FROM `$tbl_races` WHERE `id`='$robj->id' LIMIT 1");
	}else{
print '<tr><td nowrap><a href="?sid='.$sid.'&aid='.$aid.'&drid='.$robj->id.'">Delete</a> '.$robj->race.'</td><td>'.$robj->ap.'</td><td>'.$robj->dp.'</td><td>'.$robj->mp.'</td><td>'.$robj->tp.'</td><td>'.$robj->rp.'</td><td>'.$robj->pp.'</td></tr>';
	}
}
mysqli_free_result($rresult);
}



print '</table></td><td valign=top><table><tr><th colspan=5>DEFAULT RACES</th></tr><tr><th>RACE</th><th>AP</th><th>DP</th><th>MP</th><th>TP</th></tr>';
		foreach ($races_array as $key=>$val) {
	print '<tr><td>'.$key.'</td>';
	foreach($val as $ival) {
		print '<td>'.$ival.'</td>';
	}
	print '</tr>';
}print '</table></td><td valign=top>

<form method=post><table><tr><th colspan=2>Race Manager</th></tr><tr><th>Multiplier</th><th>Value</th></tr>';

$pointz=0;
$tip=0;
$powerz=array();
foreach ($ppower as $key=>$val) {
	$valer='';
	if (isset($_POST[$key])){
	$valer=clean_post($_POST[$key]);
	$$key=$valer;
		if ($key !== 'race') {
			if ($valer >= 5 and $valer <= 100) {
				$pointz += $valer;
				$tip++;
			}else{
				$valer = '';
			}
		}
	}
	print '<tr><td>'.$val.'</td><td><input type=text name="'.$key.'" value="'.$valer.'"></td></tr>';
}

//print $pointz;
print '<tr><td>race description</td><td><textarea name=description>'.(isset($_POST['description'])?$_POST['description']:'').'</textarea></td></tr><tr><th colspan=2><input type=submit value="Create Race"></th></tr></table></form>You have 100 points total, each multiplier must have atleast 5 points. Max '.$max_races.' races can be added.
	</td></tr></table>';

if (isset($_POST['description'])) {
	$description=clean_post($_POST['description']);
}else{
	$description='';
}

$races_keys = array_keys($races_array);

if (isset($race)) {
foreach ($races_keys as $val) {
if (preg_match("/$race/i",$val)) {
$pointz=111;
print 'Race already exists!';
break;
}
}
}

if ($pointz <= 100 and !empty($race) and $tip >= 6 and $have_races <= $max_races and !in_array($race,$races_keys)) {
mysqli_query($link, "INSERT INTO `$tbl_races` VALUES(NULL,'$row->server_id','$race','$ap','$dp','$mp','$tp','$rp','$pp','$description','$current_time')") or print ('Race already exists!');//die(mysqli_error($link).'inserto races');
}else{
print (isset($race)?'<center><br><b>Please check your fields again.</center>':'');
}


//RACE MANAGER
/*_______________-=TheSilenT.CoM=-_________________*/
}elseif($aid == $optionz[5]) {
//MERGE WORLDS
require_once('AdMiN/www.merge_worlds.php');
//MERGE WORLDS
}else{
print 'NO Super Admin Toy Selected';
}
		print '</td></tr></table>';

}else{//superadmin
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname Jailed for attempting unautorized accessing the world control panel in $wrow->world_name!','$current_time')");
$update_it .= ", `jail`=$current_time+300";
}
require_once($game_footer);
?>