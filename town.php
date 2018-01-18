<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

//Output table player online
if($iresult=mysqli_query($link, "SELECT `clan`,`sex`,`charname`,`race`,`level`,`xp`,`gold`,`timer`,`fp`,`mute`,`jail` FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `timer`>=($current_time-900) and `sex`!='Admin' and `sex`!='Cop' and `sex`!='Mod') ORDER BY `level` DESC LIMIT 100")){

print '<table width="100%">
<tr><th colspan="7">Now onlinE</th></tr>
<tr><th>#</th><th>Charname</th><th>Race</th><th>Level</th><th>Xp</th><th>Gold</th><th>Active</th></tr>
';
$i=0;
while($iobj=mysqli_fetch_object($iresult)){$i++;
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.($i).'</td><td nowrap><a href="members.php?sid='.$sid.'&amp;info='.$iobj->charname.'">';
empty($bg)? $bg=1:$bg='';
if(($iobj->fp-$current_time)>1){
print '<img src="'.$emotions_url.'/star.gif" border="0" alt="Premium member">';
}
print ($iobj->clan ? "[$iobj->clan] ":'').$iobj->sex.' '.$iobj->charname.'</a> '.($iobj->mute-$current_time>=1?' <sup><b>Defmuted('.dater($iobj->mute).')</b></sup>':'');print ($iobj->jail-$current_time>=1?' <sup><b>Jailed('.dater($iobj->jail).')</b></sup>':'').'</td><td>'.ucfirst($iobj->race).'</td><td>'.lint($iobj->level).'</td><td>'.lint($iobj->xp).'</td><td>'.lint($iobj->gold).'</td><td>'.dater($iobj->timer).'</td></tr>';
empty($bg)? $bg=1:$bg='';
}
mysqli_free_result($iresult);
print '<tr><th colspan="7">Their are now '.($i).' players in town!</th></tr>
</table>';
}

require_once($game_footer);
?>