<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);
if(!empty($_GET['sid'])){require_once($game_header);}else{include_once($html_header);
$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);if (!empty($_GET['served'])){
$row->server_id=clean_post($_GET['served']);
}else{$row=new stdClass; $row->server_id=1;}}

print '<table width="100%"><tr><th>'.$title.' times</th></tr>';
if($presult=mysqli_query($link, "SELECT * FROM `$tbl_papers` WHERE (`server_id`='$row->server_id' and `pid`='1') ORDER BY `id` DESC LIMIT 50")){
while($pobj=mysqli_fetch_object($presult)){
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.$pobj->news.', '.dater($pobj->timer).' ago.</td></tr>';
empty($bg)? $bg=1:$bg='';
}
mysqli_free_result($presult);
}
print '</table>';

if($lpresult=mysqli_query($link, "SELECT * FROM `$tbl_papers` WHERE (`server_id`='$row->server_id' and `pid`='1') ORDER BY `id` DESC")){
if(mysqli_num_rows($lpresult) >= 50){$somecontent='';
print '<table width="100%">';

while($lpobj=mysqli_fetch_object($lpresult)){$somecontent.='<br>'.$lpobj->news.'.';}mysqli_free_result($lpresult);

mysqli_query($link, "DELETE FROM `$tbl_papers` WHERE (`server_id`='$row->server_id' and `pid`='1' and `timer`<($current_time-(60*60*24*7)))");
}}
/*
$maxfilesize=50000;
$logdate=date('d-m-Y');
$filename='paper/'.$logdate.'.php';
if(file_exists($filename)){$filesize=filesize($filename);}else{$filesize=0;}

if($filesize>$maxfilesize){$i=0;while($filesize >= $maxfilesize){$i++;
	if ($i == 1){$filename=preg_replace("/.php/i","-$i.php",$filename);
	}else{$filename=preg_replace("/-(\d+).php/i","-$i.php",$filename);}
	if(file_exists($filename)){$filesize=filesize($filename);}else{$filesize=0;}
	//print $filename." $filesize $i<br>";
}}

if(file_exists($filename) and !empty($somecontent)) {
$exist=implode('',file($filename));
unlink($filename);
$somecontent=preg_replace("/<!--CHATLOG-->/i","<!--CHATLOG-->$somecontent",$exist);
writer($filename,'w+',$somecontent);
}elseif(!file_exists($filename) and !empty($somecontent)) {
$somecontent=$logstart.'Log date: '.$logdate.'<table width="100%"><tr><td><!--CHATLOG-->'.$somecontent.'</td></tr></table>'.$logend;
writer($filename,'a+',$somecontent);
}}}
*/
if(!empty($_GET['sid'])){require_once($game_footer);}else{mysqli_close($link);include_once($html_footer);}
?>