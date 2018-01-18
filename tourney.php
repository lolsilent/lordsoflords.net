<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($inc_races);
require_once($inc_battle);
require_once($inc_tourney);
require_once($game_header);

//tbl_save for losers
$matches=5;
$tourney_clans=5;//5
$min_members=10;//10
$min_round=600;//600
$tour_players=5;//5 players in one round
$win_clan=array();

if($tpbres=mysqli_query($link, "SELECT * FROM `$tbl_tourprice` WHERE `server_id`='$row->server_id' and `id` ORDER BY `id` DESC LIMIT 1")) {
if($tpobj=mysqli_fetch_object($tpbres)){mysqli_free_result($tpbres);

print '<table width="100%"><tr><th colspan="8">'.$title.' Tournaments</th></tr>';
if($winre=mysqli_query($link, "SELECT * FROM `$tbl_tourprice` WHERE `server_id`='$row->server_id' and `id` ORDER BY `id` DESC LIMIT 5")){
print '<tr><th colspan="8">Celebrating';
$i=0;while($wobj=mysqli_fetch_object($winre)){
if($i<=3){
if(!empty($wobj->clan)){
	$win_clan[]=$wobj->clan;
print ' [<a href="clans.php?clan='.$wobj->clan.'" title="Won '.lint($wobj->xp).' XP and $'.lint($wobj->gold).'!">'.$wobj->clan.']</a>';
}
}else{mysqli_query($link, "DELETE FROM `$tbl_tourprice` WHERE `server_id`='$row->server_id' and `id`=$wobj->id LIMIT 1");}
$i++;
}mysqli_free_result($winre);
print '</th></tr>';
}
print '<tr><th colspan="8">Price pot ';
print lint($tpobj->xp).' XP and $'.lint($tpobj->gold);
print '</th></tr>';

if($tresult=mysqli_query($link, "SELECT * FROM `$tbl_clans` WHERE `tourney`='1' ORDER BY `points` DESC LIMIT 50")){
if(mysqli_num_rows($tresult) >= $tourney_clans) {
//{clans list}
print '<tr><td align="center">#</td><td>Clan</td><td>Won <font size="1">(3 pts)</font></td><td>Tied <font size="1">(1 pts)</font></td><td>Lost</td><td>Points</td></tr>';
$num=1;$clans=array();
$sw_points_a=0;$sw_clan_a='';
while($gobj=mysqli_fetch_object($tresult)){
	if(!in_array($gobj->clan,$win_clan)){
if ($gobj->points > $sw_points_a and $gobj->points >= 1){$sw_points_a = $gobj->points;$sw_clan_a=$gobj->clan;}
if($mtresult=mysqli_query($link, "SELECT `id` FROM `$tbl_members` WHERE `clan`='$gobj->clan' and `xp`>=`level` LIMIT 100")){$total_members=mysqli_num_rows($mtresult);mysqli_free_result($mtresult);
}else{$total_members=0;}

if($total_members<=0){mysqli_query($link, "DELETE FROM `$tbl_clans` WHERE `id`=$gobj->id LIMIT 1");}else{
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td align="center">'.$num.'</td><td>['.$gobj->clan.']';
if($total_members>=$min_members){print '<font size=-2> ('.$total_members.')</font>';
if(!in_array($gobj->clan,$win_clan) and $sw_clan_a !== $gobj->clan){$clans[]=$gobj->clan;}
}else{print '<font size=-2>Need '.$min_members.' members, kicked out of the tournament!</font>';
	mysqli_query($link, "UPDATE `$tbl_clans` SET `tourney`='0' WHERE `clan`='$gobj->clan'");}
	print '</td><td>'.lint($gobj->won).'</td><td>'.lint($gobj->tied).'</td><td>'.lint($gobj->lost).'</td><td>'.lint($gobj->points).'</td></tr>';
}
$num++;
	}
}mysqli_free_result($tresult);
print '</table>';
//{clans list}


//{prepare for tourney}
if($stresult=mysqli_query($link, "SELECT * FROM `$tbl_tourney` WHERE `server_id`='$row->server_id' and `id` ORDER BY `id` ASC LIMIT $matches")){
if($total_mres=mysqli_query($link, "SELECT id FROM `$tbl_tourney` WHERE `server_id`='$row->server_id' and `id` LIMIT 1000")){
$total_matches=mysqli_num_rows($total_mres);mysqli_free_result($total_mres);
}else{$total_matches=mysqli_num_rows($stresult);}
	if($total_matches <= 0){
	//print_r($clans);
	//$clans minus the WINNER!!
if(count($clans) >= $tourney_clans){

$clans=array_unique($clans);$total_clans=count($clans);
shuffle($clans);$tourplan=array();
for($i=0;$i < $total_clans;$i++){for($j=0;$j < $total_clans;$j++){if($i !== $j){
	//print "$clans[$i] - $clans[$j] | $i - $j<br>";
array_push($tourplan,"'$clans[$i]','$clans[$j]'");
}}}

//{execute multi query}
shuffle($tourplan);$round_timer=0;
foreach($tourplan as $val){$round_timer+=$min_round;
	//print $val.' '.$round_timer.'<br>';
mysqli_query($link, "INSERT INTO `$tbl_tourney` VALUES(NULL,'$row->server_id',$val,'".($current_time+$round_timer)."')") or die(mysqli_error($link));
}


mysqli_query($link, "INSERT INTO `$tbl_tourprice` VALUES(NULL,'$row->server_id','','100000','100000','$current_time')");
/*_______________-=TheSilenT.CoM=-_________________*/
$one_percent_xp = $tpobj->xp/100;$one_percent_gold = $tpobj->gold/100;
$price_xp=array();$price_gold=array();
$price_xp[]= $one_percent_xp*75;$price_gold[] = $one_percent_gold*75;
$price_xp[]= $one_percent_xp*15;$price_gold[] = $one_percent_gold*15;
$price_xp[]= $one_percent_xp*10;$price_gold[] = $one_percent_gold*10;

mysqli_query($link, "UPDATE `$tbl_tourprice` SET `clan`='$sw_clan_a' WHERE `id`='$tpobj->id'") or print(mysqli_error($link).'55');
$swinners='';
if($winresult=mysqli_query($link, "SELECT * FROM `$tbl_clans` WHERE `tourney`='1' ORDER BY `points` DESC LIMIT 3")){
$i=0;while($wrow=mysqli_fetch_object($winresult)){

$swinners .= ', ['.$wrow->clan.'] wins '.lint($price_xp[$i]).' XP and $'.lint($price_gold[$i]).' gold';
if($swinr=mysqli_query($link, "SELECT * FROM `$tbl_members` WHERE `server_id`='$row->server_id' and `clan`='$wrow->clan' and `xp`>=`level` LIMIT 100")){
$swinmem=mysqli_num_rows($swinr);
while($swobj=mysqli_fetch_object($swinr)){ //print $swinmem.' '.$swobj->charname.' '.$swobj->xp.'<br>';
mysqli_query($link, "UPDATE `$tbl_members` SET `xp`=`xp`+'".($price_xp[$i]/$swinmem)."',`gold`=`gold`+'".($price_gold[$i]/$swinmem)."' WHERE `server_id`='$row->server_id' and `id`='$swobj->id' LIMIT 1") or print(mysqli_error($link).'55');
}
mysqli_free_result($swinr);
}

$i++;
}mysqli_free_result($winresult);
}

/*_______________-=TheSilenT.CoM=-_________________*/
mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','4','A new season of tournaments has been started $swinners','$current_time')");
mysqli_query($link, "UPDATE `$tbl_clans` SET `won`='0',`lost`='0',`tied`='0',`points`='0' WHERE `tourney`='1'");

print 'Organizing upcoming events!';
}else{
	print 'Some clans have not enough members or not enough clans left to play in the tournament!';
	}
	}else{
	print '<table><tr><th colspan="5">Upcoming matches <font size=-1><sup>Total '.$total_matches.'</sup></font></th></tr>';
	$i=0;while($stobj=mysqli_fetch_object($stresult)){$i++;
print '<tr'.(empty($bg)?' bgcolor="'.$colth.'"':'').'><td>'.$i.'</td><td>['.$stobj->clana.']</td><td> versus </td><td>['.$stobj->clanb.']</td><td>';
if($stobj->timer >= $current_time){
print ' in '.dater($stobj->timer);
}else{
//{BEGIN CLANWAR}

/*_______________-=TheSilenT.CoM=-_________________*/

$clana_bs=array('level'=>1,'life'=>1,'min_wd'=>1,'max_wd'=>1,'min_spell'=>1,'max_spell'=>1,'min_heal'=>1,'max_heal'=>1,'min_shield'=>1,'max_shield'=>1,'min_defense'=>1,'max_defense'=>1,'min_ar'=>1,'max_ar'=>1,'min_mr'=>1,'max_mr'=>1);
$clanb_bs=array('level'=>1,'life'=>1,'min_wd'=>1,'max_wd'=>1,'min_spell'=>1,'max_spell'=>1,'min_heal'=>1,'max_heal'=>1,'min_shield'=>1,'max_shield'=>1,'min_defense'=>1,'max_defense'=>1,'min_ar'=>1,'max_ar'=>1,'min_mr'=>1,'max_mr'=>1);
$clana_xp=0;
$clanb_xp=0;
$clana_gold=0;
$clanb_gold=0;
$clana_playing='';$clanb_playing='';
/*_______________-=TheSilenT.CoM=-_________________*/

if($a_res=mysqli_query($link, "SELECT id,sex,charname, race, xp, gold, level, life, str, dex, agi, intel, conc, cont, weapon, spell, heal, helm, shield, amulet, ring, armor, belt, pants, hand, feet, twin FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `twin`= '0' and `clan`='$stobj->clana' and `xp`>=`level` and `gold`>=`level`) ORDER BY `level` DESC LIMIT $tour_players")){
$a_members=mysqli_num_rows($a_res);
if($a_members >= $tour_players){
	mysqli_query($link, "UPDATE `$tbl_members` SET `twin`=0 WHERE `server_id`='$row->server_id' and `clan`='$stobj->clana' LIMIT 100") or print(mysqli_error($link).'7');
while($a_obj=mysqli_fetch_object($a_res)){$clana_playing .=$a_obj->sex.' '.$a_obj->charname.'
';
if($a_obj->twin <= 0){$clana_xp += $a_obj->xp;$clana_gold += $a_obj->gold;
	mysqli_query($link, "UPDATE `$tbl_members` SET `twin`=1 WHERE `server_id`='$row->server_id' and `id`='$a_obj->id' LIMIT 1") or print(mysqli_error($link).'7');
$amss=battlestats($a_obj);
$clana_bs['level']+=$a_obj->level;$clana_bs['life']+=$a_obj->life;
$clana_bs['min_wd']+=$amss->min_wd;$clana_bs['max_wd']+=$amss->max_wd;
$clana_bs['min_spell']+=$amss->min_spell;$clana_bs['max_spell']+=$amss->max_spell;
$clana_bs['min_heal']+=$amss->min_heal;$clana_bs['max_heal']+=$amss->max_heal;
$clana_bs['min_shield']+=$amss->min_shield;$clana_bs['max_shield']+=$amss->max_shield;
$clana_bs['min_defense']+=$amss->min_defense;$clana_bs['max_defense']+=$amss->max_defense;
$clana_bs['min_ar']+=$amss->min_ar;$clana_bs['max_ar']+=$amss->max_ar;
$clana_bs['min_mr']+=$amss->min_mr;$clana_bs['max_mr']+=$amss->max_mr;
}
}mysqli_free_result($a_res);}}

/*_______________-=TheSilenT.CoM=-_________________*/

if($b_res=mysqli_query($link, "SELECT id,sex,charname, race, xp, gold, level, life, str, dex, agi, intel, conc, cont, weapon, spell, heal, helm, shield, amulet, ring, armor, belt, pants, hand, feet, twin FROM `$tbl_members` WHERE (`server_id`='$row->server_id' and `twin`= '0' and `clan`='$stobj->clanb' and `xp`>=`level` and `gold`>=`level`) ORDER BY `level` DESC LIMIT $tour_players")){
$b_members=mysqli_num_rows($b_res);
if($b_members >= $tour_players){
	mysqli_query($link, "UPDATE `$tbl_members` SET `twin`=0 WHERE `server_id`='$row->server_id' and `clan`='$stobj->clanb' LIMIT 100") or print(mysqli_error($link).'7');
while($b_obj=mysqli_fetch_object($b_res)){$clanb_playing .=$b_obj->sex.' '.$b_obj->charname.'
';
if($b_obj->twin <= 0){$clanb_xp += $b_obj->xp;$clanb_gold += $b_obj->gold;
	mysqli_query($link, "UPDATE `$tbl_members` SET `twin`=1 WHERE `server_id`='$row->server_id' and `id`='$b_obj->id' LIMIT 1") or print(mysqli_error($link).'7');
$bmss=battlestats($b_obj);
$clanb_bs['level']+=$b_obj->level;$clanb_bs['life']+=$b_obj->life;
$clanb_bs['min_wd']+=$bmss->min_wd;$clanb_bs['max_wd']+=$bmss->max_wd;
$clanb_bs['min_spell']+=$bmss->min_spell;$clanb_bs['max_spell']+=$bmss->max_spell;
$clanb_bs['min_heal']+=$bmss->min_heal;$clanb_bs['max_heal']+=$bmss->max_heal;
$clanb_bs['min_shield']+=$bmss->min_shield;$clanb_bs['max_shield']+=$bmss->max_shield;
$clanb_bs['min_defense']+=$bmss->min_defense;$clanb_bs['max_defense']+=$bmss->max_defense;
$clanb_bs['min_ar']+=$bmss->min_ar;$clanb_bs['max_ar']+=$bmss->max_ar;
$clanb_bs['min_mr']+=$bmss->min_mr;$clanb_bs['max_mr']+=$bmss->max_mr;
}
}mysqli_free_result($b_res);}}

/*_______________-=TheSilenT.CoM=-_________________*/
$clana_life=$clana_bs['life'];$clanb_life=$clanb_bs['life'];

$battles=0;while($clana_bs['life']>=1 and $clanb_bs['life']>=1 and $battles<5){$battles++;
if($clana_bs['max_wd']>=$clana_bs['max_spell']){
$clanb_bs['life']=tweapon($clana_bs,$clanb_bs);if($clana_bs['life']<=0 or $clanb_bs['life']<=0){break;}
$clana_bs['life']=tweapon($clanb_bs,$clana_bs);if($clana_bs['life']<=0 or $clanb_bs['life']<=0){break;}
$clanb_bs['life']=tmagic($clana_bs,$clanb_bs);if($clana_bs['life']<=0 or $clanb_bs['life']<=0){break;}
$clana_bs['life']=tmagic($clanb_bs,$clana_bs);if($clana_bs['life']<=0 or $clanb_bs['life']<=0){break;}
}else{
$clanb_bs['life']=tmagic($clana_bs,$clanb_bs);if($clana_bs['life']<=0 or $clanb_bs['life']<=0){break;}
$clana_bs['life']=tmagic($clanb_bs,$clana_bs);if($clana_bs['life']<=0 or $clanb_bs['life']<=0){break;}
$clanb_bs['life']=tweapon($clana_bs,$clanb_bs);if($clana_bs['life']<=0 or $clanb_bs['life']<=0){break;}
$clana_bs['life']=tweapon($clanb_bs,$clana_bs);if($clana_bs['life']<=0 or $clanb_bs['life']<=0){break;}
}
$clanb_bs['life']=theal($clana_bs,$clanb_bs);
$clana_bs['life']=theal($clanb_bs,$clana_bs);
}//while

/*_______________-=TheSilenT.CoM=-_________________*/

$win_xp=0;$win_gold=0;

if($clana_bs['life'] > $clanb_bs['life'] and $clanb_bs['life'] <= 0){

$outcome = 'won from ';

//mysqli_query($link, "UPDATE `$tbl_members` SET `xp`=`xp`*1.01,`gold`=`gold`*1.01 WHERE `server_id`='$row->server_id' and `clan`='$stobj->clana' LIMIT 100") or print(mysqli_error($link).'3');
mysqli_query($link, "UPDATE `$tbl_members` SET `xp`=`xp`/1.01,`gold`=`gold`/1.01 WHERE `server_id`='$row->server_id' and `clan`='$stobj->clanb' LIMIT 100") or print(mysqli_error($link).'4');
/*_______________-=TheSilenT.CoM=-_________________*/
mysqli_query($link, "UPDATE `$tbl_tourprice` SET `xp`=`xp`+'".($clanb_xp/100)."',`gold`=`gold`+'".($clanb_gold/100)."' WHERE `id`='$tpobj->id'") or print(mysqli_error($link).'55');
/*_______________-=TheSilenT.CoM=-_________________*/
mysqli_query($link, "UPDATE `$tbl_clans` SET `won`=`won`+1,`points`=`points`+3 WHERE `clan`='$stobj->clana'") or print(mysqli_error($link).'5');
mysqli_query($link, "UPDATE `$tbl_clans` SET `lost`=`lost`+1 WHERE `clan`='$stobj->clanb'") or print(mysqli_error($link).'6');

if($lsr=mysqli_query($link, "SELECT `charname` FROM `$tbl_members` WHERE `server_id`='$row->server_id' and `clan`='$stobj->clanb' and `xp`>=`level`LIMIT 100")){
while($lsow=mysqli_fetch_object($lsr)){
mysqli_query($link, "DELETE FROM `$tbl_save` WHERE `charname`='$lsow->charname' LIMIT 10");
}mysqli_free_result($lsr);
}

}elseif($clana_bs['life'] < $clanb_bs['life'] and $clana_bs['life'] <= 0){

$outcome = 'lost to ';

//mysqli_query($link, "UPDATE `$tbl_members` SET `xp`=`xp`*1.01,`gold`=`*1.01 WHERE `server_id`='$row->server_id' and `clan`='$stobj->clanb' LIMIT 100") or print(mysqli_error($link).'9');
mysqli_query($link, "UPDATE `$tbl_members` SET `xp`=`xp`/1.01,`gold`=`gold`/1.01 WHERE `server_id`='$row->server_id' and `clan`='$stobj->clana' LIMIT 100") or print(mysqli_error($link).'10');

if($lsr=mysqli_query($link, "SELECT `charname` FROM `$tbl_members` WHERE `server_id`='$row->server_id' and `clan`='$stobj->clana' and `xp`>=`level`LIMIT 100")){
while($lsow=mysqli_fetch_object($lsr)){
mysqli_query($link, "DELETE FROM `$tbl_save` WHERE `charname`='$lsow->charname' LIMIT 10");
}mysqli_free_result($lsr);
}
/*_______________-=TheSilenT.CoM=-_________________*/
mysqli_query($link, "UPDATE `$tbl_tourprice` SET `xp`=`xp`+'".($clana_xp/100)."',`gold`=`gold`+'".($clana_gold/100)."' WHERE `id`='$tpobj->id'") or print(mysqli_error($link).'55');
/*_______________-=TheSilenT.CoM=-_________________*/
mysqli_query($link, "UPDATE `$tbl_clans` SET `won`=`won`+1,`points`=`points`+3 WHERE `clan`='$stobj->clanb'") or print(mysqli_error($link).'11');
mysqli_query($link, "UPDATE `$tbl_clans` SET `lost`=`lost`+1 WHERE `clan`='$stobj->clana'") or print(mysqli_error($link).'12');

}else{

$outcome = 'ties versus';
mysqli_query($link, "UPDATE `$tbl_clans` SET `tied`=`tied`+1,`points`=`points`+1 WHERE `clan`='$stobj->clanb'");
mysqli_query($link, "UPDATE `$tbl_clans` SET `tied`=`tied`+1,`points`=`points`+1 WHERE `clan`='$stobj->clana'");
}

/*_______________-=TheSilenT.CoM=-_________________*/

$clana_bs['life']=$clana_life;$clanb_bs['life']=$clanb_life;
$outcome = 'Clan [<a href="clans.php?clan='.$stobj->clana.'" title="Total clan power:
'.lint(array_sum($clana_bs)).'

Players:
'.$clana_playing.'">'.$stobj->clana.'</a>] ('.lint($a_members).') '.$outcome.' Clan [<a href="clans.php?clan='.$stobj->clanb.'" title="Total clan power:
'.lint(array_sum($clanb_bs)).'

Players:
'.$clanb_playing.'">'.$stobj->clanb.'</a>] ('.lint($b_members).') in '.$battles.' round'.($battles>1?'s':'').'';

mysqli_query($link, "INSERT INTO `$tbl_papers` VALUES(NULL,'$row->server_id','4','$outcome','$current_time')") or print(mysqli_error($link).'13');
mysqli_query($link, "DELETE FROM `$tbl_tourney` WHERE `server_id`='$row->server_id' and `id`='$stobj->id'") or print(mysqli_error($link).'14');
print $outcome;
//{BEGIN CLANWAR}
}
print '</td></tr>';
	}
	mysqli_free_result($stresult);

	}
}
//{prepare for tourney}
}else{
	print '<tr><th>A minimum of '.$tourney_clans.' clans with '.$min_members.' members are needed to start the tournament!</th></tr>';}
print '</table>';
}

}else{mysqli_query($link, "INSERT INTO `$tbl_tourprice` VALUES(NULL,'$row->server_id','','100000','100000','$current_time')");}}

print '<hr>
Prices<br>
#1 wins 75%<br>
#2 wins 15%<br>
#3 wins 10%<br>
All prices will be shared for the whole clan.<br>
A clan that loses a match will lose 1% xp and gold to the price pot.<br>
To keep the tourney running non stop a totall of 9 clans with 10 members is needed!<br>
<hr>';
require_once($game_footer);
?>