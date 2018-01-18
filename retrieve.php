<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);
include_once($html_header);

$fields=0;
if(!empty($_POST['username'])){$username=clean_input($_POST['username']);$username and $fields++;}else{$username='';}
if(!empty($_POST['email'])){$email=clean_post($_POST['email']);$fields++;}else{$email='';}

if($fields==2){
	if (preg_match("/.+@.+\..+/", "$email")) {
$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
if($result=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE (`username`='$username' and `email`='$email') LIMIT 1")){

if ($row=mysqli_fetch_object($result)) {
mysqli_free_result($result);

if($username == $row->username and $email == $row->email){

$row->email=stripslashes($row->email);

$new_pass=substr(md5(crypt($current_time)),0,10);
$message="
Hi $row->charname,

you have requested password for:
username :
$row->username
NEW password NEW !!!:
$new_pass

After you have logged in you can change your password :
$root_url/login.php

Cheers,
$admin_name

If you didn't request this then someone else did your account info is only send to you and is secure if this happens often better change your email.
";
$new_pass=crypt($new_pass,$row->username);
mysqli_query($link, "UPDATE `$tbl_members` SET `password`='$new_pass',`fail`='0' WHERE `id`='$row->id' LIMIT 1") or die('Oops1');
$row->email=strtolower($row->email);
mail("$row->email","$title $server new password","$message",
 "From: password@thesilent.com") or die('Oops2');

print "An emails has been send to $row->email containing a new password.";
}else{print 'Sorry no such combination of Username and/or Email!';}
}else{print 'Sorry no such combination of Username and/or Email!';}
}else{print 'Sorry no such combination of Username and/or Email!.';}
mysqli_close($link);
	}else{print 'Your input of the email address doesn\'t look like a real email address, action has been canceled.<br>';}
}
print '
<form method="post" action="retrieve.php">
<table cellpadding="1" cellspacing="1" border="0" width="300">
<tr><th colspan="2">
Request a new password by email
</th></tr>
<tr><td width="50%">
Username
</td><td>
<input type="text" name="username" value="" maxlength="10">
</td></tr>
<tr><td width="50%">
Email
</td><td>
<input type="text" name="email" value="" maxlength="50">
</td></tr>
<tr><th colspan="2">
<input type="submit" name="action" value="Send an email">
</th></tr>
</table>
</form>
A new password will be created and your account will be unlocked if it was locked.
';
include_once($html_footer);
?>