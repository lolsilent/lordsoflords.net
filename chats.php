<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_emotions);
require_once($inc_functions);

include_once($html_header);

$served=1;
if (!empty($_GET['served'])){
$served=clean_post($_GET['served']);
}

$chat_timer=600;

$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);

if($cresult=mysqli_query($link, "SELECT * FROM `$tbl_board` WHERE (`server_id`='$served') ORDER BY `id` DESC LIMIT 50")){
if(mysqli_num_rows($cresult) >= 1){
print '<table width="100%">';
$i=0;
while($bobj=mysqli_fetch_object($cresult)){
print '<tr';
if (empty($bg)){
	print ' bgcolor="'.$colth;
	$bg=1;
	print '"';
}else{
	$bg='';
}
print '><td>';
if($bobj->star){
	print '<img src="'.$emotions_url.'/star.gif" border="0">';
}
print (!empty($bobj->clan)?"[$bobj->clan]":'').' '.$bobj->sex.' '.$bobj->charname;
print '<font size="1"> '.$bobj->race.' '.lint($bobj->level).'</font>'.chatit($bobj->message).($current_time-$bobj->timer >= 600?' <font size=-2>('.dater($bobj->timer).')</font>':'');
print '</td></tr>';
}
mysqli_free_result($cresult);
print '</table>';
}else{print 'Sssst it is very quiet in here.';}
}
mysqli_close($link);
include_once($html_footer);
?>