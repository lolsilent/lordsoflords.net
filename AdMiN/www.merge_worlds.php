<?php 
$fields=0;
if(!empty($_POST['username'])){$username=clean_input($_POST['username']);$username and $fields++;}else{$username='';}
if(!empty($_POST['password'])){$password=clean_post($_POST['password']);$password and $fields++;}else{$password='';}
if(!empty($_POST['world'])){$world=clean_post($_POST['world']);$password and $fields++;}else{$world='';}

if ($fields >= 3) {
if($zssresult=mysqli_query($link, "SELECT * FROM `$tbl_aservers` WHERE `id`='$world' LIMIT 1")){
if($zssrow=mysqli_fetch_object($zssresult)){
mysqli_free_result($zssresult);

if($mmresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `charname`='$zssrow->admin_name' LIMIT 1")){
if($mmrow=mysqli_fetch_object($mmresult)){
mysqli_free_result($mmresult);
$password=crypt($password,$username);
if($mmrow->username == $username and $mmrow->password == $password and !empty($mmrow->username) and !empty($mmrow->password) and !empty($username) and !empty($password)){

mysqli_query($link, "UPDATE `$tbl_members` SET `server_id`='$row->server_id' WHERE `server_id`='$zssrow->id' LIMIT 100000") or die(mysqli_error($link));

mysqli_query($link, "DELETE FROM `$tbl_aservers` WHERE `id`='$zssrow->id' LIMIT 1") or die(mysqli_error($link));

mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','<b>This world has merged with $zssrow->world_name!</b>',$current_time)") or die(mysqli_error($link));

mysqli_query($link, "INSERT INTO `$tbl_zlogs` VALUES(NULL,'$row->charname','This world $wrow->world_name has merged with $zssrow->world_name!','worldmerge','$ip','$current_time')");

print 'Merge completed!';
}else{print 'Incorrect credentials 1.';}
}else{print 'Incorrect credentials 2.';}
}else{print 'Incorrect credentials 3.';}


}else{print 'Incorrect credentials 4.';}
}else{print 'Incorrect credentials 5.';}
}else{
print '
<font color=red><b>Once the worlds are merged the targetted world will be destroyed!</b></font>

<form method="post">
<table cellpadding="0" cellspacing="0" border="0" width="300">
<tr>
<th colspan="2">The Personal Account Information of the Targetted Super World Admin</th>
</tr>
<tr>
<td width="50%">Username</td><td width="50%"><input type="text" name="username" maxlength="10"></td>
</tr>
<tr>
<td>Password</td><td><input type="password" name="password" maxlength="32"></td>
</tr><tr>
<td width="50%">
World to be destroyed
</td>
<td>
<select name=world>
';
if(!mysqli_error($link)){

if($wresult=mysqli_query($link, "SELECT * FROM `$tbl_aservers` WHERE (`id`!='$row->server_id') ORDER BY `world_name` ASC LIMIT 100")){
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
print '
</select>
</td>
</tr><tr>
<th colspan="2"><input type="submit" value="Merge with this worlD" name="action"></th>
</tr>
</table>
</form>
';
}
?>