<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);
//print 'Function temporarily disabled.';require_once($game_footer);exit;

$multier=round($row->level/250);if($multier<1){$multier=1;}

$sitems=array(
'Experience' => array(100,5000000000,'xp','xp'),
'Gold' => array(50,5000000000,'gold','gold'),
'Freeplay 30' => array(500,'2592000','fp','Freeplay for 30 days'),
'Freeplay 5' => array(100,'432000','fp','Freeplay for 5 days'),
'Heavenly Charm' => array(2000,'','','+100% to all stats'),
'Gods Charm' => array(2500,'','','+127% to all stats')
);

if ($wrow->game_mode == 1) {
$sitems=array(
'Experience' => array(100,5000000000,'xp','xp'),
'Gold' => array(50,5000000000,'gold','gold'),
'Freeplay 30' => array(500,'2592000','fp','Freeplay for 30 days'),
'Freeplay 5' => array(100,'432000','fp','Freeplay for 5 days'),
'Heavenly Charm' => array(2000,'','','+100% to all stats'),
'Gods Charm' => array(2500,'','','+127% to all stats'),
'Admin Title' => array(5000,'','','Become an Admin')
);
}

if($hresult=mysqli_query($link, "SELECT `id` FROM `$tbl_charms` WHERE `charname`='$row->charname' LIMIT 10")){
$openslots =mysqli_num_rows($hresult);
mysqli_free_result($hresult);
}

error_reporting(0);

$cobj = new stdClass;
$cobj->credits = new stdClass;
if($cresult=mysqli_query($link, "SELECT * FROM `$tbl_credits` WHERE (`username`='$row->username' and `charname`='$row->charname') ORDER BY `id` DESC LIMIT 1")){
if($cobj=mysqli_fetch_object($cresult)){
mysqli_free_result($cresult);
if($cobj->credits >= 1 and !empty($_POST['action'])){
$action=clean_post($_POST['action']);
if(array_key_exists($action,$sitems)){

if($cobj->credits >= $sitems[$action][0]){
if(empty($sitems[$action][1]) and empty($sitems[$action][2])){
	//CHARM

if($action == 'Gods Charm'){
mysqli_query($link, "INSERT INTO `$tbl_charms` VALUES ('','$row->charname','$row->charname','Gods Charm',127,127,127,127,127,127,$current_time+1000000)") or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__.mysqli_error($link));
print 'Traded some credits for a Gods Charm.';
}elseif($action == 'Heavenly Charm'){
mysqli_query($link, "INSERT INTO `$tbl_charms` VALUES ('','$row->charname','$row->charname','Heavenly Charm',100,100,100,100,100,100,$current_time+1000000)") or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__.mysqli_error($link));
print 'Traded some credits for a Heavenly Charm.';
}

	//CHARM
}else{
	//OTHER
if($action == 'Freeplay 30'){
	$update_it.=", `".$sitems[$action][2]."`=$current_time+".$sitems[$action][1];
}elseif($action == 'Freeplay 5'){
	$update_it.=", `".$sitems[$action][2]."`=$current_time+".$sitems[$action][1];
}elseif($action == 'Gold' or $action == 'Experience'){
	$update_it.=", `".$sitems[$action][2]."`=`".$sitems[$action][2]."`+".($sitems[$action][1]*$multier);
}elseif($action == 'Admin Title' and $wrow->game_mode == $game_modes[1]){
	$update_it.=", `sex`='Admin'";
}

print 'Traded '.lint($sitems[$action][0]).' Credits for '.$action.'!';
	//OTHER
}
mysqli_query($link, "UPDATE `$tbl_credits` SET `credits`=".($cobj->credits-$sitems[$action][0])." WHERE `id`=$cobj->id LIMIT 1") or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__.mysqli_error($link));
mysqli_query($link, "INSERT INTO `$tbl_zlogs` VALUES(NULL,'$row->charname','Had $cobj->credits credits before trading for $action','support','$ip','$current_time')") or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__.mysqli_error($link));
$cobj->credits-=$sitems[$action][0];
}

}}}else{$cobj->credits=0;}}
print '<table width="100%"><tr><th>This game is brought to you by the players that have donated real money.
</th></tr><tr><td>Every $3.00 is equal to 300 credits, you can trade the credits for the items listed below. The more you donate at once the more bonus credits you will get. Verified Paypal accounts only please or the program won\'t accept your transaction.</td></tr><tr><th>Buy some credits with<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business='.$paypal_email.'&undefined_quantity=1&item_name=Donation for credits from '.$row->sex.' '.$row->charname.' on '.$server.'&item_number=Game:Lol1,Server:'.$server.',Charname:'.$row->charname.'&amount=3.00&no_shipping=1&return='.$root_url.'/thanks.php&cancel_return='.$root_url.'/thanks2.php&notify_url= '.$notify_url.'&lc=US" target="_blank">Paypal Transactions</a>.
'.($cobj->credits>=1?'</th></tr><tr><th>You have '.lint($cobj->credits).' credits.':'</th></tr><tr><th>You have no credits.').'.</th></tr></table>

<form method="post" action="support.php?sid='.$sid.'"><table width="100%"><tr><th colspan="4">A list of items that can be traded for your credits</th></tr><tr><td>Description / Amount</td><td>Credits</td><td>Trade</td></tr>
';
foreach($sitems as $key=>$val){

print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'>
<td>';
empty($bg)? $bg=1:$bg='';
if($key == 'Experience' or $key == 'Gold'){
print lint($val[1]*$multier);
}elseif($val[1]>=1){
print lint($val[1]);
}
print ' '.$val[3].'</td><td nowrap>'.lint($val[0]).'</td><td align="center">';
if(preg_match("/^Freeplay/i",$key)){

if($fp >= 100){print 'Enough FP';}elseif($fp <= 100 and $cobj->credits >= $val[0]){
	print '<input type="submit" name="action" value="'.$key.'">';
}else{
	print 'Insufficient credits';
	}

}elseif(preg_match("/Charm$/i",$key)){

if($cobj->credits >= $val[0] and $openslots < 5){
	print '<input type="submit" name="action" value="'.$key.'">';
	}else{
		if($cobj->credits >= $val[0]){
			print 'No open Charm slots.';
		}else{
			print 'Insufficient credits';
		}
		}

}elseif($cobj->credits >= $val[0]){
	print '<input type="submit" name="action" value="'.$key.'">';
}else{
	print 'Insufficient credits';
}
print '</td></tr>';

}

print '</table></form>
<p>
<table cellpadding="1" cellspacing="1" border="1" width="100%"><tr><td>
Verified Paypal accounts only or the program won\'t accept it.<br>
<br>

If you plan to contact me about problem with your account or sending money for credits please include the line below:<br>
<br>
<hr size="1">
Game:Lol1,Server:'.$server.',Charname:'.$row->charname.'
<hr size="1">
<br>
Thank you for your time,<br>
<br>
<b>'.$admin_name.'</b><br>
<a href="https://lordsoflords.com/forums/profile.php?member=SilenT">My contact information</a><br>
</td></tr></table>
';
require_once($game_footer);
?>