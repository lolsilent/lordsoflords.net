<?php 
#!/usr/local/bin/php

if(!empty($_GET['sid']) and empty($sid)){$sid=clean_post($_GET['sid']);}
if(!empty($_POST['sid']) and empty($sid)){$sid=clean_post($_POST['sid']);}
if(empty($sid)){header("Location: $root_url/login.php");exit;}

require_once($inc_mysql);
$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);

//MEMBER
if($result=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `sid`='$sid' and `timer`>=($current_time-600) LIMIT 1")){
if($row=mysqli_fetch_object($result)){
mysqli_free_result($result);

$fp=$row->fp-$current_time;
if($fp<0){$fp=0;}

if(empty($_SERVER['HTTP_REFERER']) AND $fp<0){header("Location: $root_url");}

$update_it="`timer`=$current_time";

}else{mysqli_close($link);header("Location: $root_url/login.php");exit;}
}else{mysqli_close($link);header("Location: $root_url/login.php");exit;}
//MEMBER

//WORLD
if($wresult=mysqli_query($link, "SELECT * FROM `$tbl_aservers` WHERE `id`='$row->server_id' ORDER BY `id` DESC LIMIT 1")){
if($wrow=mysqli_fetch_object($wresult)){
mysqli_free_result($wresult);

if($wrow->killing_spree_max >= 1) {$killing_spree_max=$wrow->killing_spree_max;}
if($wrow->max_player_per_ip >= 1) {$max_player_per_ip=$wrow->max_player_per_ip;}
if($wrow->fp_bonus_max >= 1) {$fp_bonus_max=$wrow->fp_bonus_max;}
if($wrow->chat_timer >= 1) {$chat_timer=$wrow->chat_timer;}else{$chat_timer='Disabled';}
if ($row->charname == $wrow->admin_name){$row->jail = 0; $row->mute = 0;}

}else{mysqli_close($link);header("Location: $root_url/login.php");exit;}
}else{mysqli_close($link);header("Location: $root_url/login.php");exit;}
//WORLD

header("Expires: Mon,1 Jan 2001 01:01:01 GMT");
header("Last-modified: ".gmdate("D, d M Y H:i:s") ." GMT");
header("Cache-Control: no-store,no-cache,must-revalidate");
header("Cache-Control: post-check=0,pre-check=0",false);
header("Pragma: no-cache");

if(isset($_COOKIE['pc']) and !empty($_COOKIE['pc'])){$pc=clean_post($_COOKIE['pc']);}else{$pc='';}

if(isset($_COOKIE['bg']) and !empty($_COOKIE['bg'])){$colbg=clean_post($_COOKIE['bg']);}
if(isset($_COOKIE['text']) and !empty($_COOKIE['text'])){$coltext=clean_post($_COOKIE['text']);}
if(isset($_COOKIE['alink']) and !empty($_COOKIE['alink'])){$collink=clean_post($_COOKIE['alink']);}
if(isset($_COOKIE['th']) and !empty($_COOKIE['th'])){$colth=clean_post($_COOKIE['th']);}
if(isset($_COOKIE['form']) and !empty($_COOKIE['form'])){$colform=clean_post($_COOKIE['form']);}
if(isset($_COOKIE['family']) and !empty($_COOKIE['family'])){$fontfamily=clean_post($_COOKIE['family']);}
if(isset($_COOKIE['fsize']) and !empty($_COOKIE['fsize'])){$fontsize=clean_post($_COOKIE['fsize']);}
print '<html><head>
<title>'.$title.' MMORPG</title>';
include_once($html_style);
print '</head>
<body bgcolor="'.$colbg.'" text="'.$coltext.'" link="'.$collink.'" vlink="'.$collink.'" alink="'.$collink.'"><center>';


if($row->jail >= $current_time){
print '<hr><b>Server Overload Protection<br>You have been jailed for violating our rules, jail time '.dater($row->jail).'!<br>Please go to the forums for help!
<meta https-equiv="refresh" content="'.(($row->jail-$current_time)+5).'"></b><hr>';

if($row->jail-$current_time > 50){
print '<script>alert("You have been jailed for violating our rules, jail time '.number_format($row->jail-$current_time).' seconds!  \n\n Please go to the forums for help!. \n\n Violation of the rules may get your account deleted. \n\n Possible mass clicking detected. \n\n If this does not apply for your chars, where are very sorry for what has happend to you. \n\n Just click away this screen and relogin to play.\n\n")</script>';
}

include_once($game_footer);exit;
}

print '<table cellpadding="1" cellspacing="1" border="0" width="100%"><tr><th>Level '.lint($row->level).'</th><th>Life '.lint($row->life).'</th><th>'.lint($row->xp).' XP</th><th>$'.lint($row->gold).'</th>'.($fp>1?'<th>'.number_format($fp).' FP</th>':'').'</tr></table>';

//INJECT RACES
if (isset($races_array)) {
	if($rresult=mysqli_query($link, "SELECT * FROM `$tbl_races` WHERE (`server_id`='$row->server_id' and `race`='$row->race') ORDER BY `race` ASC LIMIT 1")){
		if ($robj=mysqli_fetch_object($rresult)){
			mysqli_free_result($rresult);
			$races_array[$robj->race]= array($robj->ap,$robj->dp,$robj->mp,$robj->tp,$robj->rp,$robj->pp);
			//print 'aaaa';
		}
	}
}
//INJECT RACES

//MULTI BLOCK
/*
if($ipresult = mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `ip`='$row->ip' and onoff) ORDER BY `id` DESC LIMIT 100")){
if($iponline = mysqli_num_rows($ipresult)){
mysqli_free_result ($ipresult);

if($iponline > $max_player_per_ip){
print 'Too many players logged in on this ip please logout some of your chars, max '.$max_player_per_ip.' chars per ip allowed on this server!';
//mysqli_query($link, "UPDATE `$tbl_members` SET `onoff`=0 WHERE `ip`='$row->ip' LIMIT 10");
include_once($game_footer);exit;
}
}
}
*/
//MULTI BLOCK


/*
if($fp<=0 and $row->level>=10){?><table cellpadding="1" cellspacing="1" border="0" width="100%"><tr><td align="center">
<iframe width=468 height=60 src="/show2.php" scrolling=no align=center frameborder=0 vspace=0 hspace=0 noresize></iframe>
<br><font size="1"><a href="support.php?sid='.$sid.'">Don't show up the banners and help the development of '.$title.'.</a></font></td></tr></table><?php 
}
*/
?>