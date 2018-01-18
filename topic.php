<?php 
#!/usr/local/bin/php
require_once 'AdMiN/www.main.php';
require_once($inc_functions);
require_once($inc_emotions);
require_once($game_header);

if($row->sex == 'Admin') {$row->level = 5;} elseif($row->sex == 'Cop') {$row->level = 4;} elseif($row->sex == 'Mod') {$row->level = 3;} elseif($row->sex == 'Support') {$row->level = 2;}else{$row->level = 1;}

/*
$fld_contents='`id`,`server_id`,`tid`,`mid`,`date`,`body`,`timer`,`see`,`deleted`,`ip`';
$fld_topics='`id`,`server_id`,`fid`,`mid`,`sticky`,`name`,`body`,`replies`,`views`,`last`,`first`,`timer`,`see`,`deleted`,`ip`';
*/
$max_posts = 50;
if (!empty($_GET['tid'])) {$tid=clean_post($_GET['tid']);} else {$tid='';}

if(empty($tid)) {
	//NOTOPIC
print '<p><a href="forum.php?sid='.$sid.'">Topic not found! Click here to return to the forums.</a></p>';
	//NOTOPIC
}else{

if($tresult=mysqli_query($link, "SELECT * FROM `$tbl_topics` WHERE (`server_id`='$row->server_id' and `id`='$tid' and `see`<='$row->level' and `deleted`='0') ORDER BY `id` DESC LIMIT 1")){
if($trow=mysqli_fetch_object ($tresult)){
mysqli_free_result ($tresult);

		$title=clean_post($trow->name);

print '<table border=1 width=100% bordercolor='.$colth.'>';

if($fresult=mysqli_query($link, "SELECT `id`,`clan`,`sex`,`charname`,`race`,`level` FROM `$tbl_members` WHERE `id`='$trow->mid' LIMIT 1")){
if($frow=mysqli_fetch_object($fresult)){
mysqli_free_result($fresult);
}}

print '<tr><th colspan=2><a href="forum.php?sid='.$sid.'">Forum Index</a> <font size=+2>'.$trow->name.''.($trow->sticky?'</font> <font color='.$collink.'><sup>sticky</sup':'').'</font></th></tr><tr><td valign=top width=125 NOWRAP>';
if (empty($frow)){
	print 'R.I.P.';
	}else{
print (!empty($frow->clan)?"[$frow->clan] ":'').$frow->sex.' '.$frow->charname.' <font size=-2>'.$frow->race.' '.lint($frow->level).'</font>';
}
print '<br>'.dater($trow->first).' ago';if ($row->id==$frow->id or $row->level >=5) {print '<br>[';print '<a href="edit.php?sid='.$sid.'&action=edit&kind=topic&did='.$trow->id.'">edit</a> <a href="edit.php?sid='.$sid.'&action=delete&kind=topic&did='.$trow->id.'">delete</a>]<br><font size=1>'.$trow->ip.'</font>';}
print '</td><td valign=top>'.postit($trow->body).'</td></tr>';
mysqli_query($link, "UPDATE `$tbl_topics` SET `views`=`views`+1 WHERE (`id`='$trow->id' and `see`<='$row->level' and `deleted`='0') LIMIT 1");


if ($trow->replies >= 1) {
if($nreply=mysqli_query($link, "SELECT id FROM $tbl_contents WHERE (tid=$trow->id and see<=$row->level and deleted=0) ORDER BY id ASC")) {
if($total_replies = mysqli_num_rows($nreply)){mysqli_free_result ($nreply);}}

if (!empty($_GET['last_id'])) {$last_id=clean_post($_GET['last_id']);$where_id="`id`>='$last_id'";} else {$where_id='`id`';}
if($rresult=mysqli_query($link, "SELECT * FROM $tbl_contents WHERE ($where_id and `tid`='$trow->id' and `see`<='$row->level' and `deleted`='0') ORDER BY `id` ASC LIMIT $max_posts")) {
$replies = mysqli_num_rows($rresult);
while ($rrow=mysqli_fetch_object ($rresult)) {

if($rfresult=mysqli_query($link, "SELECT `id`,`clan`,`sex`,`charname`,`race`,`level` FROM `$tbl_members` WHERE `id`='$rrow->mid' LIMIT 1")){
if($rfrow=mysqli_fetch_object($rfresult)){
mysqli_free_result($rfresult);
}}

print '<tr><td colspan=2 align=center><img src="/images/lolnetbg.jpg" height=5 border=0></td></tr><tr><td valign=top width=125 NOWRAP>';
if (empty($rfrow)){
	print 'R.I.P.';
	}else{
print (!empty($rfrow->clan)?"[$rfrow->clan] ":'').$rfrow->sex.' '.$rfrow->charname.' <font size=-2>'.$rfrow->race.' '.lint($rfrow->level).'</font>';
}
print '<br>'.dater($rrow->timer).' ago'; if ($row->id==$rfrow->id or $row->level >=5) {print '<br>[';print '<a href="edit.php?sid='.$sid.'&action=edit&kind=reply&did='.$rrow->id.'">edit</a> <a href="edit.php?sid='.$sid.'&action=delete&kind=reply&did='.$rrow->id.'">delete</a>]<br><font size=1>'.$rrow->ip.'</font>';}
print '</td><td valign=top>'.postit($rrow->body).'</td></tr>';

$lastid=$rrow->id+1;
}
mysqli_free_result ($rresult);
if($total_replies > $max_posts){
	print '<tr><th colspan=2>';
	if(!empty($last_id)){
		print '<a href="?sid='.$sid.'&tid='.$trow->id.'">First</a>';
	}
	if($replies == $max_posts){
		print ' <a href="?sid='.$sid.'&tid='.$trow->id.'&last_id='.$lastid.'">Next</a>';
if($lres=mysqli_query($link, "SELECT `id` FROM `$tbl_contents` WHERE ($where_id and `tid`='$trow->id' and `see`<='$row->level' and `deleted`='0') ORDER BY id DESC LIMIT 1")) {
	if($lcrow=mysqli_fetch_object ($lres)){mysqli_free_result ($lres);
print ' <a href="?sid='.$sid.'&tid='.$trow->id.'&last_id='.($lcrow->id-$max_posts).'">Last</a>';
	}
	}
	}
	print '</th></tr>';
}

if($total_replies <> $trow->replies){
mysqli_query($link, "UPDATE `$tbl_topics` SET `replies`='$total_replies' WHERE `id`='$trow->id' LIMIT 1");
}

}
}
print '</table>';
if ($row->level >= $trow->fid and !empty($row->email)) {
	print '<form method=post action="post.php?sid='.$sid.'&tid='.$tid.'" name=post><table width=100%><tr><th colspan=2>Post your reply!</th></tr>
<tr><td valign=top>Message</td><td><textarea name=message cols=75 rows=15></textarea></td></tr>
<tr><th colspan=2><input type=submit name=action value="Post reply"></th></tr></table></form>
';
}else{
	print '<p>You are not allowed to post here.</p>';
	}


}else{
	print '<hr><b>We are very sorry for this inconvenience, this topic does not exist or has been deleted.</b><hr>';
	}
}
}
require_once($game_footer);
?>