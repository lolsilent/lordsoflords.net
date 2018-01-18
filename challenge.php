<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);
mysqli_query($link, "DELETE FROM `$tbl_duel` WHERE `timer`<($current_time-(60*60*24)) LIMIT 100");

if($row->mute<=$current_time){

$challenged=array();
$not_challenged='';

if(!empty($_POST['opponent'])){$opponent=clean_post($_POST['opponent']);}else{$opponent='';}
if(!empty($_POST['kind'])){$kind=clean_post($_POST['kind']);if(in_array($kind,$kinds)){$kind=	array_search($kind, $kinds);}else{$kind='';}}else{$kind='';}

if($dresult=mysqli_query($link, "SELECT * FROM `$tbl_duel` WHERE `challenger`='$row->charname' ORDER BY `id` DESC LIMIT 100")){
while($dobj=mysqli_fetch_object($dresult)){
array_push($challenged,$dobj->opponent);
}
mysqli_free_result($dresult);}

if($oresult=mysqli_query($link, "SELECT `sex`,`charname`,`level`,`xp` FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `timer`>$current_time-1000 and `charname`!='$row->charname' and `ip`!='$row->ip' and `level`>=($row->level/10)) ORDER BY `level` ASC LIMIT 100")){
while($pobj=mysqli_fetch_object($oresult)){
if(!in_array($pobj->charname,$challenged)){
if(!empty($_POST['action']) and $opponent == $pobj->charname){
mysqli_query($link, "INSERT INTO `$tbl_duel` VALUES(NULL,'$row->charname','$pobj->charname','$kind',$current_time)");
}else{
$not_challenged .="<option value=\"$pobj->charname\">".lint($pobj->level)." - $pobj->sex $pobj->charname - ".lint($pobj->xp)."</option>";
}
}
}
mysqli_free_result($oresult);
}
print '<form method="post" action="challenge.php?sid='.$sid.'" >
<table width="100%">
<tr><th colspan="2">Schedule a challenge here</th></tr>';

if(!empty($not_challenged)){
print '<tr><td width="50%">
<select name="opponent">
'.$not_challenged.'
</select></td>
<td>
<select name="kind">';
foreach($kinds as $val){
	print '<option>'.$val.'</option>';
}
print '</select></td></tr>
<tr><th colspan="2"><input type="submit" name="action" value="Challenge"></th></tr>';
}else{
	print '<tr><td colspan="2">There is nobody to challenge at this moment or you have already challenged them all.</td> </tr>';}
print '</table>
</form>';
}//mute

require_once($game_footer);
?>