<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

//exit;
//$update_it.=",`sex`='Support'";

$playing_days = 1;
if($hresult=mysqli_query($link, "SELECT * FROM `$tbl_history` WHERE `charname`='$row->charname' LIMIT 1")){
if($hobj=mysqli_fetch_object($hresult)){
mysqli_free_result($hresult);
$playing_days = round(($current_time-$hobj->timer)/(60*60*24));
}
}


if(!empty($_GET['cancel'])){$cancel=clean_post($_GET['cancel']);}else{$cancel='';}

if ($row->level >= 100 and $playing_days >= 30) {
if(!in_array($row->sex,$punished_sex)){
	//CAST VOTE
if (!empty($_GET['vote'])) {
$vote=clean_post($_GET['vote']);
if($presult = mysqli_query($link, "SELECT * FROM `$tbl_councils` WHERE (`id`='$vote' and `server_id`='$row->server_id' and `ip`!='$row->ip') ORDER BY `id` desc LIMIT 1")){
if (!empty($presult)) {
$vrow = mysqli_fetch_object ($presult);
mysqli_free_result ($presult);
	$heip=preg_replace("/(\d+)\.(\d+)\.(\d+)\.(\d+)/i","\${1}\${2}\${3}",$vrow->ip);
	$meip=preg_replace("/(\d+)\.(\d+)\.(\d+)\.(\d+)/i","\${1}\${2}\${3}",$row->ip);

	if (!empty($heip) and !empty($meip) and $heip !== $meip and $vrow->ip !== $row->ip and $vrow->charname !== $row->charname) {
$update_it.=", `vote`='$vrow->charname'";
print 'You have casted a vote on '.$vrow->sex.' '.$vrow->charname.' for '.$vrow->apply.'.';
	} else {print 'Ip match is higher then 75% or you are voting for yourself, your vote has been discarded.';}
}
}
}
	//CAST VOTE

if ($row->sex == 'Cop') {
	$kind_apply='Admin';
} elseif ($row->sex == 'Mod') {
	$kind_apply='Cop';
} elseif ($row->sex == 'Support') {
	$kind_apply='Mod';
} else {
	$kind_apply='Support';
}

	//APPLY FOR COUNCIL
if (!empty($_POST['apply']) and $row->sex !== 'Admin') {
mysqli_query($link, "INSERT INTO `$tbl_councils` values ('','$row->server_id','$row->sex','$kind_apply','$row->charname','0','0','0','0','$ip','$current_time')") or print(mysqli_error($link));
print 'You have applied for '.$kind_apply.' title.';
}
	//APPLY FOR COUNCIL

if($tresult = mysqli_query($link, "SELECT `id` FROM `$tbl_members` WHERE (`id` and `server_id`='$row->server_id') LIMIT 100000")){
if($total_players = mysqli_num_rows($tresult)){
mysqli_free_result ($tresult);
$maxop[0] = round($total_players/200);	//admins
	if ($maxop[0]<=5) {$maxop[0]=5;}
	if ($maxop[0]>=15) {$maxop[0]=15;}
$maxop[1] = $maxop[0]*1.25;		//cops
$maxop[2] = $maxop[0]*1.50;		//mods
$maxop[3] = $maxop[0]*1.75;		//supports
}
}
print '
<table width="100%" cellpadding=0 cellspacing=1 border=0>
<tr><th colspan=3>The High Council of '.$title.'<br>Population of this server is '.lint($total_players); print ' players.</th></tr>
';
$max=0;$haveop=array(-1,-1,-1,-1);
foreach ($operators as $val) {
print '<tr><th colspan=2>Max '.lint($maxop[$max]).' '.$val.'\'s</th></tr>';
if($mresult = mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE (`sex`='$val' and `charname`!='SilenT' and `server_id`='$row->server_id') ORDER BY `timer` DESC")){
$i=1;
while ($arow = mysqli_fetch_object ($mresult)) {
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>
<a href="members.php?info='.$arow->charname.'">';
if(($arow->fp-$current_time)>1){
	print '<img src="'.$emotions_url.'/star.gif" border="0" alt="Premium member">';}
print ($arow->clan ? "[$arow->clan] ":'').$arow->sex.' '.$arow->charname;
empty($bg)? $bg=1:$bg='';

if ((((($current_time-$arow->timer)/60)/60)/24) >= $opinactive[$max]) {
mysqli_query($link, "UPDATE `$tbl_members` SET `sex`='Lord' WHERE `id`=$arow->id LIMIT 1");
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES ('','$row->server_id','1','$arow->sex $arow->charname [$arow->level] has been kicked out of the council','$current_time')");
print ' <b><font size=1>inactive for more than '.$opinactive[$max].' days</font></b>';
}

print '</td><td>'.dater($arow->timer).'</td></tr>';

$i++;
}
mysqli_free_result ($mresult);
$haveop[$max]+=$i;
$max++;
}
}
print '
</table>
';		//APLIES
if($aresult = mysqli_query($link, "SELECT * FROM `$tbl_councils` WHERE (`id` and `server_id`='$row->server_id') ORDER BY `timer` desc")){
print '
<table width="100%" cellpadding=0 cellspacing=1 border=0>
<tr><th colspan=6>The High Council job applications from the last 5 days</th></tr>
<tr><td>Charname</td><td>Applying for</td><td>Admin</td><td>Cop</td><td>Mod</td><td>Support</td></tr>';
while ($aprow = mysqli_fetch_object ($aresult)) {
if($aprow->timer <= $current_time-423000){
mysqli_query($link, "DELETE FROM `$tbl_councils` WHERE (`id`<='$aprow->id' and `server_id`='$row->server_id') LIMIT 1");
}else{
if ($aprow->apply == 'Support') {
$needadmins=1;
$needcops=0;
$needmods=round(($haveop[2]/100)*25);
$needsupport=round(($haveop[3]/100)*75);
}
$iresult = mysqli_query($link, "SELECT `id` FROM `$tbl_members` WHERE and `server_id`='$row->server_id' and `sex`='Support' and `vote`='$aprow->charname'");
if ($iresult) {$aprow->support = mysqli_num_rows($iresult);mysqli_free_result ($iresult);}

if ($aprow->apply == 'Mod') {
$needadmins=1;
$needcops=round(($haveop[1]/100)*25);
$needmods=round(($haveop[2]/100)*75);
$needsupport=0;
}
$iresult = mysqli_query($link, "SELECT `id` FROM `$tbl_members` WHERE and `server_id`='$row->server_id' and `sex`='Mod' and `vote`='$aprow->charname'");
if ($iresult) {$aprow->mod = mysqli_num_rows($iresult);mysqli_free_result ($iresult);}

if ($aprow->apply == 'Cop') {
$needadmins=round(($haveop[0]/100)*25);
$needcops=round(($haveop[1]/100)*75);
$needmods=0;
$needsupport=0;
}
$iresult = mysqli_query($link, "SELECT `id` FROM `$tbl_members` WHERE and `server_id`='$row->server_id' and `sex`='Cop' and `vote`='$aprow->charname'");
if ($iresult) {$aprow->cop = mysqli_num_rows($iresult);mysqli_free_result ($iresult);}

if ($aprow->apply == 'Admin') {
$needadmins=round(($haveop[0]/100)*75);
$needcops=round(($haveop[1]/100)*25);
$needmods=0;
$needsupport=0;
}
$iresult = mysqli_query($link, "SELECT `id` FROM `$tbl_members` WHERE and `server_id`='$row->server_id' and `sex`='Admin' and `vote`='$aprow->charname'");
if ($iresult) {$aprow->admin = mysqli_num_rows($iresult);mysqli_free_result ($iresult);}

	//GRANTED TO THE HIGH COUNCIL
if ($aprow->admin >= $needadmins and $aprow->cop >= $needcops and $aprow->mod >= $needmods and $aprow->support >= $needsupport) {
mysqli_query($link, "UPDATE `$tbl_members` SET `sex`='$aprow->apply' WHERE (`charname`='$aprow->charname') LIMIT 1");
mysqli_query($link, "UPDATE `$tbl_members` SET `vote`='' WHERE (`vote`='$aprow->charname') LIMIT 1");
mysqli_query($link, "DELETE FROM `$tbl_councils` WHERE `charname`='$aprow->charname'");

mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES ('','$row->server_id','1','$aprow->sex $aprow->charname is know a high council $aprow->apply $aprow->charname','$current_time')") or print("Unable to insert message.");
}
	//GRANTED TO THE HIGH COUNCIL

print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>';

if (in_array($row->sex,$operators) and $row->charname !== $aprow->charname) {
print '<a href="?sid='.$sid.'&amp;vote='.$aprow->id.'">Vote for</a>';
} elseif ($aprow->charname == $row->charname) {$applied=1;
if ($cancel == $aprow->id) {
mysqli_query($link, "DELETE FROM `$tbl_councils` WHERE `id`=$aprow->id LIMIT 1");
print 'Application has been canceled for ';
}else{
print '<a href="?sid='.$sid.'&amp;cancel='.$aprow->id.'">Cancel my application</a>';
}
}
print " $aprow->sex $aprow->charname</td><td>$aprow->apply</td><td>$aprow->admin of $needadmins</td><td>$aprow->cop of $needcops</td><td>$aprow->mod of $needmods</td><td>$aprow->support of $needsupport</td></tr>";
}
}
mysqli_free_result ($aresult);

print '
</table>
';
}
		//APLIES

if ($row->sex !== 'Admin' and empty($applied)) {
if ($row->level >= 100){
print '
<form method="post" action="?sid='.$sid.'">
<input type=submit name=apply value="I wish to apply for a '.$kind_apply.' job in the High Council!">
</form>
';
}else{
print 'You need be at level 100 and a citizen for at least 30 days to get involved in politics.';
}
}
} else {
print 'Your are known as <b>'.$row->sex.'</b>, not allowing you to get involved into politics';
}
} else {
print 'You need be at level 100 and a citizen for at least 30 days to get involved in politics.';
}
require_once($game_footer);
?>