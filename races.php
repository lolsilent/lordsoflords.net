<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

$ppower = array('race'=>'race name','ap'=>'attack power','dp'=>'defending powers','mp'=>'mystical powers','tp'=>'thievery powers','rp'=>'race power','pp'=>'pimp power');

print '<table border=1><tr><th colspan=7>WORLD RACES</th></tr><tr>';
foreach ($ppower as $key=>$val) {
	print '<th>'.strtoupper($val).'</th>';
}
print '</tr>';


if($rresult=mysqli_query($link, "SELECT * FROM `$tbl_races` WHERE `server_id`='$row->server_id' ORDER BY `race` ASC LIMIT 10")){
while($robj=mysqli_fetch_object($rresult)){

print '<tr><td nowrap>'.$robj->race.'</td><td>'.$robj->ap.'</td><td>'.$robj->dp.'</td><td>'.$robj->mp.'</td><td>'.$robj->tp.'</td><td>'.$robj->rp.'</td><td>'.$robj->pp.'</td></tr><tr><td> </td><td colspan=6>'.stripslashes($robj->description).'</td></tr>';

}
mysqli_free_result($rresult);
}



print '</tr></table><b>Only world admins can give out these races!</b>';

require_once($game_footer);
?>