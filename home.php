<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);

require_once($inc_mysql);
$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);

include_once($html_header);

$served=1;
if (!empty($_GET['served'])){
$served=clean_post($_GET['served']);
}

if($tresult=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `server_id`='$served' ORDER BY `id` DESC")){
$total=lint(mysqli_num_rows($tresult));$nobj=mysqli_fetch_object($tresult);mysqli_free_result($tresult);
}else{$total=0;}


print '<br>Their are <b>'.$total.'</b> Lords and Ladies that kept their character alive and kicking.<br>
<br>';
if(!empty($nobj)){print 'We welcome our newest adventurer <b>'.$nobj->sex.' '.$nobj->charname.'</b>.<br>';}

//Output table player online
if($iresult=mysqli_query($link, "SELECT `clan`,`sex`,`charname`,`race`,`level`,`xp`,`gold`,`timer`,`fp`,`mute`,`jail` FROM `$tbl_members` WHERE `server_id`='$served' and `timer`>=($current_time-900) and `sex`!='Admin' and `sex`!='Cop' and `sex`!='Mod' ORDER BY `level` DESC LIMIT 100")){

print '<table width="100%">
<tr><th colspan="7">Now onlinE</th></tr>
<tr><th>#</th><th>Charname</th><th>Race</th><th>Level</th><th>Xp</th><th>Gold</th><th>Active</th></tr>';
$i=0;
while($iobj=mysqli_fetch_object($iresult)){$i++;
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.($i).'</td><td nowrap><a href="members.php?info='.$iobj->charname.'">';
empty($bg)? $bg=1:$bg='';
if(($iobj->fp-$current_time)>1){
	print '<img src="'.$emotions_url.'/star.gif" border="0" alt="Premium member">';}print ($iobj->clan ? "[$iobj->clan] ":'').$iobj->sex.' '.$iobj->charname.'</a> '.$iobj->mute-$current_time>=1?' <sup><b>Defmuted('.dater($iobj->mute).')</b></sup>':'';print $iobj->jail-$current_time>=1?' <sup><b>Jailed('.dater($iobj->jail).')</b></sup>':''.'</td><td>'.ucfirst($iobj->race).'</td><td>'.lint($iobj->level).'</td><td>'.lint($iobj->xp).'</td><td>'.lint($iobj->gold).'</td><td>'.dater($iobj->timer).'</td></tr>';
}
mysqli_free_result($iresult);
print '<tr><th colspan="7">Their are now '.($i).' visible players online!</th></tr></table>';
}

mysqli_close($link);
print '<b>If you like this game please tell your friends, thank you. <br><a href="https://lordsoflords.net" target="_top">https://lordsoflords.net</a></b><br>And don\'t forget to bookmark :o)...
';
include_once($html_footer);
?>