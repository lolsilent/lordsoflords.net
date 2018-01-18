<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_emotions);
require_once($inc_functions);
require_once($game_header);

if ($row->mute <= $current_time) {
require_once('AdMiN/functions.messaging2.php');
$gid = message_gid($server); //	2 meadow2
//print '<hr>'.$gid;

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

//RECIPIENTS SERVER DEPENDEND
if(isset($_GET['create'])){

$alfa='';
$recipient='';

if(!empty($_POST['alfa'])){
	$alfa=message_clean($_POST['alfa']);
	if(strlen($alfa) < 2){
		$alfa='';
	}
}
if(!empty($_POST['recipient'])){
	$recipient=message_clean($_POST['recipient']);
}

$recipient_select = '';

if (!empty($alfa)) {

if($presult = mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `charname`!='$row->charname' and `charname` LIKE CONVERT (_utf8 '%$alfa%' USING latin1) COLLATE latin1_swedish_ci ORDER BY `charname` ASC LIMIT 100")){
if(mysqli_num_rows($presult) >= 1) {
$recipient_select = '<select name=recipient>';
	while ($prow = mysqli_fetch_object ($presult)) {
if ($recipient == $prow->id) {
$recipient_select .= '<option value="'.$prow->id.'" selected>'.$prow->sex.' '.$prow->charname.'</option>';
}else{
$recipient_select .= '<option value="'.$prow->id.'">'.$prow->sex.' '.$prow->charname.'</option>';
}
	}
$recipient_select .= '</select>';
}
mysqli_free_result ($presult);
}

}


}
//RECIPIENTS SERVER DEPENDED

//REMOVE OLD MESSAGES
if($mresult=mysqli_query($link, "SELECT `id` FROM `$tbl_messages` WHERE (`timer`>'$current_time'+'$message_days_secs') ORDER BY `id` DESC LIMIT 1")){

if (mysqli_num_rows($mresult) >= 1) {
mysqli_query($link, "DELETE FROM `$tbl_messages` WHERE (`timer`>'$current_time'+'$message_days_secs') LIMIT 1000");
}
mysqli_free_result ($mresult);

}
//REMOVE OLD MESSAGES

//require_once($_SERVER['DOCUMENT_ROOT'].'/fatality/aaa.functions.php');

//TESTTESTTESTTESTTESTTESTTESTTEST


/*if (!isset($link)) {
require_once($_SERVER['DOCUMENT_ROOT'].'/fatality/aaa.mysql.php');
$link=mysqli_connect($db_host,$db_user,$db_password) or die(mysqli_error($link).'Database offline.');
}*/
mysqli_select_db($link,$dbm_main) or die(mysqli_error($link).'Database offline.');


print '<table cellpadding=2 cellspacing=2 border=0 width=100%><tr><th colspan=2>Messaging Service</th></tr><tr><td width=125 valign=top>
<a href="?sid='.$sid.'&create">Create Message</a><br><br>';

foreach ($message_functions as $val) {
	if (in_array($val,$status_array)) {
		message_amount($val);
	}
}

print '</td><td valign=top>';

if(isset($_GET['forward'])){
	message_forward($row->id);
}elseif(isset($_GET['reply'])){
	message_reply($row->id);
}elseif(isset($_GET['create'])){
	message_create($row->id);
}elseif(isset($_GET['outbox'])){
	message_outbox($row->id);
}elseif(isset($_GET['deleted'])){
	message_deleted($row->id);
}else{
	message_inbox($row->id);
}
print '</td></tr></table>';

//if (isset($link)) {mysqli_close($link);}

//TESTTESTTESTTESTTESTTESTTESTTEST


/*
FUNCTIONS :
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

mysqli_select_db($link,$db_main) or die(mysqli_error($link).'Database offline.');
} else {
print 'You have been muted from the chat board and messages for spamming, begging, or doing or saying something what we don\'t like. Please behave yourself next time.';
} //muted
require_once($game_footer);
?>