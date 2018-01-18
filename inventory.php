<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

print '<table width="100%"><tr><th colspan="4">Usable Gear<br>Fight level 50+ monsters in the world to find items!</th></tr><tr><td>Item</td><td>Power Level</td><td>Actions</td></tr>';

if (!empty($_GET['activate'])) {
	$in_id=clean_post($_GET['activate']);
	$activate_time = $current_time+1000;
mysqli_query($link, "UPDATE `$tbl_items` SET `timer`='$activate_time' WHERE (`id`='$in_id' AND `mid`='$row->id') LIMIT 1") or die(mysqli_error($link));
}

if (!empty($_GET['delete'])) {
	$in_id=clean_post($_GET['delete']);
mysqli_query($link, "DELETE FROM `$tbl_items` WHERE (`id`='$in_id' AND `mid`='$row->id') LIMIT 1") or die(mysqli_error($link));
}

if (!empty($_POST['recipient']) and !empty($_GET['transfer'])) {
	$in_id = clean_post($_GET['transfer']);
	$recipient=clean_post($_POST['recipient']);
mysqli_query($link, "UPDATE `$tbl_items` SET `mid`='$recipient' WHERE (`id`='$in_id' AND `mid`='$row->id') LIMIT 1") or die(mysqli_error($link));
}

if($iresult=mysqli_query($link, "SELECT * FROM `$tbl_items` WHERE `mid`='$row->id' ORDER BY `timer` DESC LIMIT 10")){
while($irow=mysqli_fetch_object($iresult)){
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.ucfirst($item_sub[$irow->sub]).' '.ucfirst($item_kinds[$irow->kind]).'</td><td> + '.$irow->value.'% '.$item_sub[$irow->sub].'</td><td>';
empty($bg)? $bg=1:$bg='';
	if ($irow->timer >= 1 and ($irow->timer-$current_time) <= 0) {
mysqli_query($link, "DELETE FROM `$tbl_items` WHERE `id`='$irow->id' LIMIT 1");
print 'Decayed';
	}else{
		if ($irow->timer >= 1) {
print number_format($irow->timer-$current_time,2).' seconds';
		}else{
print '<a href="?sid='.$sid.'&delete='.$irow->id.'">Delete</a> <a href="?sid='.$sid.'&activate='.$irow->id.'">Activate</a> <a href="?sid='.$sid.'&transfer='.$irow->id.'">Transfer</a>';
		}
	}
	
	if (!empty($_GET['transfer'])) {
		$transfer = clean_post($_GET['transfer']);
		if ($transfer == $irow->id) {
				if($oresult=mysqli_query($link, "SELECT `id`,`sex`,`charname` FROM `$tbl_members` WHERE (`timer`>$current_time-1000 and `charname`!='$row->charname') ORDER BY `level` ASC LIMIT 100")){
if(mysqli_num_rows($oresult) >= 1) {
print '<form method="post" action="?sid='.$sid.'&transfer='.$transfer.'"><select name="recipient">';
					while($pobj=mysqli_fetch_object($oresult)){
print '<option value="'.$pobj->id.'">'.$pobj->sex.' '.$pobj->charname.'</option>';
					}
					mysqli_free_result($oresult);
print '</select><input type="submit" value="Send"></form>';
}else{print 'Nobody online!';}
				}
		$_GET['transfer']='';
		$transfer='';
		}
	}
print '</td></tr>';
}
mysqli_free_result($iresult);
}

print '</table>';

$inventoryitems='AdMiN/arrays';
require_once($inventoryitems.'/array.weapons.php');
require_once($inventoryitems.'/array.spells.php');
require_once($inventoryitems.'/array.heals.php');
require_once($inventoryitems.'/array.helms.php');
require_once($inventoryitems.'/array.shields.php');
require_once($inventoryitems.'/array.amulets.php');
require_once($inventoryitems.'/array.rings.php');
require_once($inventoryitems.'/array.armors.php');
require_once($inventoryitems.'/array.belts.php');
require_once($inventoryitems.'/array.pants.php');
require_once($inventoryitems.'/array.hands.php');
require_once($inventoryitems.'/array.feets.php');

print '<table width="100%"><tr><th colspan="4">Inventory</th></tr><tr><td>Equipment</td><td>Name</td><td>Power</td></tr>';

$current=array();
foreach($items as $val){
array_push($current,$row->$val);
}

$num=0;
foreach($items as $val){
$nim=$current[$num]/100;
$nim=round($nim,3);
if($num == 0){
$max=count($weapons); if($nim>=$max){$nim=$max-1;}$whot="$weapons[$nim]";
}elseif($num == 1){
$max=count($spells); if($nim>=$max){$nim=$max-1;}$whot="$spells[$nim]";
}elseif($num == 2){
$max=count($heals); if($nim>=$max){$nim=$max-1;}$whot="$heals[$nim]";
}elseif($num == 3){
$max=count($helms); if($nim>=$max){$nim=$max-1;}$whot="$helms[$nim]";
}elseif($num == 4){
$max=count($shields); if($nim>=$max){$nim=$max-1;}$whot="$shields[$nim]";
}elseif($num == 5){
$max=count($amulets); if($nim>=$max){$nim=$max-1;}$whot="$amulets[$nim]";
}elseif($num == 6){
$max=count($rings); if($nim>=$max){$nim=$max-1;}$whot="$rings[$nim]";
}elseif($num == 7){
$max=count($armors); if($nim>=$max){$nim=$max-1;}$whot="$armors[$nim]";
}elseif($num == 8){
$max=count($belts); if($nim>=$max){$nim=$max-1;}$whot="$belts[$nim]";
}elseif($num == 9){
$max=count($pants); if($nim>=$max){$nim=$max-1;}$whot="$pants[$nim]";
}elseif($num == 10){
$max=count($hands); if($nim>=$max){$nim=$max-1;}$whot="$hands[$nim]";
}elseif($num == 11){
$max=count($feets); if($nim>=$max){$nim=$max-1;}$whot="$feets[$nim]";
}else{
$whot="Elements of Dark Shadows";
}

print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.ucfirst($val).'</td><td>'.ucwords($whot).'</td><td>'.lint($current[$num]).'</td></tr>';

$num++;
}

print '</table>';
require_once($game_footer);
?>