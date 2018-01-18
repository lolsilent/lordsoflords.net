<?php 
#!/usr/local/bin/php
require_once 'AdMiN/www.main.php';
require_once($inc_functions);
require_once($game_header);
if($row->sex == 'Admin') {$row->level = 5;} elseif($row->sex == 'Cop') {$row->level = 4;} elseif($row->sex == 'Mod') {$row->level = 3;} elseif($row->sex == 'Support') {$row->level = 2;}else{$row->level = 1;}

/*
$fld_contents='`id`,`server_id`,`tid`,`mid`,`date`,`body`,`timer`,`see`,`deleted`,`ip`';
$fld_topics='`id`,`server_id`,`fid`,`mid`,`sticky`,`name`,`body`,`replies`,`views`,`last`,`first`,`timer`,`see`,`deleted`,`ip`';
*/
$order=array('timer','replies','views','last');
if (!empty($_GET['orderby'])) {$orderby=clean_post($_GET['orderby']);if (!in_array($orderby,$order)) {$orderby='last';}} else {$orderby='last';}

print '
<table width=100%><tr><th colspan=6>Sticky and Important posts</th></tr><tr><th colspan=2><a href="?sid='.$sid.'&orderby=timer">Topics</a></th><th><a href="?sid='.$sid.'&orderby=replies">Replies</a></th><th><a href="?sid='.$sid.'&orderby=views">Views</a></th><th><a href="?sid='.$sid.'&orderby=last">Last post</a></th></tr>
';
$index_limit=35;$i=1;

if($tresult=mysqli_query($link, "SELECT * FROM `$tbl_topics` WHERE (`server_id`='$row->server_id' and `sticky` >='1' and `see`<='$row->level' and `deleted`='0') ORDER BY `$orderby` DESC LIMIT $index_limit")){
while ($trow=mysqli_fetch_object ($tresult)) {

if($fresult=mysqli_query($link, "SELECT `clan`,`sex`,`charname`,`race`,`level` FROM `$tbl_members` WHERE `id`='$trow->mid' LIMIT 1")){
if($frow=mysqli_fetch_object($fresult)){
mysqli_free_result($fresult);
}}

if (empty($bgcolor)) {$bgcolor=' bgcolor="'.$colth.'"';} else {$bgcolor="";}
print '<tr'.$bgcolor.'><td valign=top NOWRAP><b>'.$i.'!</b></td><td valign=top><a href="topic.php?sid='.$sid.'&tid='.$trow->id.'">'.substr($trow->name,0,100).'</a></td><td valign=top align=center>'.lint($trow->replies).'</td><td valign=top align=center>'.lint($trow->views).'</td><td valign=top>'.(!empty($frow->clan)?"[$frow->clan] ":'').$frow->sex.' '.$frow->charname.' <font size=1>'.dater($trow->last).' ago</font></td></tr>';$i++;
$index_limit--;
}
mysqli_free_result ($tresult);
}

print '<tr><th colspan=6>Newest and Latest posts</th></tr><tr><th colspan=2><a href="?sid='.$sid.'&orderby=timer">Topics</a></th><th><a href="?sid='.$sid.'&orderby=replies">Replies</a></th><th><a href="?sid='.$sid.'&orderby=views">Views</a></th><th><a href="?sid='.$sid.'&orderby=last">Last post</a></th></tr>';

if($tresult=mysqli_query($link, "SELECT * FROM `$tbl_topics` WHERE (`server_id`='$row->server_id' and `see`<='$row->level' and `sticky`<='0' and `deleted`='0') ORDER BY `$orderby` desc LIMIT $index_limit")){
while ($trow=mysqli_fetch_object ($tresult)) {

if($fresult=mysqli_query($link, "SELECT `clan`,`sex`,`charname`,`race`,`level` FROM `$tbl_members` WHERE `id`='$trow->mid' LIMIT 1")){
if($frow=mysqli_fetch_object($fresult)){
mysqli_free_result($fresult);
}}

if (empty($bgcolor)) {$bgcolor=' bgcolor="'.$colth.'"';} else {$bgcolor="";}
print '<tr'.$bgcolor.'><td valign=top NOWRAP>'.$i.'</td><td valign=top><a href="topic.php?sid='.$sid.'&tid='.(isset($trow->id)?$trow->id:'').'">'.(isset($trow->name)?substr($trow->name,0,50):'').'</a></td><td valign=top align=center>'.(isset($trow->replies)?lint($trow->replies):'').'</td><td valign=top align=center>'.(isset($trow->views)?lint($trow->views):'').'</td><td valign=top>'.(!empty($frow->clan)?"[$frow->clan] ":'').(isset($frow->sex)?$frow->sex:'').' '.(isset($frow->charname)?$frow->charname:'').'</a> <font size=1>'.(isset($trow->last)?dater($trow->last):'').' ago</font></td></tr>';$i++;
$index_limit--;
}
mysqli_free_result ($tresult);
}

print '</table>';
if (!empty($row->email)){print '
<form method=post action="post.php?sid='.$sid.'" name=post><table width=100%><tr><th colspan=2>Create a new topic here!</th></tr>
<tr><td>Topic name</td><td><input type=text name=topic maxlength=100 size=72></td></tr>
<tr><td valign=top>Message<br>';
if($row->level >= 2){
print '<br>Visible to:<br><select name=see>';
if($row->level >= 5){print '<option value=5>Admins or Higher!</option>';}
if($row->level >= 4){print '<option value=4>Cops or Higher!</option>';}
if($row->level >= 3){print '<option value=3>Mods or Higher!</option>';}
if($row->level >= 2){print '<option value=2>Supports or Higher!</option>';}
if($row->level >= 1){print '<option value=0 selected>Everybody!</option>';}
print '</select>';

print '<br>Post allowed:<br><select name=fid>';
if($row->level >= 5){print '<option value=5>Admins or Higher!</option>';}
if($row->level >= 4){print '<option value=4>Cops or Higher!</option>';}
if($row->level >= 3){print '<option value=3>Mods or Higher!</option>';}
if($row->level >= 2){print '<option value=2>Supports or Higher!</option>';}
if($row->level >= 1){print '<option value=0 selected>Everybody!</option>';}
print '</select>';
}
print '</td><td><textarea name=message cols=55 rows=10></textarea></td></tr>
<tr><th colspan=2><input type=submit name=action value="Create topic"></th></tr></table></form>';
}else{
print '<p>You need to set an email address to post messages in the forums.</p>';}
require_once($game_footer);
?>