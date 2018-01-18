<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($inc_titles);
require_once($game_header);

$inactive=(time()-(60*60*24*60));
$iz_where='';
$and_where="`timer`>$inactive and `xp`>=`level`*1000000";


nobility($iz_where,$male,'Lords');
nobility($iz_where,$female,'Ladies');
nobility($iz_where,$alien,'Aliens');
nobility($iz_where,$evil,'Evils');
nobility($iz_where,$angel,'Angels');
nobility($iz_where,$crazy,'Craziest');

require_once($game_footer);

function nobility($iz_where,$array_sex,$sexy){
print '<table width="100%">';
	global $row,$link,$tbl_members,$tbl_papers,$current_time,$and_where,$colth,$emotions_url;
$limit=5;
$limit+=count($array_sex);
foreach($array_sex as $val){$iz_where .="`sex`='$val' or ";}
$iz_where=preg_replace("/ or $/i","",$iz_where);
			//print "($and_where) and ($iz_where) and (`level` >= '100')";
if($nresult=mysqli_query($link, "SELECT `id`,`clan`,`sex`,`charname`,`race`,`level`,`xp`,`fp` FROM `$tbl_members` WHERE (`server_id`='$row->server_id') and ($and_where) and ($iz_where) and (`level` >= '1000') ORDER BY `level` DESC LIMIT 25")){
	if(mysqli_num_rows($nresult)>=1){
print '<tr><th colspan="4">'.$sexy.'</th></tr><tr><td>#</td><td>Charname</td><td>Race</td><td>Level</td></tr>';
$i=0;while($nobj=mysqli_fetch_object($nresult)){
$safe_sex=$nobj->sex;

if(in_array($nobj->sex,$array_sex)){
if(!empty($array_sex[$i])){
	if($array_sex[array_search($nobj->sex,$array_sex)] !== $array_sex[$i]){$safe_sex=$array_sex[$i];}
}else{$safe_sex=end($array_sex);$safe_sex=prev($array_sex);}
if($safe_sex == end($array_sex)){$safe_sex=prev($array_sex);}
}
if(!empty($safe_sex) and $safe_sex !== $nobj->sex){
mysqli_query($link, "UPDATE `$tbl_members` SET `sex`='$safe_sex' WHERE `id`=$nobj->id LIMIT 1");
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','1','$nobj->sex $nobj->charname is now known as $safe_sex $nobj->charname',$current_time)");
}
$i++;
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.$i.'</td><td>';

if(($nobj->fp-$current_time)>1){print '<img src="'.$emotions_url.'/star.gif" border="0" alt="Premium member">';}

print '<a href="members.php?info='.$nobj->charname.'">'.(!empty($nobj->clan)?"[$nobj->clan]":'').$safe_sex.' '.$nobj->charname.'</a>'.($nobj->sex!==$safe_sex?' <sup><b>NEW!</b></sup>':'').'</td><td>'.$nobj->race.'</td><td>'.lint($nobj->level).'</td></tr>';
empty($bg)? $bg=1:$bg='';
}}
mysqli_free_result($nresult);
}
print '</table>';
}
?>