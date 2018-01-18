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


if (!empty($_GET['action'])) {$action=clean_post($_GET['action']);} else {$action='';}
if (!empty($_GET['kind'])) {$kind=clean_post($_GET['kind']);} else {$kind='';}
if (!empty($_GET['did'])) {$did=clean_post($_GET['did']);} else {$did='';}

if($kind == 'topic'){
$edit_table=$tbl_topics;
}elseif($kind == 'reply'){
$edit_table=$tbl_contents;
}

if (!empty($action) and !empty($kind) and !empty($did) and !empty($edit_table)) {
if($eresult=mysqli_query($link, "SELECT * FROM `$edit_table` WHERE (`server_id`='$row->server_id' and id=$did and see<=$row->level and deleted <= 0) ORDER BY id desc LIMIT 1")){
if($edrow=mysqli_fetch_object ($eresult)){
mysqli_free_result ($eresult);

if(!empty($edrow->name)){print '<p><a href="forum.php?sid='.$sid.'">Forums index</a> | <a href="?sid='.$sid.'&action=edit&kind='.$kind.'&did='.$did.'">Edit</a> | <a href="?sid='.$sid.'&action=delete&kind='.$kind.'&did='.$did.'">Delete</a> | <a href="?sid='.$sid.'&action=sticky&kind=topic&did='.$did.'&stick=1">Make Sticky</a> | <a href="?sid='.$sid.'&action=sticky&kind=topic&did='.$did.'&unstick=1">Remove Sticky</a></p>';}else{print '<p><a href="forum.php?sid='.$sid.'">Forums index</a> | <a href="?sid='.$sid.'&action=edit&kind='.$kind.'&did='.$did.'">Edit</a> | <a href="?sid='.$sid.'&action=delete&kind='.$kind.'&did='.$did.'">Delete</a></p>';}

if ($edrow->mid == $row->id or $row->level >=5) {

if (!empty($_GET['stick']) and $row->level >=5) {
mysqli_query($link, "UPDATE $tbl_topics SET sticky=sticky+1 WHERE id=$did LIMIT 1");
print 'Made the topic to be sticky.';
} elseif (!empty($_GET['unstick']) and $row->level >=5) {
mysqli_query($link, "UPDATE $tbl_topics SET sticky=0 WHERE id=$did LIMIT 1");
print 'Removed sticky from topic.';
}

if($action == 'edit'){
$update_string='';

if (!empty($_POST['body'])) {
$body=preg_replace('@<p align=right><font size=-2>(.*?)</font></p>@si','',$_POST['body']);
$body=clean_post($body);
if ($edrow->body !==$body) {
	$body.="\n\n<p align=right><font size=-2>Edited on $current_date $current_clock by $row->sex $row->charname</font></p>";
	$update_string .="`body`='$body'";
	$body=stripslashes($body);$body=stripslashes($body);
	}
}else{$body=stripslashes($edrow->body);}

if(!empty($edrow->name)){
if (!empty($_POST['name'])) {
$name=clean_post($_POST['name']);
if ($edrow->name !==$name) {
	if(empty($update_string)){
		$update_string .="`name`='$name'";
	}else{
			$update_string .=", `name`='$name'";
	}
	$name=stripslashes($name);
	}
}else{$name=stripslashes($edrow->name);}
}

print '<form method=post action="'.'?sid='.$sid.'&action='.$action.'&kind='.$kind.'&did='.$did.'">
<table width=100%>
<tr><th colspan=2>Post editing</th></tr>
'.!empty($edrow->name)?'<tr><td valign=top nowrap>Topic name</td><td><input name=name value="'.$name.'" size=90></td></tr>':''.'
<tr><td valign=top>Message</td><td><textarea name=body cols=75 rows=15>'.$body.'</textarea></td></tr>
<tr><th colspan=2><input type=submit name=action value="Edit"></th></tr>
</table>
</form>
';
if (!empty($update_string)) {
mysqli_query($link, "UPDATE $edit_table SET $update_string WHERE `id`='$edrow->id' LIMIT 1") or print("Error ".mysqli_error($link)) and print("Post changed.");
}
}else{$body=stripslashes($edrow->body);}
print '
<table width=100% border=1><tr><td><table cellpadding=2 cellspacing=2 border=1 width=100% bordercolor='.$colth.'><tr><td valign=top>Posted message</td><td>'.postit($body); print '</td></tr></table></td></tr></table>
';
if($action == 'delete' and $kind == 'topic' and $edrow->deleted == 0){

mysqli_query($link, "UPDATE $tbl_topics SET deleted=1 WHERE id=$edrow->id LIMIT 1");
mysqli_query($link, "UPDATE $tbl_contents SET deleted=1 WHERE tid=$edrow->id");
print 'Topic erased.';

}elseif($action == 'delete' and $kind == 'reply' and $edrow->deleted == 0){

mysqli_query($link, "UPDATE $tbl_contents SET deleted=1 WHERE id=$edrow->id LIMIT 1");
mysqli_query($link, "UPDATE $tbl_topics SET replies=replies-1 WHERE id=$edrow->tid LIMIT 1");
print 'Post deleted.';
print '<br><a href="'.$_SERVER['HTTP_REFERER'].'">Go back!</a>';

}elseif($action == 'delete' and $edrow->deleted == 1){print 'Already deleted!';}



}else{print 'Nothing to edit here. You have no permissions to access this area.';}
}else{print 'Nothing to edit here.. The topic has been deleted in the meanwhile.';}
}else{print 'Nothing to edit here... The topic has been deleted in the meanwhile.';}
}else{print 'Nothing to edit here....';}

require_once($game_footer);
?>