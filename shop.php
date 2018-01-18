<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

if(!empty($_POST['action'])){$costed=0;
foreach($items as $val){
if(!empty($_POST[$val])){
	$amount=clean_int($_POST[$val]);
	if($row->str >= $row->intel){$max=$row->str;}else{$max=$row->intel;}
if(($row->$val+$amount) >= $max){$amount=$max-$row->$val;}
if($row->$val >= $max or $amount < 1){continue;}

	$total_price=(49+($row->level*$row->$val))*$amount;

	if($row->gold >= $total_price){
	print 'Buy '.lint($amount).' '.$val.' upgrade'.($amount>1?"s":"").' for '.lint($total_price).' gold.<br>';
	$row->gold -=$total_price;
	$costed+=$total_price;
	$row->$val+=$amount;
	$update_it.=", `$val`=".$row->$val;
	}
}
}

if($costed){
	print 'Total upgrade costs '.lint($costed).' gold.<table width="100%"><tr><th>Level '.lint($row->level).'</th><th>Life '.lint($row->life).'</th><th>'.lint($row->xp).' XP</th><th>$'.lint($row->gold-$costed).'</th>'.($fp>1?'<th>'.lint($fp).' FP</th>':'').'></tr></table>';
	$update_it.=", `gold`=$row->gold";
	}
}

print '<form method="post" action="shop.php?sid='.$sid.'">
<table width="100%"><tr>
<th colspan="4">Shopping</th></tr>
<tr><td>Equipments</td><td>Your power (max '.($row->str>=$row->intel?lint($row->str):lint($row->intel)).')</td><td>Price per up</td><td>Buy up</td></tr>';
$maxed=0;
foreach($items as $val){
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'>
<td>'.ucfirst($val).'</td>
<td>'.lint($row->$val).'</td><td>$'.lint(49+($row->level*$row->$val)).'</td>
<td width="100">';
if($row->$val < $row->str or $row->$val < $row->intel){
	print '<input type="text" name="'.$val.'" maxlength="10">';
	}else{
		print 'Maxed';
		$maxed++;
		}print '</td></tr>';

empty($bg)? $bg=1:$bg='';
}

if($maxed<count($items)){
	print '<tr><th colspan="4"><input type="submit" name="action" value="Buy upgrades!"></th></tr>';
	}else{
		print '<tr><td colspan=4>All your inventory items are upgraded to the maximum that you can handle with your current intelligence and strength.</td></tr>';
		}
print '</table></form>';
require_once($game_footer);
?>