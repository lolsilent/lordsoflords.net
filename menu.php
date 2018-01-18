<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
if(empty($_SERVER['HTTP_REFERER'])){header("Location: $root_url");}
if(!empty($_GET['sid']) and empty($sid)){$sid=clean_post($_GET['sid']);}
if(!empty($_POST['sid']) and empty($sid)){$sid=clean_post($_POST['sid']);}
if(empty($sid)){header("Location: $root_url/login.php");exit;}
include_once($clean_header);

$alignable = array('left','right','center','top','bottom');

if (!empty($_COOKIE['align'])) {
	$align=clean_post($_COOKIE['align']);
	if (!in_array($align,$alignable)) {$align='left';}
}

if (!empty($_COOKIE['valign'])) {
	$valign=clean_post($_COOKIE['valign']);
	if (!in_array($valign,$alignable)) {$align='top';}
}

if (!empty($_GET['align'])) {
	$align=clean_post($_GET['align']);
	if (in_array($align,$alignable)) {
		setcookie ("align", "$align",$current_time+5000000) or die("$error_message");
	}
}

if (!empty($_GET['valign'])) {
	$valign=clean_post($_GET['valign']);
	if (in_array($valign,$alignable)) {
		setcookie ("valign", "$valign",$current_time+5000000) or die("$error_message");
	}
}

print '<table width=100% height=100% border=1><tr><td align="'.$align.'" valign="'.$valign.'">';
$m='';
if (!empty($_GET['m'])){
$m=clean_post($_GET['m']);
}

$served=1;
if (!empty($_GET['served'])){
$served=clean_post($_GET['served']);
}

if($m < 1 and $m >5){$m='';}


if (empty($m)){
foreach($gamefiles as $file){
if($file == "logout"){
print '<a href="'.$file.'.php?sid='.$sid.'&served='.$served.'" target="_top">'.ucfirst($file).'</a><br>';
}elseif(empty($file)){print '<br>';
}else{
print '<a href="'.$file.'.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main">'.ucfirst($file).'</a><br>';
}
}
print '<a href="forum.php?sid='.$sid.'" target="'.$server.'_main">Forums</a><br><a href="teleport.php?sid='.$sid.'" target="'.$server.'_main">Teleport</a><br><a href="guides.php?sid='.$sid.'" target="'.$server.'_main">Guides</a>
';
}else{
print '
<img src="images/m'.$m.'.jpg" border=0 usemap="#navbar_map">

<map name="navbar_map">
<area shape="rect" coords="0,9,80,24" href="main.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Main">
<area shape="rect" coords="0,24,80,39" href="paper.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Paper">
<area shape="rect" coords="0,39,80,53" href="duels.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Duels">
<area shape="rect" coords="0,53,80,67" href="graves.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Graves">
<area shape="rect" coords="0,67,80,82" href="steals.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Steals">
<area shape="rect" coords="0,82,80,97" href="matches.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Matches">

<area shape="rect" coords="0,110,80,125" href="fight.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Fight">
<area shape="rect" coords="0,125,80,140" href="world.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="World">
<area shape="rect" coords="0,140,80,155" href="challenge.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Challenge">
<area shape="rect" coords="0,155,80,168" href="schedule.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Schedule">
<area shape="rect" coords="0,168,80,183" href="tourney.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Tourney">

<area shape="rect" coords="0,197,80,211" href="shop.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Shop">
<area shape="rect" coords="0,211,80,225" href="stats.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Stats">
<area shape="rect" coords="0,225,80,240" href="inventory.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Inventory">
<area shape="rect" coords="0,240,80,255" href="charms.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Charms">
<area shape="rect" coords="0,255,80,268" href="market.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Market">

<area shape="rect" coords="0,283,80,297" href="clan.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Clan">
<area shape="rect" coords="0,297,80,312" href="transfer.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Transfer">
<area shape="rect" coords="0,312,80,327" href="messages.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Messages">
<area shape="rect" coords="0,327,80,342" href="stash.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Stash">
<area shape="rect" coords="0,342,80,355" href="steal.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Steal">

<area shape="rect" coords="0,370,80,384" href="town.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Town">
<area shape="rect" coords="0,384,80,399" href="nobility.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Nobility">
<area shape="rect" coords="0,399,80,414" href="support.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Support">
<area shape="rect" coords="0,414,80,427" href="save.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Save">
<area shape="rect" coords="0,427,80,444" href="logout.php?sid='.$sid.'&served='.$served.'" target="_top" title="Logout">

<area shape="rect" coords="0,456,80,471" href="ladder.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Ladder">
<area shape="rect" coords="0,471,80,485" href="clans.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Clans">
<area shape="rect" coords="0,485,80,499" href="forum.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Forums">
<area shape="rect" coords="0,499,80,514" href="teleport.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Teleport">
<area shape="rect" coords="0,514,80,530" href="guides.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="Guides">
';
if ($m == 6){
print '<area shape="rect" coords="35,545,45,550" href="merge.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="?">';
}elseif ($m == 3){
print '<area shape="rect" coords="35,545,45,550" href="hospital.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main" title="?">';
} else {
print '<area shape="rect" coords="35,545,45,550" href="?m=6&sid='.$sid.'&served='.$served.'" title="?">';
}
print '
</map>';


}
print '<br><a href="?sid='.$sid.'&served='.$served.'">.</a><a href="?m=1&sid='.$sid.'&served='.$served.'">.</a><a href="?m=2&sid='.$sid.'&served='.$served.'">.</a><a href="?m=3&sid='.$sid.'&served='.$served.'">.</a><a href="?m=4&sid='.$sid.'&served='.$served.'">.</a><a href="?m=5&sid='.$sid.'&served='.$served.'">.</a>
<br>
<a href="layout.php?sid='.$sid.'&served='.$served.'" target="'.$server.'_main">Layout</a><br>
<a href="menu.php?sid='.$sid.'&served='.$served.'&align=left" title="Align left">.</a>
<a href="menu.php?sid='.$sid.'&served='.$served.'&align=center" title="Align center">.</a>
<a href="menu.php?sid='.$sid.'&served='.$served.'&align=right" title="Align right">.</a>
<br>
<a href="menu.php?sid='.$sid.'&served='.$served.'&valign=top" title="Vertical Align top">.</a><br>
<a href="menu.php?sid='.$sid.'&served='.$served.'&valign=center" title="Vertical Align center">.</a><br>
<a href="menu.php?sid='.$sid.'&served='.$served.'&valign=bottom" title="Vertical Align bottom">.</a><br>

</td></tr></table>';
include_once($clean_footer);
?>