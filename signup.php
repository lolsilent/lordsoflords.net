<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);
require_once($inc_races);
include_once($html_header);

$races_array_keys=array_keys($races_array);
sort($races_array_keys);
$fields=0;

$world='';
if (!empty($_GET['served'])){$world=clean_post($_GET['served']);}
if(!empty($_POST['username'])){$username=clean_input($_POST['username']);$username and $fields++;}else{$username='';}
if(!empty($_POST['password'])){$password=clean_post($_POST['password']);if(strlen($password)>=4){$fields++;}}else{$password='';}
if(!empty($_POST['email'])){$email=clean_post($_POST['email']);}else{$email='';}
if(!empty($_POST['world'])){$world=clean_post($_POST['world']);$fields++;}
if(!empty($_POST['sex'])){$sex=clean_input($_POST['sex']);if ($sex=='Lord' or $sex=='Lady' or $sex=='Alien' or $sex=='Evil' or $sex=='Angel' or $sex=='Crazy'){$fields++;}}else{$sex='';}
if(!empty($_POST['charname'])){$charname=clean_input($_POST['charname']);$charname=ucfirst(strtolower($charname));$charname and $fields++;}else{$charname='';}
if(!empty($_POST['friend'])){$friend=clean_input($_POST['friend']);$friend=ucfirst(strtolower($friend));if($friend == $friend){$friend='';}}else{$friend='';}
if(!empty($_POST['race'])){$race=clean_post($_POST['race']);if(in_array($race,$races_array_keys)) {$fields++;}}else{$race='';}
if(!empty($_POST['asap'])){$asap=clean_post($_POST['asap']);}else{$asap='';}
if(!empty($_POST['isap'])){$isap=clean_post($_POST['isap']);$fields++;}else{$isap='';}

if(!empty($_POST['action']) and $fields>=7){
$same_fields="";
if($username == $password){$same_fields.="Username matches Password<br>";}
if($username == $charname){$same_fields.="Username matches Charname<br>";}
if($charname == $password){$same_fields.="Charname matches Password<br>";}
if(!empty($same_fields)){print '<a>WARNING!<br>'.$same_fields.'<br>Account signup aborted!<br></a>';$fields=0;}

$saping=sap_me($asap,$isap);
if($saping == 'OKE'){
if($_POST['timer']>$current_time){
if (!empty($email) and !preg_match("/.+@.+\..+/", "$email")) {print '<br>Your input of the email address does not look like a real email address, value has been cleared.<br>';$email='';}
$password=crypt($password,$username);
$sid=md5($current_time.$password);
$value="NULL, '$sid', '$world', '$username', '$password', '$email', '', '$sex', '$charname', '$race', '1', '0', '250', '0', '100', '25', '15', '10', '25', '15', '10', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '0', '0', '0', '', '$current_time', '0', '$friend', '0', '0', '$ip'";

$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
mysqli_query($link, "INSERT INTO `$tbl_members` VALUES ($value)");// or some_error(mysqli_error($link).__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
if(!mysqli_error($link)){
print '<table width="80%"><tr><td>
Hi <b>'.$sex.' '.$charname.'</b>,<br>
<br>
Your account has been created successfully.<br>
ATTENTION : your account details could have been modified a little bit.<br>
<br>
Username :<b>'.$username.'</b><br>
Password :<b>**********</b><br>
Sex :<b>'.$sex.'</b><br>
Charname :<b>'.$charname.'</b><br>
Race :<b>'.$race.'</b><br>
<br>
Please take some time to read our privacy, terms, rules and forums for updated information about this virtual world.<br>
<br>
Have fun and thank you for your time.<br>
'.$ip.'<br><br>
</td></tr></table>';
}else{
	print 'Sorry the Username or Charname is already taken please try another one.<br><br>';
	$value='';}
mysqli_close($link);

}else{
	print 'Signup timed out because you took longer than 300 seconds to signup,please try again.<br><br>';
	}
}else{
	print 'Your statement of <b>'.$asap.'='.$isap.'</b> is not correct.<br><br>';
	}
}//action

if(empty($value)){
print '
<br><b>Please use only alphabetic and numeric characters.</b><br><br>

<form method="post" action="signup.php" target="_top">
<input type="hidden" name="timer" value="'.($current_time+300).'">';
if(!empty($_GET['friend'])){
print '<input type="hidden" name="friend" value="'.clean_post($_GET['friend']).'">';
}
print '<table width="100%">
<tr>
<th colspan="2">
Personal Account Information<br><font size="1">can not be seen by other players and is case sensitive.<br>no fields must be the same.</font>
</th>
</tr>
<tr>
<td width="50%">
Username
<br><font size="1">Maxlength is 10 chars, minimum of 4 chars</font>
</td>
<td>
<input type="text" name="username" maxlength="10" value="'.$username.'">
</td>
</tr>
<tr>
<td width="50%">
Password
<br><font size="1">Maxlength is 32 chars, minimum of 4 chars</font>
</td>
<td>
<input type="password" name="password" maxlength="32" value="">
</td>
</tr>

<tr>
<th colspan="2">
Game Account Information<br><font size="1">Is visible to all players and is case sensitive</font>
</th>

<tr>
<td width="50%">
World<br><font size="1">If you like chatting or need help please choose a world that have online players.</font>
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
</tr>

</tr>
<tr>
<td width="50%">
Sex
</td>
<td>
<select name=sex>
<option>Lord</option>
<option>Lady</option>
<option>Alien</option>
<option>Evil</option>
<option>Angel</option>
<option>Crazy</option>
</select>
</td>
</tr>
<tr>
<td width="50%">
Charname
<br><font size="1">Maxlength is 10 chars, minimum of 4 chars.</font>
</td>
<td>
<input type="text" name="charname" maxlength="10" value="'.$charname.'">
</td>
</tr>
<tr>
<td>
Race
<br><font size="1"><a href="guides.php?see=races" target="lol_races">More on races here..</a> Every world has it own races, ask the world admin for more info.</font>
</td><td>
<select name="race">';
foreach($races_array_keys as $val){
if($val == $race){
print '<option selected>'.$val.'</option>';
}else{
print '<option>'.$val.'</option>';
}
}
print '</select></td>
</tr>
<tr>
<th colspan="2">
Simple Automation Protection
</th>
</tr>
<tr>
<td width="50%">
';
list($sapa,$sapb,$sapc)=get_sap();
print '
<input type="hidden" name="asap" value="'."$sapa $sapb $sapc".'">
How much is <b>'."$sapa$sapb$sapc".'</b>?
<br><font size="1">Difficult? Refresh the page and try again</font>
</td>
<td>
<input type="text" name="isap" value="">
</td>
</tr>
<tr>
<th colspan="2">
<input type="submit" name="action" value="Signup now">
</th>
</tr>
</table>
</form>
';
}
include_once($html_footer);
?>
