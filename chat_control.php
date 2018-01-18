<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);
if(!empty($_GET['sid']) and empty($sid)){$sid=clean_post($_GET['sid']);}
if(!empty($_POST['sid']) and empty($sid)){$sid=clean_post($_POST['sid']);}
if(empty($sid)){header("Location: $root_url");exit;}


$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
if($result=mysqli_query($link, "SELECT `sex`,`level` FROM `$tbl_members` WHERE `sid`='$sid' LIMIT 1")){
if($row=mysqli_fetch_object($result)){
mysqli_free_result($result);
}else{mysqli_close($link);header("Location: $root_url");exit;}}else{mysqli_close($link);header("Location: $root_url");exit;}

include_once($clean_header);

if($row->level>=100){
print '<table width="100%"><form method="post" name="chat" target="'.$server.'_chit" action="chat.php?sid='.$sid.'"><tr><td width="80%"><input type="text" name="message" size="35" maxlength="200" onFocus="document.chat.message.value=\'\';document.chat.message.select()" style="width:100%;"></td><td width="10%"><input type="submit" name="action" value="Post" style="width:100%;"></td><td width="10%"><input type="reset" name="action" value="Clear" onmouseover="document.chat.message.value=\'\';document.chat.message.select()" style="width:100%;"></td>'.(($row->sex == 'Admin' or $row->sex == 'Cop')?'</form><form method=post action="admin.php?sid='.$sid.'" target="'.$server.'_main"><td width="10%"><input type=submit name=action value="'.$row->sex.'"></td>':'').'</tr></form></table>';
}else{
print '<b>When you reach level 100 or above you have to re login for chat ability.</b>';
}
include_once($clean_footer);
?>