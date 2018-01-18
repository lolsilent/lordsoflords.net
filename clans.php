<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);
include_once($html_header);

print '<table width="100%"><tr><th colspan="9">The most Victorious clans</th></tr><tr><td align="center">#</td><td>Clan name</td><td>Leader</td><td>Members</td><td>Won</td><td>Tied</td><td>Lost</td><td>Tourney</td><td>Points</td></tr>
';
$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
if($tresult=mysqli_query($link, "SELECT * FROM `$tbl_clans` WHERE `id` ORDER BY `points` DESC LIMIT 50")){
$num=1;
while($gobj=mysqli_fetch_object($tresult)){
if($mtresult=mysqli_query($link, "SELECT `id` FROM `$tbl_members` WHERE `clan`='$gobj->clan' LIMIT 1000")){
$total_members=mysqli_num_rows($mtresult);
mysqli_free_result($mtresult);
}else{$total_members=0;}
if($total_members<=0){
mysqli_query($link, "DELETE FROM `$tbl_clans` WHERE `id`=$gobj->id LIMIT 1");
}else{
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td align="center">'.$num.'</td><td><a href="?clan='.$gobj->clan.'">['.$gobj->clan.'] '.stripslashes($gobj->name).'</a></td><td>'.$gobj->sex.' '.$gobj->charname.'</td><td>'.lint($total_members).'</td><td>'.lint($gobj->won).'</td><td>'.lint($gobj->tied).'</td><td>'.lint($gobj->lost).'</td><td>'.$gobj->tourney==1?'Yes':'No'.'</td><td>'.$gobj->tourney==1?lint($gobj->points):rand(0,1).'</td></tr>';
empty($bg)? $bg=1:$bg='';
$num++;}
if(!empty($_GET['clan'])){if($_GET['clan'] == $gobj->clan){

if($mresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `clan`='$gobj->clan' ORDER BY `level` DESC LIMIT 250")){
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td colspan="9"><ul>';
empty($bg)? $bg=1:$bg='';
while($mobj=mysqli_fetch_object($mresult)){
print '<li><a href="members.php?info='.$mobj->charname.'">'.($mobj->twin>=1?'-':'+').$mobj->sex.' '.$mobj->charname.' '.$mobj->level.'</a></li>';
}
mysqli_free_result($mresult);
print '</ul></td></tr>';
}}}

}
mysqli_free_result($tresult);
}
mysqli_close($link);
print '</table>';
include_once($html_footer);
?>