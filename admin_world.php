<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

if ($row->charname == $wrow->admin_name or $row->id == 1){

if(!empty($_POST)){

//foreach ($_POST as $key => $val) {echo 'if(!empty($_POST[\''.$key.'\'])){$'.$key.' = clean_post($_POST[\''.$key.'\']);if($wrow->'.$key.' !== $'.$key.'){$world_update .= "`'.$key.'`=\'$'.$key.'\', ";}}<br>';}

if(!empty($_POST['killing_spree_max'])){$killing_spree_max = clean_post($_POST['killing_spree_max']);if($killing_spree_max < 5 or $killing_spree_max > 10000) {$killing_spree_max = 5; }if($wrow->killing_spree_max !== $killing_spree_max){$world_update .= "`killing_spree_max`='$killing_spree_max', ";}}

if(!empty($_POST['max_player_per_ip'])){$max_player_per_ip = clean_post($_POST['max_player_per_ip']);if($max_player_per_ip < 1 or $max_player_per_ip > 3) {$max_player_per_ip = 1; }if($wrow->max_player_per_ip !== $max_player_per_ip){$world_update .= "`max_player_per_ip`='$max_player_per_ip', ";}}

if(!empty($_POST['fp_bonus_max'])){$fp_bonus_max = clean_post($_POST['fp_bonus_max']);if($fp_bonus_max < 10 or $fp_bonus_max > 10000) {$fp_bonus_max = 250; }if($wrow->fp_bonus_max !== $fp_bonus_max){$world_update .= "`fp_bonus_max`='$fp_bonus_max', ";}}

if(!empty($_POST['max_muted'])){$max_muted = clean_post($_POST['max_muted']);if($max_muted < 1 or $max_muted > 10000000) {$max_muted = 500; }if($wrow->max_muted !== $max_muted){$world_update .= "`max_muted`='$max_muted', ";}}

if(!empty($_POST['max_jailed'])){$max_jailed = clean_post($_POST['max_jailed']);if($max_jailed < 1 or $max_jailed > 10000000) {$max_jailed = 5; }if($wrow->max_jailed !== $max_jailed){$world_update .= "`max_jailed`='$max_jailed', ";}}

if(isset($_POST['reset_days'])){$reset_days = clean_post($_POST['reset_days']);if($reset_days > 1000) {$reset_days = 1000; }if($wrow->reset_days !== $reset_days){$world_update .= "`reset_days`='$reset_days', ";}}

if(isset($_POST['game_mode'])){$game_mode = clean_post($_POST['game_mode']);if($wrow->game_mode !== $game_mode){$world_update .= "`game_mode`='$game_mode', ";}}

if(!empty($_POST['chat_timer'])){$chat_timer = clean_post($_POST['chat_timer']);if($chat_timer < 5) {$chat_timer = 0; }if($wrow->chat_timer !== $chat_timer){$world_update .= "`chat_timer`='$chat_timer', ";}}

if(!empty($_POST['world_title'])){$world_title = clean_post($_POST['world_title']);if($wrow->world_title !== $world_title){$world_update .= "`world_title`='$world_title', ";}}

if(!empty($_POST['world_description'])){$world_description = clean_post($_POST['world_description']);if($wrow->world_description !== $world_description){$world_update .= "`world_description`='$world_description', ";}}

if(isset($_POST['menu_type'])){$menu_type = clean_post($_POST['menu_type']);if($menu_type > 5) {$menu_type = 0; }if($wrow->menu_type !== $menu_type){$world_update .= "`menu_type`='$menu_type', ";}}

if (!empty($world_update)){
	$world_update .= "`timer`='$current_time+300'";
	mysqli_query($link, "UPDATE `$tbl_aservers` SET $world_update WHERE (`id`='$wrow->id') LIMIT 1") or die (mysqli_error($link).'<hr>'.$world_update);
	print 'Updates implanted!';
} else {print 'There is nothing change dude!';}

}

print '<form method=post>
<table width="100%"><tr><th colspan=2>World control panel</th></tr>
<tr><td>World Name<br><font size=-2>Can not be changed</font></td><td>'.$wrow->world_name.'</td></tr>
<tr bgcolor="'.$colth.'"><td>World Admin<br><font size=-2>Can not be changed</font></td><td>'.$wrow->admin_name.'</td></tr>
<tr><td>World Birthday<br><font size=-2>Can not be changed</font></td><td>'.$wrow->world_date.'</td></tr>
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
<tr bgcolor="'.$colth.'"><td>World Title<br><font size=-2>World name description.</font></td><td><input type=text name=world_title value="'.$wrow->world_title.'" maxlength=50></td></tr>
<tr><td>World Description<br><font size=-2>World description long.</font></td><td><input type=text name=world_description value="'.$wrow->world_description.'" maxlength=120></td></tr>
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

<tr><th colspan=2><input type=submit name=action value="Update information!"></th></tr></table></form>';
}else{
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname Jailed for attempting unautorized accessing the world control panel in $wrow->world_name!','$current_time')");
$update_it .= ", `jail`=$current_time+300";
}
require_once($game_footer);
?>