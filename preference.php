<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);
array_push($search, "'java'", "'javascript'", "'document'", "'forms'", "'value'", "'style'", "'='", "';'");
array_push($replace, "", "", "", "", "", "", "");
$output='';

if(!empty($_GET['reset'])){
foreach ($_COOKIE as $key=>$val){if($key !== 'sid'){setcookie($key, '', time()-5000000);}}
$output.='Reset all site and color settings.';}

if(!empty($_POST['passworda'])){$passworda=clean_post($_POST['passworda']);if(strlen($passworda)<4){$passworda='';}}else{$passworda='';}
if(!empty($_POST['passwordb'])){$passwordb=clean_post($_POST['passwordb']);if(strlen($passwordb)<4){$passwordb='';}}else{$passwordb='';}
if(!empty($_POST['email'])){$email=clean_post($_POST['email']);if(!preg_match("/.+@.+\..+/",$email)){$email='';}}else{$email='';}
if(!empty($_POST['emailold'])){$emailold=clean_post($_POST['emailold']);if(!preg_match("/.+@.+\..+/",$emailold)){$emailold='';}}else{$emailold='';}

if(!empty($_POST['faction'])){
if(!empty($_POST['pcc'])){$pcc=clean_post($_POST['pcc']);
if($pcc !== $pc){$pc=$pcc;setcookie("pc", $pcc, time()+5000000);
$output.='Changed personal color:'.$pcc.'<br>';}}
if(!empty($_POST['bg'])){$bg=clean_post($_POST['bg']);if($bg !== $colbg){setcookie("bg", $bg, time()+5000000);
$output.='Changed background color :'.$bg.'<br>';}}
if(!empty($_POST['text'])){$text=clean_post($_POST['text']);if($text !== $coltext){setcookie("text", $text, time()+5000000);
$output.='Changed text color :'.$text.'<br>';}}
if(!empty($_POST['alink'])){$alink=clean_post($_POST['alink']);if($alink !== $collink){setcookie("alink", $alink, time()+5000000);
$output.='Changed link color :'.$alink.'<br>';}}
if(!empty($_POST['th'])){$th=clean_post($_POST['th']);if($th !== $colth){setcookie("th", $th, time()+5000000);
$output.='Changed th color :'.$th.'<br>';}}
if(!empty($_POST['form'])){$form=clean_post($_POST['form']);if($form !== $colform){setcookie("form", $form, time()+5000000);
$output.='Changed form color :'.$form.'<br>';}}
if(!empty($_POST['family'])){$family=clean_post($_POST['family']);if($family !== $fontfamily){setcookie("family", $family, time()+5000000);
$output.='Changed font :'.$family.'<br>';}}
if(!empty($_POST['fsize'])){$fsize=clean_post($_POST['fsize']);if($fsize !== $fontsize){setcookie("fsize", $fsize, time()+5000000);
$output.='Changed font size :'.$fsize.'<br>';}}
}

if(!empty($passworda) and !empty($passwordb) and $passworda == $passwordb){
$spassword=crypt($passworda,$row->username);
if($row->password !== $spassword){
$update_it.=", password='$spassword'";
print 'Password changed!<br>';
}else{print 'New password is the same!?<br>';}}

if(!empty($email)){
$row->email=stripslashes($row->email);

	if ($email !== $row->email and $emailold == $row->email) {
		$update_it.=", email='$email'";
		print 'Email changed to <b>'.$email.'</b>.<br>';
	}else{
		print 'The new or old email address is not accepted.<br>';
	}

}


if(!empty($output)){
print '<table><tr><td>'.$output.'</td></tr></table>';
print '<br><a href="?sid='.$sid.'">Go back!</a>';
}else{
print '
<form method="post" action="preference.php">
<input type="hidden" name="sid" value="'.$sid.'">
<table width="100%">
<tr><th colspan="2">Account Modification</th></tr>
<tr><td width="50%">Change your password<br><font size="1">Maxlength is 32 chars, minimum of 4 chars</font></td><td><input type="password" name="passworda" maxlength="32" value=""></td></tr>
<tr><td width="50%">Verify your password</td><td><input type="password" name="passwordb" maxlength="32" value=""></td></tr>
';
if(!empty($row->email)){
print '<tr><td width="50%">Your old email address</td><td><input type="text" name="emailold" maxlength="64" value=""></td></tr>
';
}
print '
<tr><td width="50%">Add or change email address<br><font size="1">Maxlength is 64 chars<br>Used for password retrieval and account unlocking when 10 failed login attempt occurs, you may leave this field empty</font></td><td><input type="text" name="email" maxlength="64" value=""></td></tr>
<tr><th colspan="2"><input type="submit" name="action" value="Make Account Modifications!"></th></tr>

<tr><th colspan=2>Site and Color Settings <a href="?reset=1&sid='.$sid.'">reset all</a></th></tr>
<tr><td>Personal Color<font color="'.($pc?$pc:'').'"> EXAMPLE</font> color</td><td><input type=text name=pcc value="'.(isset($_COOKIE['pc'])?$_COOKIE['pc']:'').'" maxlength=10></td></tr>
<tr><td>Background Color<font color="'.($colbg?$colbg:'').'"> EXAMPLE</font> color</td><td><input type=text name=bg value="'.(isset($_COOKIE['bg'])?$_COOKIE['bg']:'').'" maxlength=10></td></tr>
<tr><td>Text Color<font color="'.($coltext?$coltext:'').'"> EXAMPLE</font> color</td><td><input type=text name=text value="'.(isset($_COOKIE['text'])?$_COOKIE['text']:'').'" maxlength=10></td></tr>
<tr><td>Link Color<font color="'.($collink?$collink:'').'"> EXAMPLE</font> color</td><td><input type=text name=alink value="'.(isset($_COOKIE['link'])?$_COOKIE['link']:'').'" maxlength=10></td></tr>
<tr><td>Table Color<font color="'.($colth?$colth:'').'"> EXAMPLE</font> color</td><td><input type=text name=th value="'.(isset($_COOKIE['th'])?$_COOKIE['th']:'').'" maxlength=10></td></tr>
<tr><td>Form Color<font color="'.($colform?$colform:'').'"> EXAMPLE</font> color</td><td><input type=text name=form value="'.(isset($_COOKIE['form'])?$_COOKIE['form']:'').'" maxlength=10></td></tr>
<tr><td>Font<font face="'.($fontfamily?$fontfamily:'').'"> EXAMPLE</font> font</td><td><input type=text name=form value="'.(isset($_COOKIE['family'])?$_COOKIE['family']:'').'" maxlength=10></td></tr>
<tr><td>Font<font style="size:'.($fontsize?$fontsize:'').';"> EXAMPLE</font> font size</td><td><input type=text name=form value="'.(isset($_COOKIE['fsize'])?$_COOKIE['fsize']:'').'" maxlength=10></td></tr>
<tr><th colspan=2><input type=submit name=faction value="Make Site And Color Changes!"></th></tr>
</form>


<form enctype="multipart/form-data" method="POST">
<input type="hidden" name="sid" value="'.$sid.'">
<input type="hidden" name="MAX_FILE_SIZE" value="100000">
<tr><th colspan=2>Add your real photo to the main site!</th></tr>
';
$uploaddir = '/home/lolnet/public_html/photos/';
$pic_name = strtolower($server).$row->id.'.jpg';
	//UPLOADING
if(!empty($_FILES['photo'])){
	echo '<tr><td colspan=2>';
$uploadfile = $uploaddir.$pic_name;
if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)) {
print 'Image uploaded.';
}else{print 'Image upload error.';}
	echo '</td></tr>';
}else{
	//UPLOADING
print '
<tr><td>Upload or change my photo<br><font size=-2>Must be smaller than 100kb and JPG</font></td><td><input name="photo" type="file"></td></tr>
<tr><th colspan=2><input type="submit" value="Go!"></th></tr>
';
}
print '
</form>
</table>
';
	//EXIST OR DELETE
$picurl='https://lordsoflords.net/photos/'.$pic_name;

if(file_exists($uploaddir.$pic_name)){
	if(!empty($_GET['del'])){
		unlink($uploaddir.$pic_name);
	}else{
		print '<img src="'. $picurl.'" border=0><br><a href="?del=1&sid='. $sid.'">Delete this photo</a>'.'<a href="one.php?sid='.$sid.'">.</a>';
	}
}else{
	print 'No current photo.';
}
	//EXIST OR DELETE

}
require_once($game_footer);
?>