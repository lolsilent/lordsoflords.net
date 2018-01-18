<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);
require_once($inc_races);
include_once($html_header);
$somecontent='';

$served=1;
if (!empty($_GET['served'])){
$served=clean_post($_GET['served']);
}

$races_array_keys=array_keys($races_array);
sort($races_array_keys);
$maxi=100;$raced='';
if(empty($_GET['sort'])){$sort='level';}else{$sort=clean_post($_GET['sort']);
if($sort == 'level' or $sort == 'xp'){$sort="$sort";}else{$sort='level';}}
if(empty($_GET['i'])){$i=1;}else{$i=clean_post($_GET['i']);}

if(empty($_GET['race'])){$race='';$where='id';$racer='';}else{
if(in_array($_GET['race'],$races_array_keys)){$race=clean_post($_GET['race']);$where="race='$race'";$raced="&amp;race=$race";}else{$where='id';}}
if(!empty($_GET['page'])){$page=round(clean_post($_GET['page']));$where.=" and $sort<$page";}
print '
<table width="100%">
<tr>
<th colspan="7">Top players sorted by '.$sort.',';
foreach($races_array_keys as $val){
if (isset($race) AND $race !== $val) {
	print ' <a href="ladder.php?sort='.$sort.'&amp;race='.$val.'&amp;served='.$served.'">'.$val.'</a>';
	}else{
		print ' '.$val;
	}
}
print '</th>
</tr>
<tr>
<td align="center">#</td><td>Charname</td><td>Race</td><td><a href="ladder.php?sort=level'.$raced.'&amp;served='.$served.'">Level</a></td><td><a href="ladder.php?sort=xp'.$raced.'&amp;served='.$served.'">Xp</a></td></tr>';

$where .= " and `sex`!='admin' and `sex`!='cop' and `sex`!='mod' and `sex`!='support' and `server_id`='$served'";

$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
if($result=mysqli_query($link, "SELECT `id`,`clan`,`sex`,`charname`,`race`,`level`,`xp`,`timer`,`mute`,`jail`,`fp` FROM `$tbl_members` WHERE $where ORDER BY `$sort` DESC LIMIT $maxi")){
while($iobj=mysqli_fetch_object($result)){

if(empty($_GET['race'])){$somecontent.="<tr><td>$i</td><td nowrap>".($iobj->clan ? "[$iobj->clan] ":'')."$iobj->sex $iobj->charname</td><td>$iobj->race</td><td>".lint($iobj->level)."</td><td>".lint($iobj->xp)."</td></tr>";}

print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.($i).'</td><td nowrap><a href="members.php?info='.$iobj->charname.'">';

empty($bg)? $bg=1:$bg='';

if(($iobj->fp-$current_time)>1){
	print '<img src="'. $emotions_url.'/star.gif" border="0" alt="Premium member">';
}
print ($iobj->clan ? "[$iobj->clan] ":'').$iobj->sex.' '.$iobj->charname;
print '</a>'.($iobj->timer>=($current_time-1000)?'<sup><b>Online</b></sup>':'');
print $iobj->mute-$current_time>=1?' <sup><b>Defmuted('.dater($iobj->mute).')</b></sup>':'';
print $iobj->jail-$current_time>=1?' <sup><b>Jailed('.dater($iobj->jail).')</b></sup>':'';
print '</td><td>'.$iobj->race.'</td><td>'.lint($iobj->level).'</td><td>'.lint($iobj->xp).'</td></tr>';

$i++;$page=$iobj->$sort;
}
mysqli_free_result($result);
}

mysqli_close($link);

if($i<=901 and !empty($page) and $i>=$maxi){
print '<tr><th colspan="6"><a href="ladder.php?sort='. $sort.'&amp;i='.$i.'&amp;page='. $page.''.$raced.'">';
print $i.'-'.($i+99).'</a></th></tr>';
}
print '</table><br>Players with a <img src="'.$emotions_url.'/star.gif" border="0" alt="Premium member"> in front of their name are supporting this game with donations.';

/*
if(empty($_GET['race']) and !empty($somecontent)){

$filename='ladder/'.date('d-m-Y').'.php';
	//if(file_exists($filename)) {unlink($filename);}
if(!file_exists($filename) and !empty($somecontent)) {
$somecontent=$logstart.'<table width="100%"><tr><th colspan="6">'.$title.' Ladder log of '.date('d m Y').'</th></tr><tr><th>#</th><th>Charname</th><th>Race</th><th>Level</th><th>Xp</th></tr>'.$somecontent.'</table>'.$logend;

writer($filename,'a+',$somecontent);
}

}
*/
include_once($html_footer);
?>