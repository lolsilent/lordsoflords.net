<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);
include_once($html_header);

if(!empty($_GET['sort'])){$sort=clean_post($_GET['sort']);if($sort !== 'duelsl' and $sort !== 'kills' and $sort !== 'deads'){$sort='duelsw';}}else{$sort='duelsw';}

print '<table width="100%"><tr><th colspan="6">Most deadly players</th></tr><tr><td>#</td><td>Charname</td><td><a href="ladder_history.php?sort=duelsw">Duels won</a></td><td><a href="ladder_history.php?sort=duelsl">Duels lost</a></td><td><a href="ladder_history.php?sort=kills">Moster Kills</a></td><td><a href="ladder_history.php?sort=deads">Deads</a></td></tr>';

$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
if($sresult=mysqli_query($link, "SELECT * FROM `$tbl_history` WHERE `id` ORDER BY `$sort` DESC LIMIT 50")){
$i=0;while($sobj=mysqli_fetch_object($sresult)){$i++;
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.$i.'</td><td>'.$sobj->charname.'</td><td>'.lint($sobj->duelsw).'</td><td>'.lint($sobj->duelsl).'</td><td>'.lint($sobj->kills).'</td><td>'.lint($sobj->deads).'</td></tr>';
empty($bg)? $bg=1:$bg='';
}
mysqli_free_result($sresult);
}
mysqli_close($link);
print '</table>';
include_once($html_footer);
?>