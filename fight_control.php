<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($inc_locations);

if(!empty($_GET['sid']) and empty($sid)){$sid=clean_post($_GET['sid']);}
if(!empty($_POST['sid']) and empty($sid)){$sid=clean_post($_POST['sid']);}
if(empty($sid)){header("Location: $root_url");exit;}

if(!empty($_POST['difficulty'])){$difficulty=clean_post($_POST['difficulty']);if($difficulty<1){$difficulty=1;}}else{$difficulty=1;}
if(!empty($_POST['plus'])){$plus=1;if($difficulty>100){$difficulty+=$difficulty/20;}else{$difficulty++;}}else{$plus=0;}
if(!empty($_POST['min'])){$min=1;if($difficulty>100){$difficulty-=$difficulty/20;}else{$difficulty--;}}else{$min=0;}

$filer='fight';$i=0;
if(!empty($_POST['iworld'])){
$iworld=strtolower(clean_post($_POST['iworld']));
if(array_key_exists($iworld,$array_location)){
$i=$array_location[$iworld]-1;
$iworld=preg_replace("/ /i","_",$iworld);
require_once('AdMiN/arrays/world.'.$iworld.'.php');
$filer='world';
}else{
require_once($inc_monsters);
}
}else{
require_once($inc_monsters);
}

if($difficulty<=0){$difficulty=1;}
$difficulty=round($difficulty);

include_once($clean_header);
print '
<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td width="75%">

<form method="post" action="'.$filer.'.php?sid='.$sid.'" target="'.$server.'_main">';
if(!empty($_POST['iworld'])){
	print '<input type="hidden" name="iworld" value="'.clean_post($_POST['iworld']).'">';
}
print '<input type="hidden" name="difficulty" value="'.$difficulty.'"><table width="100%"><tr><td width="55%"><select name="monster" style="width:100%;">';
foreach($monsters_array as $val){$i++;
print '<option value="'.$val.'">'.lint($i).' - '.ucwords($val).' - '.lint((96+((1+$i)*(1+$i))*$i)*($difficulty)).'</option>';
}
print '</select></td><td width="15%"><input type="submit" name="action" value="Fight!" style="width:100%;"></td></tr></table></form>

</td><td width="25%">

<form method="post" action="fight_control.php?sid='.$sid.'" ><table width="100%"><tr><td width="10%"><input type="text" name="difficulty" value="'.$difficulty.'" style="width:100%;"></td><td width="10%"><input type="submit" name="action" value="Difficulty" style="width:100%;"></td><td width="5%"><input type="submit" name="plus" value="+" style="width:100%;"></td><td width="5%"><input type="submit" name="min" value="-" style="width:100%;"></td></tr></table>';
if(!empty($_POST['iworld'])){
	print '<input type="hidden" name="iworld" value="'.clean_post($_POST['iworld']).'">';
}
print '</form>

</td></tr></table>';
include_once($clean_footer);
?>