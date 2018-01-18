<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

$charm_recharge=$current_time+(86400*30);
$charm_upcharge=$current_time+(86400*7);

$actions=array('transfer','market','sell','upcharge','recharge','decharge');
if(!empty($_GET['action'])){$action=clean_post($_GET['action']);if(!in_array($action, $actions)){$action='';}}else{$action='';}
if(!empty($_POST['action']) and empty($action)){$action=clean_post($_POST['action']);if(!in_array($action, $actions)){$action='';}}
if(!empty($_GET['cid'])){$cid=clean_post($_GET['cid']);}else{$cid='';}
if(!empty($_POST['cid']) and empty($cid)){$cid=clean_post($_POST['cid']);}

if(!empty($action) and !empty($cid)){
if($cidresult=mysqli_query($link, "SELECT * FROM `$tbl_charms` WHERE (`id`='$cid' and `charname`='$row->charname') LIMIT 1")){
if($cidobj=mysqli_fetch_object($cidresult)){
mysqli_free_result($cidresult);

print '<form method="post" action="charms.php?sid='.$sid.'">
<table width="100%">
<tr bgcolor="'.$colth.'"><th align="center" colspan="2">'.ucwords($action).' '.$cidobj->name.'<br>Charm '.$cidobj->id.'<br>Finder '.$cidobj->finder.'<br>';
if($cidobj->str){print "+$cidobj->str % Strength<br>";}
if($cidobj->dex){print "+$cidobj->dex % Dexterity<br>";}
if($cidobj->agi){print "+$cidobj->agi % Agility<br>";}
if($cidobj->intel){print "+$cidobj->intel % Intelligence<br>";}
if($cidobj->conc){print "+$cidobj->conc % Concentration<br>";}
if($cidobj->cont){print "+$cidobj->cont % Contravention<br>";}

print '</th></tr>
<input type="hidden" name="action" value="'.$action.'">
<input type="hidden" name="cid" value="'.$cid.'">';

			if ($action == 'transfer' and $cid==$cidobj->id and $cidobj->timer > $current_time){


if(!empty($_POST['recipient'])){$recipient=clean_post($_POST['recipient']);
$pinum=0;if($pnres=mysqli_query($link, "SELECT * FROM `$tbl_charms` WHERE `charname`='$recipient' ORDER BY `timer` ASC LIMIT 5")){
$pinum=mysqli_num_rows($pnres);mysqli_free_result($pnres);}
if($pinum<=4){
mysqli_query($link, "UPDATE `$tbl_charms` SET `charname`='$recipient' WHERE (`id`=$cidobj->id and `charname`='$row->charname') LIMIT 1");
print '<tr><th colspan="2">Giving away this charm to '.$recipient.'.</th></tr>';
mysqli_query($link, "INSERT INTO `$tbl_zlogs` VALUES(NULL,'$row->charname','Charm $cidobj->id to $recipient','charms','$ip','$current_time')");
}
}else{
print '<tr><td>Recipient</td><td width="50%"><select name="recipient">';
if($iresult=mysqli_query($link, "SELECT `sex`,`charname` FROM `$tbl_members` WHERE (`timer`>=($current_time-1000) and `charname`!='$row->charname') ORDER BY `timer` DESC LIMIT 100")){
while($iobj=mysqli_fetch_object($iresult)){
$inum=0;
if($nres=mysqli_query($link, "SELECT * FROM `$tbl_charms` WHERE `charname`='$iobj->charname' ORDER BY `timer` ASC LIMIT 5")){
$inum=mysqli_num_rows($nres);mysqli_free_result($nres);}
if ($inum<=4){
	print '<option value="'.$iobj->charname.'">'.$iobj->sex.' '.$iobj->charname.'</option>';
	}
}
mysqli_free_result($iresult);
}

print '</select></td></tr>
<tr><th colspan="2"><input type="submit" name="saction" value="Give him this charm!"></th></tr>
';
}


			}elseif ($action == 'market' and $cid==$cidobj->id and $cidobj->timer > $current_time){


if(!empty($_POST['sell_gold'])){$sell_gold=clean_int($_POST['sell_gold']);}else{$sell_gold=0;}
if(!empty($_POST['sell_creds'])){$sell_creds=clean_int($_POST['sell_creds']);}else{$sell_creds=0;}
if ($sell_gold > 0 or $sell_creds > 0){if ($sell_creds>=2500){$sell_creds=2500;}
if(!mysqli_fetch_object(mysqli_query($link, "SELECT * FROM `$tbl_market` WHERE `cid`=$cidobj->id LIMIT 1"))){
mysqli_query($link, "INSERT INTO `$tbl_market` VALUES(NULL,'$row->server_id','$cidobj->id','$row->charname','$sell_gold','$sell_creds','$current_time')");
print '<tr><th colspan="2">Placing this charm on the market!</th></tr>';
mysqli_query($link, "INSERT INTO `$tbl_zlogs` VALUES(NULL,'$row->charname','Charm $cidobj->id placed on the market','charms','$ip','$current_time')");
}else{
	print '<tr><th colspan="2">Already on the market up for sell!</th></tr>';
	}
}else{

print '<tr><td>Gold value</td><td width="50%"><input type="text" name="sell_gold"></td></tr>
<tr><td>Credits value</td><td width="50%"><input type="text" name="sell_creds"></td></tr>
<tr><th colspan="2"><input type="submit" name="saction" value="Try to sell this charm!"></th></tr>
';
}
			print '<tr><th colspan="2">Charms are sold for the price that you want to sell it for.</th></tr>';
			}elseif ($action == 'sell' and $cid==$cidobj->id and $cidobj->timer > $current_time){

$charm_pre_price=charm_price($cidobj);
if(!empty($_GET['confirm'])){
print '<tr><th colspan="2">Sold this charm for $'.lint($charm_pre_price).'!</th></tr>';
$update_it.=", `gold`=".($row->gold+$charm_pre_price);
mysqli_query($link, "DELETE FROM `$tbl_charms` WHERE `id`=$cidobj->id LIMIT 1");
mysqli_query($link, "INSERT INTO `$tbl_zlogs` VALUES(NULL,'$row->charname','Charm $cidobj->id sold for gold','charms','$ip','$current_time')");
}else{
print '<tr><th colspan="2"><br><a href="charms.php?sid='.$sid.'&amp;action=sell&amp;cid='.$cidobj->id.'&amp;confirm=1">Confirm the sell of this charm for $'.lint($charm_pre_price).'</a></th></tr>';
}

			}elseif ($action == 'upcharge' and $cid==$cidobj->id and $cidobj->timer > $current_time){


$charm_pre_price=charm_price($cidobj);
if($row->gold >= ($charm_pre_price/5) and ($cidobj->timer-$current_time) <= (86400*100)){
mysqli_query($link, "UPDATE `$tbl_charms` SET `timer`=`timer`+86400*7 WHERE (`id`=$cidobj->id and `charname`='$row->charname') LIMIT 1");
$update_it.=", `gold`=".($row->gold-($charm_pre_price/5));
$row->gold-=($charm_pre_price/5);
print '<tr><th colspan="2">Charm has been upcharged! <br>Active for '.''.dater($cidobj->timer+(86400*7)).'<br>';
if($row->gold >= ($charm_pre_price/5)){
print '<br><a href="charms.php?sid='.$sid.'&amp;action=upcharge&amp;cid='.$cidobj->id.'">Plus '.dater($charm_upcharge).' for $'.lint($charm_pre_price/5).' again!</a>';
}
print '</th></tr>';
}elseif($row->gold < ($charm_pre_price/5)) {
	print '<tr><th colspan="2">Not enough gold!</th></tr><?php }else{?><tr><th colspan="2">Is fully recharged!</th></tr>';
	}


			}elseif ($action == 'recharge' and $cid==$cidobj->id and $cidobj->timer < $current_time){


if($cidobj->name == 'Gods Charm' or $cidobj->name == 'Heavenly Charm'){
$charm_pre_price=charm_price($cidobj);
if($row->gold >= ($charm_pre_price/3)){
mysqli_query($link, "UPDATE `$tbl_charms` SET `timer`=$charm_recharge WHERE (`id`=$cidobj->id and `charname`='$row->charname') LIMIT 1");
print '<tr><th colspan="2">Charm is now recharged!</th></tr>';
$update_it.=", `gold`=".($row->gold-($charm_pre_price/3));
}else{
	print '<tr><th colspan="2">Not enough gold!</th></tr>';
	}
}else{print 'Sorry you can\'t do that!';}


			}elseif ($action == 'decharge' and $cid==$cidobj->id and $cidobj->timer < $current_time){

$decharged="`timer`=$charm_recharge";
if($cidobj->str){$decharged.=", `str`=`str`-1";}
if($cidobj->dex){$decharged.=", `dex`=`dex`-1";}
if($cidobj->agi){$decharged.=", `agi`=`agi`-1";}
if($cidobj->intel){$decharged.=", `intel`=`intel`-1";}
if($cidobj->conc){$decharged.=", `conc`=`conc`-1";}
if($cidobj->cont){$decharged.=", `cont`=`cont`-1";}
mysqli_query($link, "UPDATE `$tbl_charms` SET $decharged WHERE (`id`=$cidobj->id and `charname`='$row->charname') LIMIT 1");
print '<tr><th colspan="2">Charm is now decharged!</th></tr>';
mysqli_query($link, "INSERT INTO `$tbl_zlogs` VALUES(NULL,'$row->charname','Charm $cidobj->id decharged','charms','$ip','$current_time')");

			}else{
				print 'Sorry you can\'t do that!';}
print '</table></form>';
}else{print 'Sorry you can\'t do that!';}}else{print 'Sorry you can\'t do that!';}} else {

print '<table width="100%">
<tr><th nowrap>Charm slot I</th><th nowrap>Charm slot II</th><th nowrap>Charm slot III</th><th nowrap>Charm slot IV</th><th nowrap>Charm slot V</th></tr><tr>
';
if($cmresult=mysqli_query($link, "SELECT * FROM `$tbl_charms` WHERE `charname`='$row->charname' ORDER BY `timer` ASC LIMIT 5")){
$num=mysqli_num_rows($cmresult);
while($cmobj=mysqli_fetch_object($cmresult)){
$charm_price=charm_price($cmobj);
print '<td'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><b>'.$cmobj->name.'</b><br>Charm '.$cmobj->id.'<br>Finder '.$cmobj->finder.'<br>';
empty($bg)? $bg=1:$bg='';
if($cmobj->str){print "+$cmobj->str % Strength<br>";}
if($cmobj->dex){print "+$cmobj->dex % Dexterity<br>";}
if($cmobj->agi){print "+$cmobj->agi % Agility<br>";}
if($cmobj->intel){print "+$cmobj->intel % Intelligence<br>";}
if($cmobj->conc){print "+$cmobj->conc % Concentration<br>";}
if($cmobj->cont){print "+$cmobj->cont % Contravention<br>";}

if($cmobj->timer > $current_time){
print '<br>Active for '.dater($cmobj->timer).'<br>';
print '<br><a href="charms.php?sid='.$sid.'&amp;action=transfer&amp;cid='.$cmobj->id.'">Transfer</a><br>';
if(!mysqli_fetch_object(mysqli_query($link, "SELECT * FROM `$tbl_market` WHERE `cid`=$cmobj->id LIMIT 1"))){
print '<br><a href="charms.php?sid='.$sid.'&amp;action=market&amp;cid='.$cmobj->id.'">Market</a><br>';
}
if($row->gold >= ($charm_price/5) and ($cmobj->timer-$current_time) <= (86400*100)){
print '<br><a href="charms.php?sid='.$sid.'&amp;action=upcharge&amp;cid='.$cmobj->id.'">Plus '.dater($charm_upcharge).' for $'.lint($charm_price/5).'</a><br>';
}elseif($row->gold < ($charm_price/5)){
	print '<br>Upcharge requires $'.lint($charm_price/5).'!<br>';}
if($cmobj->name !== 'Gods Charm' and $cmobj->name !== 'Heavenly Charm'){
	print '<br><a href="charms.php?sid='.$sid.'&amp;action=sell&amp;cid='.$cmobj->id.'">Sell for $'.lint($charm_price).'</a><br>';
}
}else{
print '<br><font color=red>INACTIVE</font><br>';
if($cmobj->name !== 'Gods Charm' and $cmobj->name !== 'Heavenly Charm'){
print '<a href="charms.php?sid='.$sid.'&amp;action=decharge&amp;cid='.$cmobj->id.'">Decharge to '.dater($charm_recharge).' </a><br>';
}else{
if($row->gold >= ($charm_price/3)){
print '<a href="charms.php?sid='.$sid.'&amp;action=recharge&amp;cid='.$cmobj->id.'">Recharge to '.dater($charm_recharge).' for $'.lint($charm_price/3).'</a><br>';
}else{
	print 'Recharge requires $'.lint($charm_price/3).'!';}
}
}

print '</td>';
}
mysqli_free_result($cmresult);
}
if($num<5){
for($i=(1+$num);$i<=5;$i++){
print '<td align="center"'.(empty($bg)?' bgcolor="'.$colth.'"':'').'>Empty</td>';
}
}
print '</tr></table>';
}
require_once($game_footer);

function charm_price($cmobj){
return round(1000+(100+$cmobj->str+$cmobj->dex)*(100+$cmobj->intel+$cmobj->conc)*(100+$cmobj->agi+$cmobj->cont));
}
?>