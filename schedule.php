<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);
mysqli_query($link, "DELETE FROM `$tbl_duel` WHERE `timer`<($current_time-(60*60*24)) LIMIT 100");

if(!empty($_GET['cancelall'])){
mysqli_query($link, "DELETE FROM `$tbl_duel` WHERE `challenger`='$row->charname' LIMIT 100");
}elseif(!empty($_GET['rejectall'])){
mysqli_query($link, "DELETE FROM `$tbl_duel` WHERE `opponent`='$row->charname' LIMIT 100");
}elseif(!empty($_GET['cancel'])){
$cancel=clean_post($_GET['cancel']);
mysqli_query($link, "DELETE FROM `$tbl_duel` WHERE `id`=$cancel and `challenger`='$row->charname' LIMIT 1");
}elseif(!empty($_GET['reject'])){
$reject=clean_post($_GET['reject']);
mysqli_query($link, "DELETE FROM `$tbl_duel` WHERE `id`=$reject and `opponent`='$row->charname' LIMIT 1");
}

print '<form method="post" action="duel.php?sid='.$row->sid.'">
<table width="100%">
<tr><th colspan="3">Duel schedule</th></tr>
';
$my=0;$his=0;

if($dresult=mysqli_query($link, "SELECT * FROM `$tbl_duel` WHERE `opponent`='$row->charname' ORDER BY `id` DESC LIMIT 100")){
while($dobj=mysqli_fetch_object($dresult)){
if($row->charname == $dobj->opponent){
if($his<1){
	print '<tr><th colspan="2">Challenged you</th><th><a href="schedule.php?sid='.$row->sid.'&amp;rejectall=1">Reject all!</a></th></tr>';
	}
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td colspan="2">'.$kinds[$dobj->kind].' versus <a href="members.php?info='.$dobj->challenger.'">'.$dobj->challenger.'</a>.</td><td align="center"><a href="duel.php?sid='.$row->sid.'&amp;accept='.$dobj->id.'">Accept</a> <a href="?sid='.$row->sid.'&amp;reject='.$dobj->id.'">Reject</a></td></tr>';
empty($bg)? $bg=1:$bg='';
$his++;
}
}
mysqli_free_result($dresult);
}
if($my>=1){
	print '<tr><td colspan="3"><br><br></td></tr>';
	}
if($dresult=mysqli_query($link, "SELECT * FROM `$tbl_duel` WHERE `challenger`='$row->charname' ORDER BY `id` DESC LIMIT 100")){
while($dobj=mysqli_fetch_object($dresult)){
if($row->charname == $dobj->challenger){
if($my<1){
	print '<tr><th colspan="2">Your challenges</th><th><a href="schedule.php?sid='.$row->sid.'&amp;cancelall=1">Cancel all!</a></th></tr>';
	}
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td colspan="2">'.$kinds[$dobj->kind].' versus <a href="members.php?info='.$dobj->opponent.'">'.$dobj->opponent.'</a>.</td><td align="center"><a href="?sid='.$row->sid.'&amp;cancel='.$dobj->id.'">Cancel</a></td></tr>';
empty($bg)? $bg=1:$bg='';
$my++;
}
}
mysqli_free_result($dresult);
}

if($my<1 and $his<1){print '<tr><td colspan="3">There are no duels scheduled at this moment.</td></tr>';}
print '</table></form>';
require_once($game_footer);
?>