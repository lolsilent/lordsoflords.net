<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);
print '<b>Creating and controlling your own world will cost 5.000 credits!</b>';

//$fld_aservers='`id`,`admin_name`,`admin_email`,`world_name`,`world_title`,`world_description`,`world_date`,`game_mode`,`killing_spree_max`,`max_player_per_ip`,`fp_bonus_max`,`max_muted`,`max_jailed`,`chat_timer`,`reset_days`,`menu_type`,`updated`,`timer`';

if(!empty($_POST)){

//foreach ($_POST as $key => $val) {echo 'if(!empty($_POST[\''.$key.'\'])){$'.$key.' = clean_post($_POST[\''.$key.'\']);if($wrow->'.$key.' !== $'.$key.'){$world_update .= "`'.$key.'`=\'$'.$key.'\', ";}}<br>';}

$world_name='';$world_title='';$world_description='';$current_date='';
$game_mode='';$killing_spree_max='';$max_player_per_ip='';$fp_bonus_max='';
$max_muted='';$max_jailed='';$chat_timer='';$reset_days='';$menu_type='';

$f=0;
if(!empty($_POST['world_name'])){$world_name = clean_input($_POST['world_name']);if (!empty($world_name)){$f++;}}

if(!empty($_POST['killing_spree_max'])){$killing_spree_max = clean_post($_POST['killing_spree_max']);if($killing_spree_max < 5 or $killing_spree_max > 100) {$killing_spree_max = 5; }}

if(!empty($_POST['max_player_per_ip'])){$max_player_per_ip = clean_post($_POST['max_player_per_ip']);if($max_player_per_ip < 1 or $max_player_per_ip > 3) {$max_player_per_ip = 1; }}

if(!empty($_POST['fp_bonus_max'])){$fp_bonus_max = clean_post($_POST['fp_bonus_max']);if($fp_bonus_max < 10 or $fp_bonus_max > 10000) {$fp_bonus_max = 250; }}

if(!empty($_POST['max_muted'])){$max_muted = clean_post($_POST['max_muted']);if($max_muted < 1 or $max_muted > 10000000) {$max_muted = 500; }}

if(!empty($_POST['max_jailed'])){$max_jailed = clean_post($_POST['max_jailed']);if($max_jailed < 1 or $max_jailed > 10000000) {$max_jailed = 5; }}

if(isset($_POST['reset_days'])){$reset_days = clean_post($_POST['reset_days']);if($reset_days > 1000) {$reset_days = 1000; }}

if(isset($_POST['game_mode'])){$game_mode = clean_post($_POST['game_mode']);}

if(!empty($_POST['chat_timer'])){$chat_timer = clean_post($_POST['chat_timer']);if($chat_timer < 5) {$chat_timer = 0; }}

if(!empty($_POST['world_title'])){$world_title = clean_post($_POST['world_title']);}

if(!empty($_POST['world_description'])){$world_description = clean_post($_POST['world_description']);}

if(isset($_POST['menu_type'])){$menu_type = clean_post($_POST['menu_type']);if($menu_type > 5) {$menu_type = 0; }}

if($f >= 1){
if($cresult=mysqli_query($link, "SELECT * FROM `$tbl_credits` WHERE (`username`='$row->username' and `charname`='$row->charname') ORDER BY `id` DESC LIMIT 1")){
if($cobj=mysqli_fetch_object($cresult)){
mysqli_free_result($cresult);
if($cobj->credits >= 5000){

mysqli_query($link, "UPDATE `$tbl_credits` SET `credits`='".($cobj->credits-5000)."' WHERE `id`='$cobj->id' LIMIT 1") or die (mysqli_error($link));
mysqli_query($link, "INSERT INTO `$tbl_aservers` VALUES(NULL, '$row->charname', '$row->email', '$world_name', '$world_title', '$world_description', '$current_date', '$game_mode', '$killing_spree_max', '$max_player_per_ip', '$fp_bonus_max', '$max_muted', '$max_jailed', '$chat_timer', '$reset_days', '$menu_type','0','0', '$current_time')") or die (mysqli_error($link));

if($wwresult=mysqli_query($link, "SELECT * FROM `$tbl_aservers` WHERE `admin_name`='$row->charname' ORDER BY `id` DESC LIMIT 1")){
if($wwrow=mysqli_fetch_object($wwresult)){
mysqli_free_result($wwresult);
$update_it .= ", `server_id` = '$wwrow->id', `sex`='Admin'";
print '<br>World created! You can now invite players to signup to play in your world!<br>';
}else{print 'World creation error 1, please email Admin SilenT.';}
}else{print 'World creation error 2, please email Admin SilenT.';}

}else{print 'Insufficient credits!';}
}
}
}

}

print '<form method=post>
<table width="100%"><tr><th colspan=2>World Creation Panel<br>Please use only alphabetic and numeric characters.
</th></tr>
<tr><td>World Name<br><font size=-2>Can not be changed, Maxlength is 10 chars, minimum of 4 chars, </font></td><td><input type=text name=world_name value="" maxlength=10></td></tr>
<tr bgcolor="'.$colth.'"><td>World Admin<br><font size=-2>Can not be changed</font></td><td>'.$row->charname.'</td></tr>
<tr><td>World Birthday<br><font size=-2>Can not be changed</font></td><td>'.$current_date.'</td></tr>
<tr bgcolor="'.$colth.'"><td>Max Killing Spree<br><font size=-2>Maximum monsters you can kill with a single click on fight!</font></td><td><input type=text name=killing_spree_max value="'.$wrow->killing_spree_max.'" maxlength=5></td></tr>
<tr><td>Max Players Per IP<br><font size=-2>Max players logged on with the same IP address!</font></td><td><input type=text name=max_player_per_ip value="'.$wrow->max_player_per_ip.'" maxlength=1></td></tr>
<tr bgcolor="'.$colth.'"><td>Max FP bonus<br><font size=-2>Maximum Freeplay Bonus!</font></td><td><input type=text name=fp_bonus_max value="'.$wrow->fp_bonus_max.'" maxlength=5></td></tr>
<tr><td>Max Muted<br><font size=-2>Maximum allowed mute time.</font></td><td><input type=text name=max_muted value="'.$wrow->max_muted.'" maxlength=8></td></tr>
<tr bgcolor="'.$colth.'"><td>Max Jailed<br><font size=-2>Maximum allowed jail time.</font></td><td><input type=text name=max_jailed value="'.$wrow->max_jailed.'" maxlength=8></td></tr>
<tr><td>World Resets<br><font size=-2>World resets in days, zero for never reset.</font></td><td><input type=text name=reset_days value="'.$wrow->reset_days.'" maxlength=5></td></tr>
<tr bgcolor="'.$colth.'"><td>Game mode<br><font size=-2>Check the guides for the game modes</font></td><td><select name=game_mode>
';
$i=0;foreach($game_modes as $val){
if ($wrow->game_mode == $i) {
print '<option value="'.$i.'" selected>'.$val.'</option>';
} else {
print '<option value="'.$i.'">'.$val.'</option>';
}
$i++;
}
print '
</td></tr>
<tr><td>Chat Timer<br><font size=-2>Seconds untill the chat box reloads, 5 or less for no chat.</font></td><td><input type=text name=chat_timer value="'.$wrow->chat_timer.'" maxlength=5></td></tr>
<tr bgcolor="'.$colth.'"><td>World Title<br><font size=-2>World name description.</font></td><td><input type=text name=world_title maxlength=50></td></tr>
<tr><td>World Description<br><font size=-2>World description long.</font></td><td><input type=text name=world_description maxlength=120></td></tr>
<tr bgcolor="'.$colth.'"><td>Menu Type<br><font size=-2>Select start up navigation image, zero for text standard.</font></td><td><select name=menu_type>
';
for ($i=0;$i<=5;$i++){
if ($wrow->menu_type == $i) {
print '<option value="'.$i.'" selected>'.$i.'</option>';
} else {
print '<option value="'.$i.'">'.$i.'</option>';
}
}
print '
</select></td></tr>

<tr><th colspan=2><input type=submit name=action value="Create my world!"></th></tr></table></form>';

require_once($game_footer);
?>