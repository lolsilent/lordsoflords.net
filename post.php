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

$see='0';if($row->level >= 2){if (!empty($_POST['see'])) {$see=clean_post($_POST['see']);}}
if (!empty($_POST['topic'])) {$topic=clean_post($_POST['topic']);} else {$topic='';}
if (!empty($_POST['message'])) {
$message=clean_post($_POST['message']);

$mstrlen = strlen($message);
if ($mstrlen >= 10 and $mstrlen <= 10000 and !empty($message)) {

if (!empty($_GET['tid'])) {$tid=clean_post($_GET['tid']);} else {$tid='';}

if(!empty($pc) and !preg_match('@(\[c=.*?\])@si',$message)){$message='[c='.$pc.']'.$message.'[/c]';}

if ($_POST['action'] == 'Create topic' and !empty($topic)) {
if (!empty($_POST['fid'])) {$fid=clean_post($_POST['fid']);} else {$fid='0';}

mysqli_query($link, "INSERT INTO `$tbl_topics` values ('','$row->server_id','$fid','$row->id','0','$topic','$message','0','0','$current_time','$current_time','$current_time','$see','0','$ip')") or die(mysqli_error($link));
print '<p><a href="forum.php?sid='.$sid.'">Topic created, click here to return to the forums.</a></p>';

} elseif ($_POST['action'] == 'Post reply' and !empty($tid)) {

mysqli_query($link, "INSERT INTO `$tbl_contents` values ('','$row->server_id','$tid','$row->id','$current_date','$message','$current_time','$see','0','$ip')") or die(mysqli_error($link));
mysqli_query($link, "UPDATE `$tbl_topics` SET `replies`=`replies`+1,`last`='$current_time' WHERE `id`='$tid' LIMIT 1") or die(mysqli_error($link));
print '<p><a href="forum.php?sid='.$sid.'">Reply posted, click here to return to the forums.</a> or <a href="topic.php?sid='.$sid.'&tid='.$tid.'"> here to return to the topic.</a></p>';

}

} else {
print '<b><p>Message too short or too long!</p>Your post may not contain less than 10 characters and more than 10.000 characters. Your posted message has '.lint($mstrlen).' characters.</b><p>Please go back with your browser and try again.</p>';
}
}else{print '<p><a href="forum.php?sid='.$sid.'">What do you think of this? Click here to return to the forums.</a></p>';}
require_once($game_footer);
?>