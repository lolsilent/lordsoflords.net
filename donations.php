<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);

include_once($html_header);

$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);

if($tpresult=mysqli_query($link, "SELECT * FROM `$tbl_paypal` WHERE `id` ORDER BY `amount` DESC LIMIT 10000")){
$numrows=mysqli_num_rows($tpresult);
if($numrows >= 1){
print '<table>
<tr><th colspan="3">All time hall of fame Donated Dominator\'s</th></tr>';

$donators=array();
while($tpobj=mysqli_fetch_object($tpresult)){

if(!array_key_exists($tpobj->ip,$donators)){
$donators[$tpobj->ip] = $tpobj->amount;
}else{
$donators[$tpobj->ip] += $tpobj->amount;
}

}
mysqli_free_result($tpresult);

arsort($donators);
$amount=array_sum($donators);

foreach ($donators as $key=>$val){
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.$key.'</td><td>'.lint($val,2).'</td><td>'.lint(($val/$amount)*100,2).'%</tr>';
empty($bg)? $bg=1:$bg='';
}

print '<tr><th colspan="3">A totality of '.lint($numrows).' donations where made by '.count($donators).' players with a total sum of $'.lint($amount,2).'.</th></tr></table>';

}}

mysqli_close($link);
include_once($html_footer);
?>