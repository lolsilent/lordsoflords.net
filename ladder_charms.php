<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);
include_once($html_header);

$hstats=array('str','dex','agi','intel','conc','cont');
if(!empty($_GET['sort'])){if(in_array($_GET['sort'],$hstats)){$sort=clean_post($_GET['sort']);}else{$sort='str';}}else{$sort='str';}
if(!empty($_GET['ghc'])){$ghc=clean_post($_GET['ghc']);}else{$ghc='';}
if(!empty($_GET['cids'])){$cids=clean_post($_GET['cids']);}else{$cids='';}
print '
<table width="100%">
<tr><th colspan="10">Best Charms <a href="ladder_charms.php?ghc=Gods charm">God charms</a> <a href="ladder_charms.php?ghc=Heavenly charm">Heavenly charms</a></th></tr>
<tr><td>#</td><td>Charm name</td><td>Finder</td><td>Owner</td>
';
foreach($hstats as $val){
print '<td><a href="ladder_charms.php?sort='.strtolower($val).'">'.ucfirst($val).'</a></td>';
}
print '
</tr>
';
$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);


if($ghc == 'Gods charm'){
$hquery="SELECT * FROM `$tbl_charms` WHERE `name`='Gods charm' ORDER BY `$sort` DESC LIMIT 100";
}elseif($ghc == 'Heavenly charm'){
$hquery="SELECT * FROM `$tbl_charms` WHERE `name`='Heavenly charm' ORDER BY `$sort` DESC LIMIT 100";
}elseif(!empty($cids)){
$hquery="SELECT * FROM `$tbl_charms` WHERE `id`='$cids' ORDER BY `$sort` DESC LIMIT 100";
}else{
$hquery="SELECT * FROM `$tbl_charms` WHERE `id` and `name`!='Gods charm' and `name`!='Heavenly charm' ORDER BY `$sort` DESC LIMIT 50";
}
$hresult=mysqli_query($link, $hquery);
$num=1;
while($hobj=mysqli_fetch_object($hresult)){
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.$num.'</td><td>'.$hobj->name.'</td><td>'.$hobj->finder.'</td><td>'.$hobj->charname.'</td><td>'.$hobj->str.'</td><td>'.$hobj->dex.'</td><td>'.$hobj->agi.'</td><td>'.$hobj->intel.'</td><td>'.$hobj->conc.'</td><td>'.$hobj->cont.'</td></tr>';
empty($bg)? $bg=1:$bg='';
$num++;
}
mysqli_free_result($hresult);
mysqli_close($link);
print '</table>';
include_once($html_footer);
?>