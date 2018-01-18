<?php 
#!/usr/local/bin/php

function tweapon($row,$mon){
$row['max_ar']/=(rand(100,255)/100);
$mon['max_ar']/=(rand(100,255)/100);

if($row['max_ar']<$row['min_ar']){$row['max_ar']=$row['min_ar'];}
if($mon['max_ar']<$mon['min_ar']){$mon['max_ar']=$mon['min_ar'];}

if($row['max_ar']>=$mon['max_ar']){
$row['max_wd']/=(rand(100,255)/100);
$mon['max_defense']/=(rand(100,255)/100);
if($row['max_wd']<$row['min_wd']){$row['max_wd']=$row['min_wd'];}
if($mon['max_defense']<$mon['min_defense']){$mon['max_defense']=$mon['min_defense'];}

if($row['max_wd']>=0){
$mon['life']-=($row['max_wd']-$mon['max_defense']);
}
}
return $mon['life'];
}
//weapon

//magic
function tmagic($row,$mon){
$row['max_mr']/=(rand(100,255)/100);
$mon['max_mr']/=(rand(100,255)/100);

if($row['max_mr']<$row['min_mr']){$row['max_mr']=$row['min_mr'];}
if($mon['max_mr']<$mon['min_mr']){$mon['max_mr']=$mon['min_mr'];}

if($row['max_mr']>=$mon['max_mr']){
$row['max_spell']/=(rand(100,255)/100);
$mon['max_shield']/=(rand(100,255)/100);
if($row['max_spell']<$row['min_spell']){$row['max_spell']=$row['min_spell'];}
if($mon['max_shield']<$mon['min_shield']){$mon['max_shield']=$mon['min_shield'];}

if($row['max_spell']>=0){
$mon['life']-=($row['max_spell']-$mon['max_shield']);
}
}
return $mon['life'];
}
//magic

//heal
function theal($row,$mon){
$row['max_mr']/=(rand(100,255)/100);
$mon['max_mr']/=(rand(100,255)/100);

if($row['max_mr']<$row['min_mr']){$row['max_mr']=$row['min_mr'];}
if($mon['max_mr']<$mon['min_mr']){$mon['max_mr']=$mon['min_mr'];}

if($row['max_mr']>=$mon['max_mr']){
$row['max_heal']/=(rand(100,255)/100);
$mon['max_shield']/=(rand(100,255)/100);
if($row['max_heal']<$row['min_heal']){$row['max_heal']=$row['min_heal'];}
if($mon['max_shield']<$mon['min_shield']){$mon['max_shield']=$mon['min_shield'];}

if($row['max_heal']>=0){
$mon['life']-=($row['max_heal']-$mon['max_shield']);
}
}
return $mon['life'];
}
//heal
?>