<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);
print '<br><b>Please beware with this option! This is not the normal Evolve coded teleport mode, if you teleport to another world your char teleports to the other world and <font color=red>resetting your char</font> because of the differences between worlds. Unless you are the world admin in both servers.<br>If you have any <font color=red>HCM title on your char it will be stripped</font> aswell.</b><br>';

if($gwr=mysqli_query($link, "SELECT * FROM `$tbl_aservers` WHERE `id` ORDER BY `id` DESC LIMIT 1000")){
$array_tworlds = array();
print '<br><br><form method=post target="_top"><select name="tele_world">';
while($gwrow=mysqli_fetch_object($gwr)){
print '<option value="'.$gwrow->id.'">'.$gwrow->world_name.'</option>';
$array_tworlds [] = $gwrow->id;
}
mysqli_free_result($gwr);
print '</select><input type=submit name=action value="Teleport Me to this World!"></form><br>';


if(!empty($_POST['tele_world'])) {
	$tele_world = clean_post($_POST['tele_world']);
	if(in_array($tele_world, $array_tworlds)) {
if ($wrow->admin_name == $row->charname and $gwrow->admin_name == $row->charname) {

	if ($row->id == 1){
mysqli_query($link, "UPDATE LOW_PRIORITY`$tbl_members` SET `server_id`='$tele_world' WHERE `id`='$row->id'");
	}else{
mysqli_query($link, "UPDATE LOW_PRIORITY`$tbl_members` SET `server_id`='$tele_world' WHERE `id`='$row->id'");
	}

}else{

	if ($row->id == 1){
mysqli_query($link, "UPDATE LOW_PRIORITY`$tbl_members` SET `server_id`='$tele_world' WHERE `id`='$row->id'");
	}else{

if (in_array($row->sex,$operators)){
mysqli_query($link, "UPDATE LOW_PRIORITY `$tbl_members` SET `server_id`='$tele_world', `sex`='Lord', `level`=100, `xp`=50000, `gold`=25000, `stash`=100000, `life`=10000, `str`=200, `dex`=100, `agi`=50, `intel`=200, `conc`=100, `cont`=50, `weapon`=1, `spell`=1, `heal`=1, `helm`=1, `shield`=1, `amulet`=1, `ring`=1, `armor`=1, `belt`=1, `pants`=1, `hand`=1, `feet`=1, `rounds`=`rounds`+1 WHERE `id`='$row->id'");
}else{
mysqli_query($link, "UPDATE LOW_PRIORITY `$tbl_members` SET `server_id`='$tele_world', `level`=100, `xp`=50000, `gold`=25000, `stash`=100000, `life`=10000, `str`=200, `dex`=100, `agi`=50, `intel`=200, `conc`=100, `cont`=50, `weapon`=1, `spell`=1, `heal`=1, `helm`=1, `shield`=1, `amulet`=1, `ring`=1, `armor`=1, `belt`=1, `pants`=1, `hand`=1, `feet`=1, `rounds`=`rounds`+1 WHERE `id`='$row->id'");
}

	}
}
print '<br><b><a href="login.php">Please relogin to enter the new world.</a></b><br>';

mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname has left this world',$current_time)") or die(mysqli_error($link));

mysqli_query($link, "DELETE FROM `$tbl_save` WHERE `charname`='$row->charname' LIMIT 50");
mysqli_query($link, "DELETE FROM `$tbl_duel` WHERE `challenger`='$row->charname' or `opponent`='$row->charname' LIMIT 1000");

	}
}

}


require_once($game_footer);
?>