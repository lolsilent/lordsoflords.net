<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);
include_once($html_header);

$tbl_pets='lol_pets';

if(!empty($_GET['sort'])){$sort=clean_post($_GET['sort']);if($sort !== 'level' and $sort !== 'xp'){$sort='level';}}else{$sort='level';}

print '<table width="100%"><tr><th colspan="7">Most Deadly Pets</th></tr><tr><td>#</td><td>Pet Name</td><td>Pet Race</td><td><a href="ladder_pets.php?sort=level">Level</a></td><td><a href="ladder_pets.php?sort=xp">XP</a></td><td>Attack Power</td><td>Mystic Power</td></tr>';

$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
if($sresult=mysqli_query($link, "SELECT * FROM `$tbl_pets` WHERE `id` ORDER BY `$sort` DESC LIMIT 50")){
$i=0;while($sobj=mysqli_fetch_object($sresult)){$i++;
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.$i.'</td><td>'.$sobj->petname.'</td><td>'.$sobj->petrace.'</td><td>'.lint($sobj->level).'</td><td>'.lint($sobj->xp).'</td><td>'.lint($sobj->str+$sobj->dex+$sobj->agi).'</td><td>'.lint($sobj->intel+$sobj->conc+$sobj->cont).'</td></tr>';
empty($bg)? $bg=1:$bg='';
}
mysqli_free_result($sresult);
}
mysqli_close($link);
print '</table>';
include_once($html_footer);
?>