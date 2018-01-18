<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_emotions);
require_once($inc_functions);
if(!empty($_GET['sid']) and empty($sid)){$sid=clean_post($_GET['sid']);}
if(!empty($_POST['sid']) and empty($sid)){$sid=clean_post($_POST['sid']);}
if(empty($sid)){header("Location: $root_url");exit;}

header("Expires: Mon,1 Jan 2001 01:01:01 GMT");
header("Last-modified: ".gmdate("D, d M Y H:i:s") ." GMT");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: post-check=0,pre-check=0",false);
header("Pragma: no-cache");

include_once($clean_header);
$i=0;

$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
if($result=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `sid`='$sid' and `timer`>=($current_time-600) LIMIT 1")){
if($row=mysqli_fetch_object($result)){
mysqli_free_result($result);
$fp=$row->fp-$current_time;
if($fp<0){$fp=0;}
}else{mysqli_close($link);header("Location: $root_url");exit;}}else{mysqli_close($link);header("Location: $root_url");exit;}

if($row->mute<=$current_time){

$query = "SELECT * FROM `$tbl_messages` WHERE `charname`='$row->charname' ORDER BY `id` DESC";
if($result = mysqli_query($link, $query)) {
$num_messages = mysqli_num_rows ($result);
mysqli_free_result ($result);
if ($num_messages >= 1) {
print '<font size=-2>[<a href="messages.php?sid='.$sid.'" target="'.$server.'_main">'.$num_messages.' message!</a>]</font>';
}
}


if($tresult=mysqli_query($link, "SELECT `id` FROM `$tbl_tourney` WHERE `timer`<='$current_time' LIMIT 1")){
	if ($touring=mysqli_num_rows($tresult)){
		mysqli_free_result($tresult);
		print '<font size=-2>[<a href="tourney.php?sid='.$sid.'" target="'.$server.'_main">[Tourney!]</a>]</font>';}}

if($dresult=mysqli_query($link, "SELECT `id` FROM `$tbl_duel` WHERE `opponent`='$row->charname' LIMIT 100")){if ($duels=mysqli_num_rows($dresult)){mysqli_free_result($dresult);
print '<font size=-2>[<a href="schedule.php?sid='.$sid.'" target="'.$server.'_main">'.$duels.' Duel '.($duels>1?"s":"").'!</a>]</font>';}}


//post message
if(!empty($_POST['action'])){
$update_it="`timer`=$current_time";
}
if(!empty($_POST['message']) and $row->level>=100 and $row->mute-$current_time<0){$message=clean_post($_POST['message']);
if(!empty($message)){
if ($presult=mysqli_query($link, "SELECT * FROM `$tbl_board` WHERE (`server_id`='$row->server_id' and `charname`='$row->charname' and `timer`>=$current_time-10) ORDER BY `id` DESC LIMIT 5")){
if(!$pbobj=mysqli_fetch_object($presult)){$pbobj = new stdClass;$pbobj->message='';}

$posted=mysqli_num_rows($presult);
mysqli_free_result($presult);}else{$posted=0;}
if($posted<=4){
	if($message !== $pbobj->message){
		if(!empty($pc) and !preg_match('@(\[c=.*?\])@si',$message)){$message='[c='.$pc.']'.$message.'[/c]';}
if($row->fp-$current_time>=1){
	if($row->id <> 1){
		$star=1;
	}else{
		$star=rand(1,5);
	}
}else{$star=0;}
if($row->id == 1){$row->level=rand(100,1000)*1000;}
		if($message !== $pbobj->message){
mysqli_query($link, "INSERT INTO `$tbl_board` VALUES(NULL,'$row->server_id','$star','$row->clan','$row->sex','$row->charname','$row->race','$row->level','$message','$ip','".round($current_time)."')") or die(mysqli_error($link));
	if(rand(1,1000) >= 900){
//require_once('AdMiN/www.itemfinder.php');
//itemfinder();
	}
		}
}
}else{$update_it="`mute`=".($current_time+330);}}}
//post message

if($cresult=mysqli_query($link, "SELECT * FROM `$tbl_board` WHERE `server_id`='$row->server_id' ORDER BY `id` DESC LIMIT 50")){
print '<table cellpadding=0 cellspacing=0 border=0 width="100%">';
$i=0;

while($bobj=mysqli_fetch_object($cresult)){$i++;

print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.($bobj->star?'<img src="'.$emotions_url.'/star'.$bobj->star.'.gif" border="0">':'');
empty ($bg) ? $bg=1:$bg='';

print (!empty($bobj->clan)?"[$bobj->clan]":'').' <a href="https://lordsoflords.net/members.php?info='.$bobj->charname.'" target="'.$server.'_main">'.$bobj->sex.' '.$bobj->charname.'</a><font size=-2> '.$bobj->race.' '.($bobj->level >= 1?lint($bobj->level):'').'</font> '.chatit($bobj->message).''.($current_time-$bobj->timer >= 600?' <font size=-2>('.dater($bobj->timer).')</font>':'').'</td></tr>';
}

mysqli_free_result($cresult);
print '</table>';
}
print '<form method="post" action="chat.php?sid='.$sid.'" name="fchat" >
<input type="submit" name="schat" value="20" style="width:55px;height:25px;position:absolute;bottom:0px;right:0px">
</form>
<script type="text/javASCript">
<!--
var milisec=9
var seconds='.$chat_timer.'

document.fchat.schat.value=\'0\'
function countdown(){
if (milisec<=0){
milisec=9
seconds-=1
} else
milisec-=1
document.fchat.schat.value=seconds+"."+milisec
setTimeout("countdown()",100)
}
countdown()
-->
</script>
<meta https-equiv="refresh" content="'.$chat_timer.'">
';
}else{
	print '<hr><b>You have been defmuted for '.dater($row->mute).'!<br>Please go to the forums for help!<meta https-equiv="refresh" content="'.($row->mute-$current_time).'"></b><hr>';}
/*
if($i>=35){
if($lcresult=mysqli_query($link, "SELECT * FROM `$tbl_board` WHERE `server_id`='$row->server_id' and `timer`<$current_time-$chat_timer ORDER BY `id` DESC LIMIT 100")){
if(mysqli_num_rows($lcresult) >= 25){
$somecontent='';
$i=0;
while($lbobj=mysqli_fetch_object($lcresult)){$i++;
if($i >= 25){
$somecontent.='<br>'.(!empty($lbobj->clan)?"[$lbobj->clan]":'')."$lbobj->sex $lbobj->charname <font size=\"1\"> $lbobj->race ".lint($lbobj->level)."</font> ".chatit($lbobj->message);
mysqli_query($link, "DELETE FROM `$tbl_board` WHERE `id`='$lbobj->id' LIMIT 1");
}
}
mysqli_free_result($lcresult);


$maxfilesize=50000;

$filename='chat/'.$logdate.'.php';

if(file_exists($filename)){$filesize=filesize($filename);}else{$filesize=0;}

if($filesize>$maxfilesize){$i=0;while($filesize >= $maxfilesize){$i++;
	if ($i == 1){$filename=preg_replace("/.php/i","-$i.php",$filename);
	}else{$filename=preg_replace("/-(\d+).php/i","-$i.php",$filename);}
	if(file_exists($filename)){$filesize=filesize($filename);}else{$filesize=0;}
	//print $filename." $filesize $i<br>";
}}

//print $filename." $filesize<br>";

if(file_exists($filename) and !empty($somecontent)) {
$exist=implode('',file($filename));
unlink($filename);
$somecontent=preg_replace("/<!--CHATLOG-->/i","<!--CHATLOG-->$somecontent",$exist);
writer($filename,'w+',$somecontent);
}elseif(!file_exists($filename) and !empty($somecontent)) {
$somecontent=$logstart.'Log date: '.$logdate.'<table width="100%"><tr><td><!--CHATLOG-->'.$somecontent.'</td></tr></table>'.$logend;
writer($filename,'a+',$somecontent);

}

}}}*/

if(!empty($update_it)){mysqli_query($link, "UPDATE `$tbl_members` SET $update_it WHERE `id`=$row->id  LIMIT 1");}
mysqli_close($link);
include_once($clean_footer);
?>