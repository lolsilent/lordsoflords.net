<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);


$fields=0;

if(!empty($_POST['username'])){$username=clean_input($_POST['username']);$username and $fields++;}else{$username='';}
if(!empty($_POST['password'])){$password=clean_post($_POST['password']);$password and $fields++;}else{$password='';}
if(!empty($_POST['world'])){$world=clean_post($_POST['world']);}else{$world='';}

if (!empty($password)) {$ppassword=$password;}else{$ppassword='';}

if($fields == 2){
$password=crypt($password,$username);
$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or die('Sorry MySQL server is down!');

if($result=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `username`='$username' LIMIT 1")){
if($row=mysqli_fetch_object($result)){
mysqli_free_result($result);


/*_______________-=TheSilenT.CoM=-_________________*/

if($ssresult=mysqli_query($link, "SELECT * FROM `$tbl_aservers` WHERE `id`='$row->server_id' LIMIT 1")){
if(mysqli_num_rows($ssresult) >= 1){
mysqli_free_result($ssresult);
}else{$ohdear=1;}
}else{$ohdear=2;}

/*_______________-=TheSilenT.CoM=-_________________*/


if($row->username == $username and $row->password == $password){
if (!empty($world)) {

if($zssresult=mysqli_query($link, "SELECT * FROM `$tbl_aservers` WHERE `id`='$world' LIMIT 1")){
if($zssrow=mysqli_fetch_object($zssresult)){
mysqli_free_result($zssresult);

mysqli_query($link, "UPDATE `$tbl_members` SET `server_id`='$zssrow->id' WHERE `id`='$row->id' LIMIT 1");
unset($ohdear);
}else{$ohdear=3;}
}else{$ohdear=4;}

}
if (!isset($ohdear)) {
if($row->fail < 10){

$sid=md5(crypt($current_time,$row->username));
mysqli_query($link, "UPDATE `$tbl_members` SET `sid`='$sid', `timer`=$current_time, `onoff`=1 WHERE `id`='$row->id' LIMIT 1");

mysqli_query($link, "UPDATE `$tbl_aservers` SET `timer`='$current_time' WHERE `id`='$row->server_id' LIMIT 1");
$fields++;

if($lresult=mysqli_query($link, "SELECT * FROM `$tbl_zlogs` WHERE `charname`='$row->charname' ORDER BY `id` DESC LIMIT 1")){
$timeout=0;
if($lobj=mysqli_fetch_object($lresult)){
	$timeout=$lobj->timer;
	mysqli_free_result($lresult);
}else{
	$timeout=$current_time-301;
}
if($current_time-$timeout >= 300){
mysqli_query($link, "INSERT INTO `$tbl_zlogs` VALUES(NULL,'$row->charname','$row->xp XP','login','$ip','$current_time')");
}
}

}else{$lock_fail=1;}

//WORLD
if($wresult=mysqli_query($link, "SELECT * FROM `$tbl_aservers` WHERE `id`='$row->server_id' ORDER BY `id` DESC LIMIT 1")){
if($wrow=mysqli_fetch_object($wresult)){
mysqli_free_result($wresult);
if($wrow->chat_timer >= 1) {$chat_timer=$wrow->chat_timer;}else{$chat_timer='Disabled';}
}else{mysqli_close($link);header("Location: $root_url/login.php");exit;}
}else{mysqli_close($link);header("Location: $root_url/login.php");exit;}
//WORLD
}
}else{
mysqli_query($link, "UPDATE `$tbl_members` SET `fail`=`fail`+1 WHERE `id`='$row->id' LIMIT 1");
}
}
}

mysqli_close($link);
}

if($fields==3 and !empty($sid)){

print '<html><head><title>'. $title.' - World Name : '.ucfirst($wrow->world_name).' - World Admin : '.$wrow->admin_name.'</title>';
include_once("$html_style");
print '</head>

<frameset cols="100,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_nav" src="'.$root_url.'/menu.php?'. (empty($wrow->menu_type)?'':'m='.$wrow->menu_type).'sid='.$sid.'&served='.$row->server_id.'" frameborder="0" framespacing="0" scrolling="no">	
	<frameset rows="*,25'.($chat_timer >= 1? ',25,20%':'').'" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_main" src="'.$root_url.'/main.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_fcontrol" src="'.$root_url.'/fight_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">';
if($chat_timer >= 1){
print '<frame name="'.$server.'_control" src="'.$root_url.'/chat_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_chit" src="'.$root_url.'/chat.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">';
}
print '
	</frameset>

</frameset>

<noframes>
<body>
Please use a browser that supports frames.
</body>
</noframes>
</html>';
}else{

include_once($html_header);

if($fields>=1 and $fields<3 and !isset($ohdear)){
print '<b><font color=red>Login failed make sure that your username and password are case correct!</font></b><br>';
}elseif(isset($ohdear)){
print '<b><font color=red>This world has been destroyed please choose a new world!</font></b><br>';
}
if(empty($lock_fail)){
print '
<form method="post" action="login.php" target="_top">
<table cellpadding="0" cellspacing="0" border="0" width="300">
<tr>
<th colspan="2">Personal Account Information</th>
</tr>
<tr>
<td width="50%">Username</td><td width="50%"><input type="text" name="username" maxlength="10" value="'. $username.'"></td>
</tr>
<tr>
<td>Password</td><td><input type="password" name="password" maxlength="32" value="'.(isset($ohdear)?$ppassword:'').'"></td>
</tr>';
if (isset($ohdear)) {

/*_______________-=TheSilenT.CoM=-_________________*/
print '<tr>
<td width="50%">
New Home World
</td>


<td>
<select name=world>

';
$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);

if(!mysqli_error($link)){

if($wresult=mysqli_query($link, "SELECT * FROM `$tbl_aservers` WHERE `id` AND `timer`>=$current_time-2538000 ORDER BY `timer` DESC LIMIT 100")){
while($wrow=mysqli_fetch_object($wresult)){
if($world == $wrow->id) {
print '<option value="'.$wrow->id.'" selected>'.$wrow->world_name.'</option>';
}else{
print '<option value="'.$wrow->id.'">'.$wrow->world_name.'</option>';
}
}
mysqli_free_result($wresult);
}

}
mysqli_close($link);
print '
</select>
</td>
</tr>';
/*_______________-=TheSilenT.CoM=-_________________*/

}
print '<tr>
<th colspan="2"><input type="submit" value="Play Game!" name="action"></th>
</tr>
</table>
</form>
';
}else{
	print '<br><b>This account has been locked!</b>';
}

print '<a href="retrieve.php">Forgot your password, is your account locked?</a>';

include_once($html_footer);
}
?>