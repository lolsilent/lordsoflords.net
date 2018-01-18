<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

if(!empty($_POST['action'])){$action=clean_post($_POST['action']);}else{$action='';}
if(!empty($_POST['recipient'])){$recipient=clean_post($_POST['recipient']);}else{$recipient='';}
if(!empty($_POST['gold'])){$gold=clean_int($_POST['gold']);$gold=round($gold);}else{$gold='';}
if(!empty($_POST['credits'])){$credits=clean_int($_POST['credits']);$credits=round($credits);}else{$credits='';}
if(!empty($_POST['freeplay'])){$freeplay=clean_post($_POST['freeplay']);}else{$freeplay='';}
$actions=array();

print '<form method="post" action="transfer.php?sid='.$sid.'"><table width="100%"><tr><th colspan="2">Transfer</th></tr><tr><td>Gold</td><td><input type="text" name="gold" maxlength="25" value="'.($gold>=1?lint($gold):'').'"></td></tr>';
$cobj = new stdClass;
$cobj->credits=0;if($cresult=mysqli_query($link, "SELECT * FROM `$tbl_credits` WHERE (`username`='$row->username' and `charname`='$row->charname') ORDER BY `id` DESC LIMIT 1")){
if($cobj=mysqli_fetch_object($cresult)){
mysqli_free_result($cresult);
if($cobj->credits>=1){

/*_______________-=TheSilenT.CoM=-_________________*/

if($credits>=1 and $credits<=$cobj->credits){
if($poresult=mysqli_query($link, "SELECT `id`,`username`,`sex`,`charname` FROM `$tbl_members` WHERE `charname`='$recipient' ORDER BY `id` ASC LIMIT 1")){
if($ppobj=mysqli_fetch_object($poresult)){mysqli_free_result($poresult);

mysqli_query($link, "INSERT INTO `$tbl_credits` VALUES(NULL,'$ppobj->username','$ppobj->charname','$credits') ON DUPLICATE KEY UPDATE `credits`=`credits`+$credits");

mysqli_query($link, "UPDATE `$tbl_credits` SET `credits`=`credits`-$credits WHERE `id`='$cobj->id' LIMIT 1");
$cobj->credits-=$credits;

mysqli_query($link, "INSERT INTO `$tbl_zlogs` VALUES(NULL,'$row->charname','Gave $recipient $credits credits','transfer','$ip','$current_time')");
$actions[] = $ppobj->sex.' '.$ppobj->charname;
$actions[] = lint($credits).' credit'.($credits>1?'s':'');

}}}

/*_______________-=TheSilenT.CoM=-_________________*/

print '<tr><td nowrap>Credits '.lint($cobj->credits).'</td><td><input type="text" name="credits" maxlength="5"></td></tr>
';}}}
if($fp>=1){
print '
<tr><td nowrap>Freeplay exchange</td><td><select name="freeplay"><option value="No" selected>No</option><option value="Yes">Yes</option></select></td></tr>
';}

print '


<tr><td>Recipient</td><td width="75%"><select name="recipient">
';
$i=0;
if($oresult=mysqli_query($link, "SELECT `id`,`sex`,`charname`,`level`,`xp`,`fp` FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `timer`>$current_time-1000 and `charname`!='$row->charname') ORDER BY `level` ASC LIMIT 100")){
while($pobj=mysqli_fetch_object($oresult)){$i++;
if(!empty($action)){
	if($gold>=1 and $gold <= $row->gold and $recipient == $pobj->charname){
$update_it .=", `gold`=".($row->gold-$gold);
mysqli_query($link, "UPDATE `$tbl_save` SET `gold`=`gold`-$gold WHERE `charname`='$row->charname' LIMIT 10");
mysqli_query($link, "UPDATE `$tbl_members` SET `gold`=`gold`+$gold WHERE `id`=$pobj->id LIMIT 1");
if(empty($actions)){$actions[] = $pobj->sex.' '.$pobj->charname;}
$actions[] = lint($gold).' gold';
	}
	if($freeplay=='Yes' and $fp>=1 and $recipient == $pobj->charname and $row->fp>$pobj->fp){
$update_it .=", `fp`=$pobj->fp";
mysqli_query($link, "UPDATE `$tbl_members` SET `fp`=$row->fp WHERE `id`=$pobj->id LIMIT 1");
mysqli_query($link, "INSERT INTO `$tbl_zlogs` VALUES(NULL,'$row->charname','FP exchanged with $pobj->charname','transfer','$ip','$current_time')");
if(empty($actions)){$actions[] = $pobj->sex.' '.$pobj->charname;}
$actions[] = 'Freeplay';
	}
}
print '<option value="'.$pobj->charname.'">'.lint($pobj->level)." - $pobj->sex $pobj->charname - ".lint($pobj->xp).'</option>';
}
mysqli_free_result($oresult);
}
if($i<=0){print '<option value="0">Nobody</option>';}
print '
</select></td></tr><tr><th colspan="2"><input type="submit" name="action" value="Transfer"></th>
</tr></table></form>

';
if(!empty($actions)){

switch (count($actions)){
case 2:$outp="$actions[0] $actions[1]";break;
case 3:$outp="$actions[0] $actions[1] and $actions[2]";break;
case 4:$outp="$actions[0] $actions[1], $actions[2] and $actions[3]";break;
}
print 'You give '.$outp.'.';
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$row->sex $row->charname gave $outp','$current_time')");
}
require_once($game_footer);
?>