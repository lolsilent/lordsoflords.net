<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

if ($row->charname == $wrow->admin_name and !empty($_POST['rules'])){
$rules = clean_post($_POST['rules']);
$wrow->rules = $rules;
mysqli_query($link, "UPDATE `$tbl_aservers` SET `rules`='$rules' WHERE (`id`='$wrow->id') LIMIT 1") or die (mysqli_error($link).'<hr>'.$world_update);
}

print '<table><tr><th>World rules provided by '.$wrow->admin_name.'.</th></tr><tr><td align=center>
<form method=post><textarea cols="75" rows="35"  name="rules">'.$wrow->rules.'</textarea>';

if ($row->charname == $wrow->admin_name){
print '<br><input type=submit name=action value="Submit changes!">';
}

?></form></td></tr></table><?php 
require_once($game_footer);
?>