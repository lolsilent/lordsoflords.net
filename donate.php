<?php 
require_once('AdMiN/www.main.php');
require_once($inc_mysql);
require_once($inc_functions);
include_once($html_header);
$link=mysqli_connect($db_host,$db_user,$db_password,$db_main) or some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
print '
<form method="post">
<table width="100%" cellpadding=0 cellspacing=1 border=0 align=center>
<tr><th colspan=2>Make a donation to a player in the game.</th></tr>
';
if($ores=mysqli_query($link, "SELECT `clan`,`sex`,`charname`,`race`,`level`,`xp`,`gold`,`timer`,`fp`,`mute`,`jail` FROM `$tbl_members` WHERE `timer`>=($current_time-900) and `sex`!='Admin' and `sex`!='Cop' and `sex`!='Mod' ORDER BY `level` ASC LIMIT 100")){
if(mysqli_num_rows($ores) >= 1){
print '<tr><td width=50%>A online player</td><td><select name="oplayer">';
while ($orow = mysqli_fetch_object ($ores)) {
print '<option value="'.$orow->charname.'">'.$orow->charname.'</option>';
}
mysqli_free_result ($ores);
print '</select></td></tr>';
}}
print '<tr><td>Find a player by his Charname</td><td><input type=text name=player value=""></td></tr>
<tr><th colspan=2><input type=submit name=action value="Give this player a present!"></th></tr>
</table>
</form>

';
if(!empty($_POST['player']) or !empty($_POST['oplayer'])){
if(empty($_POST['player']) and !empty($_POST['oplayer'])){$player=clean_post($_POST['oplayer']);}
if(!empty($_POST['player']) and empty($_POST['oplayer'])){$player=clean_post($_POST['player']);}

if($mres=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `charname`='$player' LIMIT 1")){
if($mrow = mysqli_fetch_object ($mres)){
mysqli_free_result ($mres);
print '<br><br><b><a href="'."https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=paypal@thesilent.com&undefined_quantity=1&item_name=$title : Buy 300 credits for $mrow->sex $mrow->charname&item_number=Game:Lol1,Server:$server,Charname:$mrow->charname&amount=3&no_shipping=1&return=$root_url/thanks.php&cancel_return=$root_url/thanks2.php&notify_url=https://thesilent.com/paypal/index.php&lc=US".'">Proceed to buy credits for '."$mrow->sex $mrow->charname".'</a>
</b><br><br>';
}else{print 'Can\'t find player.';
	}
}
}
print '
Verified Paypal accounts only.<br>
';
mysqli_close($link);
include_once($html_footer);
?>