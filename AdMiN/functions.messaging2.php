<?php 
#!/usr/local/bin/php
/*
###_______________-=TheSilenT.CoM=-_______________###
Project name	: Messaging Funcion
Script name	: Script name
Version		: 1.00
Release date	: 2-15-2008 03:32:55
Last Update	: 2-15-2008 03:32:55
Email		: admin@thesilent.com
Homepage	: https://thesilent.com
Created by	: TheSilent
Last modified by	: TheSilent
###_______________COPYRIGHT NOTICE_______________###
# Redistributing this software in part or in whole strictly prohibited.
# You may use and modified my software as you wish. 
# If you want to make money from my work please ask first. 
# By using this free software you agree not to blame me for any
# liability that might arise from it's use.
# In all cases this copyright notice and the comments above must remain intact.
# Copyright (c) 2001 TheSilenT.CoM.  All Rights Reserved.
###_______________COPYRIGHT NOTICE_______________###
*/
$dbm_main = 'messaging';
$tbl_messages = 'messages';
$fld_messages = '`id`,`gid`,`mid`,`rid`,`body`,`importance`,`status`,`dater`,`delay_timer`,`timer`';

$message_days = 30; //days to keep messages
$message_days_secs = $message_days*86400;//days to keep messages UNIX
$message_limit = 100; //max number of messages to keep/display

$delay_timer = 5; //wait seconds before the second message is allowed to send

$current_time = time();
$current_date = date('d M Y H:m:s');

$message_functions = array('inbox', 'outbox', 'deleted');
$status_array = array(
'inbox' => 0,
'outbox' => 1,
'deleted' => 2,
);
/*
FUNCTIONS :
message_gid
message_amount
message_inbox
message_outbox
message_deleted

message_remove
message_create
message_reply
message_forward
message_alert
message_report
message_post
message_clean
message_dater
*/

/*
MYSQL FIELDS
`id`
`gid` game id

	
	
`mid` member id
`rid` recipient id
`body`
`importance`
	0 standard
	1 support message
	2 mod message
	3 cop message
	4 admin message
	5 supermin message
	6 site news message
`status`
	0 inbox
	0 outbox
	2 deleted
	3 removed
	4 send
	5 reply
	6 forward

`dater` datestamp
`delay_timer` datestamp
`timer` timestamp
*/

/*_______________-=TheSilenT.CoM=-_________________*/

function message_gid($in) {

if (empty($in)) {
	$gid = 0;//	0 fatality SANDBOX
}elseif($in == 'Meadow'){
	$gid = 1; // 1 meadow
}elseif($in == 'MeadowII'){
	$gid = 2; //	2 MeadowII
}elseif($in == 'Eidolon'){
	$gid = 3; //	3 eidolon
}elseif($in == 'Xedon'){
	$gid = 4; //	4 xedon

}elseif($in == 'Duel'){
	$gid = 5; //	5 duel
}elseif($in == 'Devlab'){
	$gid = 6; //	6 devlab
}elseif($in == 'Evolve'){
	$gid = 7; //	7 evolve
}elseif($in == 'Euro'){
	$gid = 8; //	8 euro
}elseif($in == 'Tourney'){
	$gid = 9; //	9 tourney

}elseif($in == 'Ysomite'){
	$gid = 10; //	10 ysomite

}elseif($in == 'Shadow'){
	$gid = 11; //	11 shadow
}elseif($in == 'ShadowII'){
	$gid = 12; //	12 ShadowII

}elseif($in == 'Net'){
	$gid = 13; //	13 net

}elseif($in == 'History'){
	$gid = 14; //	14 history
}elseif($in == 'rpgtext'){
	$gid = 15; //	15 rpgtext
}elseif($in == 'warunit'){
	$gid = 16; //	16 warunit
}elseif($in == 'megod'){
	$gid = 17; //	17 megod
}elseif($in == 'mobunit'){
	$gid = 18; //	18 mobunit
}elseif($in == 'project'){
	$gid = 19; //	19 project x5
}elseif($in == 'wargame'){
	$gid = 20; //	20 wargame
}elseif($in == 'newconflict'){
	$gid = 21; //	21 newconflict
}elseif($in == 'skillgames'){
	$gid = 22; //	22 skillgames
}elseif($in == 'jackpot'){
	$gid = 23; //	23 jackpot
}elseif($in == 'humanimals'){
	$gid = 24; //	24 humanimals
}elseif($in == 'forums'){
	$gid = 25; //	25 forums
}elseif($in == 'thesilent'){
	$gid = 26; //	26 thesilent
}else{
	$gid=0;
}
return $gid;
}
/*_______________-=TheSilenT.CoM=-_________________*/

function message_amount($in) {
	global $link, $sid,$row,$tbl_messages,$gid,$current_time,$message_limit,$status_array;
$linkstart = '?sid='.$sid.'&'.$in;
if ($in == 'inbox') {
	$mid_rid = 'rid';
}else{
	$mid_rid = 'mid';
}

$mquery="SELECT `id` FROM `$tbl_messages` WHERE (`gid`='$gid' AND `$mid_rid`='$row->id' AND `status`='".$status_array[$in]."'  AND `delay_timer`<='$current_time') ORDER BY `id` DESC LIMIT $message_limit";

if($mresult=mysqli_query($link, $mquery)){
	$num_rows = mysqli_num_rows($mresult);
print '<a href="'.$linkstart.'">'.ucfirst($in).'</a> <sup>('.number_format($num_rows).''.($num_rows >=1 ?'<a href="'.$linkstart.'&delete_all" title="Delete all messages"> empty</a>':'').')</sup><br>';
	
mysqli_free_result($mresult);
}

}
/*_______________-=TheSilenT.CoM=-_________________*/

function message_inbox($mid) {
	global $link, $sid,$row,$tbl_messages,$gid,$current_time,$message_limit;
$linkstart = '?sid='.$sid.'&inbox';

$mquery="SELECT * FROM `$tbl_messages` WHERE (`gid`='$gid' AND `mid` AND `rid`='$row->id' AND `status`='0' AND `delay_timer`<='$current_time') ORDER BY `id` DESC LIMIT $message_limit";

if($mresult=mysqli_query($link, $mquery)) {
if (mysqli_num_rows($mresult) >= 1) {
	while ($mrow=mysqli_fetch_object($mresult)){
		$linkstart .='&did='.$mrow->id;
print '<table width=100%><tr><th><a href="'.$linkstart.'&reply">Reply</a></th><th><a href="'.$linkstart.'&create&forward">Forward</a></th><th><a href="'.$linkstart.'&delete">Delete</a></th><th>'.$mrow->dater.'</th></tr><tr><td colspan=4>'.message_post($mrow->body).' - '.message_dater($mrow->timer).' ago';

if(isset($_GET['delete']) or isset($_GET['delete_all'])){
	if(!empty($_GET['did']) or isset($_GET['delete_all'])) {
		if (empty($_GET['did'])){$_GET['did']='';}
		if($_GET['did'] == $mrow->id or isset($_GET['delete_all'])) {
mysqli_query($link, "UPDATE `$tbl_messages` SET `status`='2',`mid`='$row->id', `rid`='0' WHERE `id`='$mrow->id' LIMIT 1") or print(mysqli_error($link).'3');
print '<p>Message Deleted</p>';
		}
	}
}

print '</td></tr></table>';
	}
	mysqli_free_result($mresult);
} else {
print '<p>Your inbox is empty.</p>';
}
}

}

/*_______________-=TheSilenT.CoM=-_________________*/

function message_outbox($mid) {
	global $link, $sid,$row,$tbl_messages,$gid,$current_time,$message_limit,$db_main,$dbm_main,$tbl_members;
$linkstart = '?sid='.$sid.'&outbox';

$mquery="SELECT * FROM `$tbl_messages` WHERE (`gid`='$gid' AND `rid` AND `mid`='$row->id' AND `status`='0' AND `delay_timer`<='$current_time') ORDER BY `id` DESC LIMIT $message_limit";

$arid = array();
$out_boxed = '';
if($mresult=mysqli_query($link, $mquery)) {
if (mysqli_num_rows($mresult) >= 1) {
	while ($mrow=mysqli_fetch_object($mresult)){
		if(!in_array($mrow->rid,$arid)) {
			$arid[$mrow->rid]='@\[ARID\]'.$mrow->rid.'\[\/ARID\]@si';
		}
		$linkstart .='&did='.$mrow->id;
$out_boxed .= '<table width=100%><tr><th><a href="'.$linkstart.'&create&forward">Forward</a></th><th><a href="'.$linkstart.'&delete">Delete</a></th><th>[ARID]'.$mrow->rid.'[/ARID]</th><th>'.$mrow->dater.'</th></tr><tr><td colspan=4>'.message_post($mrow->body).' - '.message_dater($mrow->timer).' ago';

if(isset($_GET['delete']) or isset($_GET['delete_all'])){
	if(!empty($_GET['did']) or isset($_GET['delete_all'])) {
		if (empty($_GET['did'])){$_GET['did']='';}
		if($_GET['did'] == $mrow->id or isset($_GET['delete_all'])) {
mysqli_query($link, "UPDATE `$tbl_messages` SET `status`='2',`mid`='$row->id', `rid`='0' WHERE `id`='$mrow->id' LIMIT 1") or print(mysqli_error($link).'3');
$out_boxed .= '<p>Message Deleted</p>';
		}
	}
}
$out_boxed .= '</td></tr></table>';
	}
	mysqli_free_result($mresult);

//RECIPIENT
$mids = '';
ksort($arid);
foreach ($arid as $key=>$val) {
if (!empty($mids)) {$mids .= ' OR ';}
$mids .= "`id`='$key'";
}
//print $mids;

$minfo = array();
mysqli_select_db($link,$db_main) or die(mysqli_error($link).'Database offline.');

$iquery="SELECT `id`,`clan`,`sex`,`charname` FROM `$tbl_members` WHERE ($mids) ORDER BY `id` ASC LIMIT $message_limit";
if($iresult=mysqli_query($link, $iquery)) {
if (mysqli_num_rows($iresult) >= 1) {
	while ($irow=mysqli_fetch_object($iresult)){
		//$minf= $irow->id.'';
		$minf='';
		if (!empty($irow->clan)) {
			$minf .= '['.$irow->clan.'] ' ;
		}
			$minf .= $irow->sex.' '.$irow->charname;
		$minfo[$irow->id]=$minf;
	}
	mysqli_free_result($iresult);
}
}
mysqli_select_db($link,$dbm_main) or die(mysqli_error($link).'Database offline.');

//print '<hr>';print_r($arid);print '<hr>';print_r($minfo);print '<hr>';
ksort($minfo);

print preg_replace (array_values($arid),array_values($minfo),$out_boxed);
//RECIPIENT
//print $out_boxed;
} else {
print '<p>Your outbox is empty.</p>';
}
}
}

/*_______________-=TheSilenT.CoM=-_________________*/

function message_deleted($mid) {
	global $link, $sid,$row,$tbl_messages,$gid,$current_time,$message_limit;
$linkstart = '?sid='.$sid.'&deleted';

$mquery="SELECT * FROM `$tbl_messages` WHERE (`gid`='$gid' AND !`rid` AND `mid`='$row->id' AND `status`='2' AND `delay_timer`<='$current_time') ORDER BY `id` DESC LIMIT $message_limit";

if($mresult=mysqli_query($link, $mquery)) {
if (mysqli_num_rows($mresult) >= 1) {
	while ($mrow=mysqli_fetch_object($mresult)){
		$linkstart .='&did='.$mrow->id;
print '<table width=100%><tr><th><a href="'.$linkstart.'&create&forward">Forward</a></th><th><a href="'.$linkstart.'&delete">Permanent Delete</a></th><th>'.$mrow->dater.'</th></tr><tr><td colspan=5>'.message_post($mrow->body).' - '.message_dater($mrow->timer).' ago';

if(isset($_GET['delete']) or isset($_GET['delete_all'])){
	if(!empty($_GET['did']) or isset($_GET['delete_all'])) {
		if (empty($_GET['did'])){$_GET['did']='';}
		if($_GET['did'] == $mrow->id or isset($_GET['delete_all'])) {
mysqli_query($link, "DELETE FROM `$tbl_messages` WHERE `id`='$mrow->id' LIMIT 1");
print '<p>Permanent Deleted Message</p>';
		}
	}
}

print '</td></tr></table>';
	}
	mysqli_free_result($mresult);
} else {
print '<p>Your have no deleted messages.</p>';
}
}

}

/*_______________-=TheSilenT.CoM=-_________________*/

function message_create($mid) {
	global $link, $row,$tbl_messages,$fld_messages,$gid,$current_time,$current_date,$message_limit,$alfa,$recipient_select;

$message='';
if(!empty($_POST['message'])){
	$message=message_clean($_POST['message']);
}
if(!empty($_POST['recipient'])){
	$recipient=message_clean($_POST['recipient']);
}

$max_characters = 5000;
$delivery_time = 5;

if (empty($alfa) or empty($recipient_select)) {
print '<form method=post>
<table width=250>
<tr><td width=50% align=right><input type=text name=alfa value="'.$alfa.'" maxlength=10></td><td><input type=submit value="Find player"></td></tr>
</table>Use at least two characters to find a player containing these characters.
</form>';
if (!empty($alfa) and empty($recipient_select)) {
print 'No players found containing your search term.';
}
}else{
print '<form method=post name=message_form>
<table width=100%><tr><th colspan=3>Create a new message</th></tr>
<input type=hidden name=alfa value="'.$alfa.'" maxlength=10>
<tr><td width=100>Recipient</td><td colspan=2>'.$recipient_select.'</td></tr>
<tr><td width=100 valign=top>Message</td><td colspan=2><textarea cols=50 rows=5 onKeyDown="count(document.message_form.message,document.message_form.counter,'.$max_characters.')"
onKeyUp="count(document.message_form.message,document.message_form.counter,'.$max_characters.')" name=message>'.$message.'</textarea>
</td></tr>
<tr><td>Characters left</td><td><input disabled readonly type="text" name="counter" size="5" maxlength="5" value="'.($max_characters-strlen($message)).'">
</td><td><input type=submit value="Send message"></td></tr>
</table>
</form>
<script language="javascript">
<!--
function count(field,counter,maxlimit) {
if (field.value.length > maxlimit)
field.value = field.value.substring(0, maxlimit);
else
counter.value = maxlimit - field.value.length;
}
//-->
</script>
';
}

//SEND MESSAGE
if (!empty($message) and !empty($recipient)) {
	if (strlen($message) <= $max_characters) {

$last_send=0;
if($pmresult=mysqli_query($link, "SELECT `id` FROM `$tbl_messages` WHERE (`delay_timer`>'$current_time') ORDER BY `id` DESC LIMIT 1")){
$last_send = mysqli_num_rows($pmresult);
mysqli_free_result ($pmresult);
}

		if ($last_send <= 0) {

$message .= '

Signed by,
';
if (!empty($row->clan)) {
$message .= '['.$row->clan.']' ;
}
$message .= $row->sex.' '.$row->charname.' <font size=-2>'.$row->race.' '.number_format($row->level).'</font>';

	mysqli_query($link, "INSERT INTO `$tbl_messages` ($fld_messages) VALUES (NULL, '$gid', '$row->id', '$recipient', '$message', '0', '0', '$current_date', $current_time+$delivery_time, $current_time)") or print(mysqli_error($link).' 1000');

	mysqli_query($link, "INSERT INTO `$tbl_messages` ($fld_messages) VALUES (NULL, '$gid', '$row->id', '$recipient', '$message', '0', '1', '$current_date', $current_time+$delivery_time, $current_time)") or print(mysqli_error($link).' 1001');
	
	//TEST SEND A COPY TO MYSELF
	//mysqli_query($link, "INSERT INTO `$tbl_messages` ($fld_messages) VALUES (NULL, '$gid', '$recipient', '$row->id', '$message', '0', '0', '$current_date', $current_time+$delivery_time, $current_time)") or print(mysqli_error($link).' 1010');
	print '<font color=red>Your message has been handed over to the postman, it will take a few seconds to deliver.</font>';
		}else{
			print '<font color=red>Postman has just left the building, please wait a few seconds before sending a message.</font>';
		}
	}else{
	print '<font color=red>Your message is too long.</font>';
	}
}
//SEND MESSAGE
}

/*_______________-=TheSilenT.CoM=-_________________*/

function message_reply($mid) {
		global $link, $sid,$row,$tbl_messages,$fld_messages,$gid,$current_time,$current_date,$message;

if(!empty($_GET['did'])) {

if(!empty($_GET['did'])){
	$did = clean_post($_GET['did']);
}

$mquery="SELECT * FROM `$tbl_messages` WHERE `id`='$did' ORDER BY `id` DESC LIMIT 1";

if($mresult=mysqli_query($link, $mquery)) {
if (mysqli_num_rows($mresult) >= 1) {
if ($mrow=mysqli_fetch_object($mresult)){
mysqli_free_result ($mresult);

$message='';
if(!empty($_POST['message'])){
	$message=message_clean($_POST['message']);
}

$max_characters = 5000;
$delivery_time = 5;

print '<form method=post action="?sid='.$sid.'&reply&did='.$did.'" name=message_form>
<table width=100% border=0>
<tr><th colspan=3>Create a reply message</th></tr>
<tr><td width=100 valign=top>Message</td><td colspan=2><textarea cols=50 rows=5 onKeyDown="count(document.message_form.message,document.message_form.counter,'.$max_characters.')"
onKeyUp="count(document.message_form.message,document.message_form.counter,'.$max_characters.')" name=message>'.$message.'</textarea>
</td></tr>
<tr><td>Characters left</td><td><input disabled readonly type="text" name="counter" size="5" maxlength="5" value="'.($max_characters-strlen($message)).'">
</td><td><input type=submit value="Send reply"></td></tr>
</table>';

//SEND MESSAGE
if (!empty($message)) {
	if (strlen($message) <= $max_characters) {

$last_send=0;
if($pmresult=mysqli_query($link, "SELECT `id` FROM `$tbl_messages` WHERE (`delay_timer`>'$current_time') ORDER BY `id` DESC LIMIT 1")){
$last_send = mysqli_num_rows($pmresult);
mysqli_free_result ($pmresult);
}

		if ($last_send <= 0) {

$message .= '

Replied by,
';
if (!empty($row->clan)) {
$message .= '['.$row->clan.']' ;
}
$message .= $row->sex.' '.$row->charname.' <font size=-2>'.$row->race.' '.number_format($row->level).'</font>';

	mysqli_query($link, "INSERT INTO `$tbl_messages` ($fld_messages) VALUES (NULL, '$gid', '$row->id', '$mrow->mid', '$message', '0', '0', '$current_date', $current_time+$delivery_time, $current_time)") or print(mysqli_error($link).' 1200');

	mysqli_query($link, "INSERT INTO `$tbl_messages` ($fld_messages) VALUES (NULL, '$gid', '$row->id', '$mrow->mid', '$message', '0', '1', '$current_date', $current_time+$delivery_time, $current_time)") or print(mysqli_error($link).' 1020');
	
	//TEST SEND A COPY TO MYSELF
	//mysqli_query($link, "INSERT INTO `$tbl_messages` ($fld_messages) VALUES (NULL, '$gid', '$mrow->mid', '$row->id', '$message', '0', '0', '$current_date', $current_time+$delivery_time, $current_time)") or print(mysqli_error($link).' 1002');
	print '<font color=red>Your message has been handed over to the postman, it will take a few seconds to deliver.</font>';
		}else{
			print '<font color=red>Postman has just left the building, please wait a few seconds before sending a message.</font>';
		}
	}else{
	print '<font color=red>Your message is too long.</font>';
	}
}
//SEND MESSAGE

print '<table width=100% border=0>
<tr><th>Replying on this message</th><th>'.$mrow->dater.'</th></tr>
<tr><td colspan=2>'.message_post($mrow->body).' - '.message_dater($mrow->timer).' ago</td></tr></table>
</form>
<script language="javascript">
<!--
function count(field,counter,maxlimit) {
if (field.value.length > maxlimit)
field.value = field.value.substring(0, maxlimit);
else
counter.value = maxlimit - field.value.length;
}
//-->
</script>
';

}
}
}

}

}

/*_______________-=TheSilenT.CoM=-_________________*/

function message_forward($mid) {
		global $link, $row,$tbl_messages,$fld_messages,$gid,$current_time,$current_date,$message,$alfa,$recipient_select,$recipient,$message;

if(!empty($_GET['did'])) {

if(!empty($_GET['did'])){
	$did = clean_post($_GET['did']);
}

$mquery="SELECT * FROM `$tbl_messages` WHERE `id`='$did' ORDER BY `id` DESC LIMIT 1";

if($mresult=mysqli_query($link, $mquery)) {
if (mysqli_num_rows($mresult) >= 1) {
if ($mrow=mysqli_fetch_object($mresult)){
mysqli_free_result ($mresult);

$message='';
if(!empty($_POST['message'])){
	$message=message_clean($_POST['message']);
}

$max_characters = 5000-strlen($mrow->body);
$delivery_time = 5;

if (empty($alfa) or empty($recipient_select)) {
print '<form method=post>
<table width=250>
<tr><td width=50% align=right><input type=text name=alfa value="'.$alfa.'" maxlength=10></td><td><input type=submit value="Find player"></td></tr>
</table>Use at least two characters to find a player containing these characters.
</form>';
if (!empty($alfa) and empty($recipient_select)) {
print 'No players found containing your search term.';
}
}else{
print '<form method=post name=message_form>
<table width=100%><tr><th colspan=3>Forwarding</th></tr>
<input type=hidden name=alfa value="'.$alfa.'" maxlength=10>
<tr><td width=100>Recipient</td><td colspan=2>'.$recipient_select.'</td></tr>
<tr><td width=100 valign=top>Add comments</td><td colspan=2><textarea cols=50 rows=5 onKeyDown="count(document.message_form.message,document.message_form.counter,'.$max_characters.')"
onKeyUp="count(document.message_form.message,document.message_form.counter,'.$max_characters.')" name=message>'.$message.'</textarea>
</td></tr>
<tr><td>Characters left</td><td><input disabled readonly type="text" name="counter" size="5" maxlength="5" value="'.($max_characters-strlen($message)).'">
</td><td><input type=submit value="Send message"></td></tr>
</table>
</form>
<script language="javascript">
<!--
function count(field,counter,maxlimit) {
if (field.value.length > maxlimit)
field.value = field.value.substring(0, maxlimit);
else
counter.value = maxlimit - field.value.length;
}
//-->
</script>
';
}

//SEND MESSAGE
if (!empty($message) and !empty($recipient)) {
	if (strlen($message) <= $max_characters) {

$last_send=0;
if($pmresult=mysqli_query($link, "SELECT `id` FROM `$tbl_messages` WHERE (`delay_timer`>'$current_time') ORDER BY `id` DESC LIMIT 1")){
$last_send = mysqli_num_rows($pmresult);
mysqli_free_result ($pmresult);
}

		if ($last_send <= 0) {

$message .= '

Forwarded by,
';
if (!empty($row->clan)) {
$message .= '['.$row->clan.']' ;
}
$message .= $row->sex.' '.$row->charname.' <font size=-2>'.$row->race.' '.number_format($row->level).'</font>';

	mysqli_query($link, "INSERT INTO `$tbl_messages` ($fld_messages) VALUES (NULL, '$gid', '$row->id', '$recipient', '$mrow->body<hr>$message', '0', '0', '$current_date', $current_time+$delivery_time, $current_time)") or print(mysqli_error($link).' 1300');
	
	mysqli_query($link, "INSERT INTO `$tbl_messages` ($fld_messages) VALUES (NULL, '$gid', '$row->id', '$recipient', '$mrow->body<hr>$message', '0', '1', '$current_date', $current_time+$delivery_time, $current_time)") or print(mysqli_error($link).' 1030');
	
	//TEST SEND A COPY TO MYSELF
	//mysqli_query($link, "INSERT INTO `$tbl_messages` ($fld_messages) VALUES (NULL, '$gid', '$recipient', '$row->id', '$message', '0', '0', '$current_date', $current_time+$delivery_time, $current_time)") or print(mysqli_error($link).' 1003');
	print '<font color=red>Your message has been handed over to the postman, it will take a few seconds to deliver.</font>';
		}else{
			print '<font color=red>Postman has just left the building, please wait a few seconds before sending a message.</font>';
		}
	}else{
	print '<font color=red>Your message is too long.</font>';
	}
}
//SEND MESSAGE

print '<table width=100% border=0>
<tr><th>Forwarding this message</th><th>'.$mrow->dater.'</th></tr>
<tr><td colspan=2>'.message_post($mrow->body).' - '.message_dater($mrow->timer).' ago</td></tr></table>
</form>
<script language="javascript">
<!--
function count(field,counter,maxlimit) {
if (field.value.length > maxlimit)
field.value = field.value.substring(0, maxlimit);
else
counter.value = maxlimit - field.value.length;
}
//-->
</script>
';

}
}
}

}
}

/*_______________-=TheSilenT.CoM=-_________________*/

function message_alert($mid) {
}

/*_______________-=TheSilenT.CoM=-_________________*/

function message_report($mid) {
}

/*_______________-=TheSilenT.CoM=-_________________*/

function message_post($in) {
global $emotions;

$emotions_url = '/images/emotions';

$hi=array (
'@\n@si',
'@\[quote\](.*?)\[/quote\]@si',
'@\[img\](https:\/\/.*?)\[/img\]@si',
'@\[url\](https:\/\/.*?)\[/url\]@si',
'@\[url=(https:\/\/.*?)\](.*?)\[/url\]@si',
'@\[email\](.*?\@.*?\..*?)\[/email\]@si',
'@\[c=(.*?)\](.*?)\[/c\]@si',
);

$ha=array (
'<br>',
'<blockquote><hr>\1<hr></blockquote>',
'<img src="\1" border=0>',
'<a href="\1" target="_blank">\1</a>',
'<a href="\1" target="_blank">\2</a>',
'<a href="mailto:\1\">\1</a>',
'<font color="\1">\2</font>',
);
$in=preg_replace($hi, $ha, $in);

if(preg_match("/\[.*?\]/i",$in)){
foreach($emotions as $face){
if(in_array($face,$emotions)){
$face=strtolower($face);
$in=preg_replace("'\[$face\]'i","<img src=\"$emotions_url/$face.gif\" border=\"0\">",$in);
}}}

return stripslashes($in);
}

/*_______________-=TheSilenT.CoM=-_________________*/

function message_clean ($in){

$in=htmlentities("$in",ENT_QUOTES);
$in=strip_tags($in);
$in=trim($in);
$in=addslashes($in);
return $in;
}

/*_______________-=TheSilenT.CoM=-_________________*/

function message_dater($secs){
global $current_time;
$s='';$i=0;
if ($current_time-$secs < 0){
$secs=round($secs-$current_time);
}else{
$secs=round($current_time-$secs);
}

if($secs>= 3600){
$n=(int)($secs/3600);$s .=($n<=9?'0':'').$n.':';$secs %= 3600;
}else{$s.='00:';}

if($secs>= 60){
$n=(int)($secs/60);$s .=($n<=9?'0':'').$n.':';$secs %= 60;
}else{$s.='00:';}

if($secs>=1){
$s .=($secs<=9?'0':'').$secs;
}elseif($secs<=0){
$s .='00';
}
return trim($s);
}


/*_______________-=TheSilenT.CoM=-_________________*/
?>