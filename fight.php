<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($inc_races);
require_once($inc_monsters);
require_once($inc_battle);
require_once($game_header);

$next_level=next_level();

if($row->xp>=$next_level){if($fp<=1) {include_once('stats.php');exit;}else{print '<br>Freeplay levelup later is active.<br>';}}

if(!empty($_GET['difficulty'])){$difficulty=clean_post($_GET['difficulty']);if($difficulty<1){$difficulty=1;}}else{$difficulty=1;}
	
if(!empty($_POST['difficulty'])){$difficulty=clean_post($_POST['difficulty']);if($difficulty<1){$difficulty=1;}}else{if (!isset($_GET['difficulty'])){$difficulty=1;}}

if(!empty($_GET['monster'])){
$monster=clean_post($_GET['monster']);
if(!in_array($monster, $monsters_array)){$monster=$monsters_array[0];}
}else{$monster=$monsters_array[0];}

if(!empty($_POST['monster'])){
$monster=clean_post($_POST['monster']);
if(!in_array($monster, $monsters_array)){$monster=$monsters_array[0];}
}else{if (!isset($_GET['monster'])){$monster=$monsters_array[0];}}

$mon = new stdClass;
$mon->level = array_search($monster, $monsters_array);$mon->level++;
$mon->xp=(96+((1+$mon->level)*(1+$mon->level))*$mon->level)*($difficulty);
$mon->gold=30+($mon->xp/5);
$mon->life=($mon->xp/4)+475*($difficulty);
$mon->sex='';
$mon->charname=ucwords($monster);

$multiplier=strlen($mon->charname);
$base_min=$multiplier+$mon->xp/15.55;
$base_max=$multiplier+$mon->xp/2.55;

$flex[0]='3'.substr(($multiplier*11),-2);$flex[0]/=100;
$flex[1]='2'.substr(($multiplier*22),0,2);$flex[1]/=100;
$flex[2]='1'.substr(($multiplier*33),-2);$flex[2]/=100;
$flex[3]='3'.substr(($multiplier*44),0,2);$flex[3]/=100;
$flex[4]='2'.substr(($multiplier*55),-2);$flex[4]/=100;
$flex[5]='1'.substr(($multiplier*66),0,2);$flex[5]/=100;
$flex[6]='5'.substr(($multiplier*77),-2);$flex[6]/=100;

$mon->min_wd=$base_min*$flex[0];
$mon->max_wd=$base_max*$flex[0];
$mon->min_defense=$base_min*$flex[1];
$mon->max_defense=$base_max*$flex[1];
$mon->min_ar=$base_min*$flex[2];
$mon->max_ar=$base_max*$flex[2];

$mon->min_spell=$base_min*$flex[3];
$mon->max_spell=$base_max*$flex[3];
$mon->min_shield=$base_min*$flex[4];
$mon->max_shield=$base_max*$flex[4];
$mon->min_mr=$base_min*$flex[5];
$mon->max_mr=$base_max*$flex[5];

$mon->min_heal=$base_min*$flex[6];
$mon->max_heal=$base_max*$flex[6];

$row=battlestats($row);

print '
<table width="100%">
<tr><th colspan="5">'.$row->sex.' '.$row->charname.' vs '.ucwords($mon->charname).'</th></tr>
<tr>
<td><br>Weapon damage<br>Attack spell<br>Heal spell<br>Magic shield<br>Defense<br>Attack Rating<br>Magic Rating</td>
<td>min<br>'.lint($row->min_wd).'<br>'.lint($row->min_spell).'<br>'.lint($row->min_heal).'<br>'.lint($row->min_shield).'<br>'.lint($row->min_defense).'<br>'.lint($row->min_ar).'<br>'.lint($row->min_mr).'</td>
<td>max<br>'.lint($row->max_wd).'<br>'.lint($row->max_spell).'<br>'.lint($row->max_heal).'<br>'.lint($row->max_shield).'<br>'.lint($row->max_defense).'<br>'.lint($row->max_ar).'<br>'.lint($row->max_mr).'</td>
<td>min<br>'.lint($mon->min_wd).'<br>'.lint($mon->min_spell).'<br>'.lint($mon->min_heal).'<br>'.lint($mon->min_shield).'<br>'.lint($mon->min_defense).'<br>'.lint($mon->min_ar).'<br>'.lint($mon->min_mr).'</td>
<td>max<br>'.lint($mon->max_wd).'<br>'.lint($mon->max_spell).'<br>'.lint($mon->max_heal).'<br>'.lint($mon->max_shield).'<br>'.lint($mon->max_defense).'<br>'.lint($mon->max_ar).'<br>'.lint($mon->max_mr).'</td>
</tr></table>

<table width="100%"><tr><th>'.ucwords($mon->charname).'</th><th>Level '.lint($mon->level).'</th><th>Life '.lint($mon->life).'</th><th>'.lint($mon->xp).' XP</th><th>$'.lint($mon->gold).'</th></tr></table>
';
if(!empty($_POST['action']) OR !empty($_GET['sid'])){
$battles=0;while($row->life>=1 and $mon->life>=1 and $battles<100){
$battles++;print "<b>Round $battles</b><br>";
if($row->max_wd>=$row->max_spell){
$mon->life=weapon($row,$mon);if($row->life<=0 or $mon->life<=0){break;}
$row->life=weapon($mon,$row);if($row->life<=0 or $mon->life<=0){break;}
$mon->life=magic($row,$mon);if($row->life<=0 or $mon->life<=0){break;}
$row->life=magic($mon,$row);if($row->life<=0 or $mon->life<=0){break;}
}else{
$mon->life=magic($row,$mon);if($row->life<=0 or $mon->life<=0){break;}
$row->life=magic($mon,$row);if($row->life<=0 or $mon->life<=0){break;}
$mon->life=weapon($row,$mon);if($row->life<=0 or $mon->life<=0){break;}
$row->life=weapon($mon,$row);if($row->life<=0 or $mon->life<=0){break;}
}
$mon->life=heal($row,$mon);
$row->life=heal($mon,$row);
}//while

if($mon->life<=0){
$mon->xp/=rand(10,100)/10;
$mon->gold/=rand(10,100)/10;

if($fp>=1){
$fbonus=rand(100,$fp_bonus_max);
$mon->xp=($mon->xp/100)*(100+$fbonus);
$mon->gold=($mon->gold/100)*(100+$fbonus);
print '<br>Your freeplay bonus was <b>'.lint($fbonus).'% / '.lint($fp_bonus_max).'%</b>!';
$killing_spree_max *= 3;
}

if($mon->xp<=0){$mon->xp=1;}else{$mon->xp=round($mon->xp);}
if($mon->gold<=0){$mon->gold=1;}else{$mon->gold=round($mon->gold);}


//KILLING SPREE
$spree=rand(1,$killing_spree_max);
$mon->xp*=$spree;$mon->gold*=$spree;
//KILLING SPREE

print '<br>You have slain '.'<b>'.$spree.' </b>'.ucwords($mon->charname).'.<br>You gain '.lint($mon->xp).' xp and '.lint($mon->gold).' gold.';

print '<br><b>'.(($fp>=1)?'FP ':'').'Killing spree of '.$spree.' / '.$killing_spree_max.' !</b><br>';


$tbl_pets='lol_pets';
if($petres=mysqli_query($link, "SELECT * FROM `$tbl_pets` WHERE `charname`='$row->charname' ORDER BY `id` ASC LIMIT 1")){
if(mysqli_num_rows($petres) >= 1){
if($petobj=mysqli_fetch_object($petres)){
mysqli_free_result($petres);
if($petobj->hunger >= 3 or $petobj->mood >= 3){
	$petgain=rand(500,1000);
mysqli_query($link, "UPDATE `$tbl_pets` SET `xp`=`xp`+(1+($mon->xp/$petgain)) WHERE `charname`='$row->charname' LIMIT 1");
echo '<br>Pet gains '.number_format((1+($mon->xp/$petgain))).' XP.';
}
}
}
}


$row->xp=($row->xp+$mon->xp);$row->gold=($row->gold+$mon->gold);

$update_it.=", `xp`=$row->xp, `gold`=$row->gold";

if(!empty($row->friend)){myfriend($mon->xp,$mon->gold,$row->friend);}

mysqli_query($link, "UPDATE `$tbl_history` SET `kills`=`kills`+1 WHERE `charname`='$row->charname' LIMIT 1");

}elseif($row->life<=0){

$row->xp/=1.01;$row->gold/=1.01;
$row->xp=round($row->xp);
$row->gold=round($row->gold);
if($row->xp>=1){$update_it.=", `xp`=$row->xp";}
if($row->gold>=1){$update_it.=", `gold`=$row->gold";}

mysqli_query($link, "UPDATE `$tbl_history` SET `deads`=`deads`+1 WHERE `charname`='$row->charname' LIMIT 1");

print 'You have been slain by '.ucwords($mon->charname).'. You lose '.(($row->xp/100)>=0?lint($row->xp/100):'').' xp and '.(($row->gold/100)>=0?lint($row->gold/100):'0').' gold.';

mysqli_query($link, "INSERT INTO `$tbl_zlogs` VALUES(NULL,'$row->charname','XP LOSS $row->xp','fight','$ip','$current_time')");

}else{
print 'The battle tied.';
}

mysqli_query($link, "INSERT INTO `$tbl_index` VALUES(NULL,'".date('m d Y')."',1,$current_time) ON DUPLICATE KEY UPDATE `fights`=`fights`+1");

}//spy
print '<br>You need <b>'.(($next_level-$row->xp)<0?'0':lint($next_level-$row->xp)).'</b> XP for the next level.';

if(empty($_POST['action']) and empty($_POST['spy'])){
print '
<script type="text/javASCript">
<!--
window.parent.frames[\'\'.$server.\'_fcontrol\'].location.replace(\'fight_control.php?sid='.$row->sid.'\');
-->
</script>
<noscript>
<a href="fight_control.php?sid='.$row->sid.'" target="'.$server.'_fcontrol">Click here to open fight controls!</a>
</noscript>
';
}
if($fp>=1 AND !empty($difficulty) AND !empty($monster)){
print '<br>Freeplay auto fight is on!</br>';
print '<meta https-equiv="REFRESH" content="1 ; url='.$_SERVER['PHP_SELF'].'?sid='.$sid.'&difficulty='.$difficulty.'&monster='.$monster.'" target="'.$server.'_main">';
}
require_once($game_footer);
?>