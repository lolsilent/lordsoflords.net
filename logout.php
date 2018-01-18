<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);

if(!empty($_GET['sid']) and empty($sid)){$sid=clean_post($_GET['sid']);}
if(!empty($_POST['sid']) and empty($sid)){$sid=clean_post($_POST['sid']);}
if(empty($sid)){header("Location: $root_url/login.php");exit;}

include_once($html_header);

$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
if($result=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `sid`='$sid' and `timer`>=($current_time-1000) LIMIT 1")){
if($row=mysqli_fetch_object($result)){
mysqli_free_result($result);
$sid=md5($current_time.$row->id);
$current_time=round($current_time-1000);
mysqli_query($link, "UPDATE `$tbl_members` SET `sid`='$sid',`timer`='$current_time' WHERE `id`='$row->id' LIMIT 1") or print(mysqli_error($link));

$optimizer='';$tot_tables=count($table_names);
for ($i=0;$i<$tot_tables;$i++) {
if($i<($tot_tables-1)){
	$optimizer .= "`$table_names[$i]`,";
}elseif($i==($tot_tables-1)){
	$optimizer .= "`$table_names[$i]`";
}else{break;}
}

if(!empty($optimizer)){
	mysqli_query($link, "OPTIMIZE TABLE $optimizer") or some_error($optimizer.'-'.__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
}

//setcookie("sid", '', -500);
//setcookie("world", '', -500);

}else{mysqli_close($link);header("Location: $root_url");exit;}}else{mysqli_close($link);header("Location: $root_url");exit;}


print '
<h3>Thank you for your time to play '.$title.'.<br><br>
Hope to see you back again.</h3><br><br>';

if($presult=mysqli_query($link, "SELECT * FROM `$tbl_paypal` WHERE (`month`='".date("m")."' and `year`='".date("Y")."') ORDER BY `amount` DESC LIMIT 100")){
if(mysqli_num_rows($presult) >= 1){
	print '<table width="100%">
<tr><th colspan="2">This month we thank these players who donated to the game</th></tr>';
$amount=0;$i=0;
while($pobj=mysqli_fetch_object($presult)){
print '<tr';
if(empty($bg)){
	print ' bgcolor="'.$colth.'"';$bg=1;
	}else{$bg='';}
		print '><td>'.$pobj->ip.'</td><td>$'.lint($pobj->amount,2).'</td></tr>';
$amount+=$pobj->amount;$i++;
}
mysqli_free_result($presult);
print '<tr><td>'.lint($i).' players donated </td><td>$'.lint($amount,2).'</td></tr></table>';
}}

mysqli_close($link);
include_once($html_footer);
?>