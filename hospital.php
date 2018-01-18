<?php 
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($inc_titles);
include_once($game_header);

print '<p><b>You found an Easter egg in the game, the medicine man can change your sex for 1000 gold. Not for chars with a special sex.</b></p><br>';

if (!in_array($row->sex,$punished_sex) and !in_array($row->sex,$operators)) {

$sexable=array('Lord'=>'The medicine man build a thing between your legs.',
'Lady'=>'Ouch! That hurts the medicine man cuts of your thing!! AAAhh!',
'Alien'=>'You just got abducted by an alien.',
'Evil'=>'The devil has found you.',
'Angel'=>'Are you really an angel.',
'Crazy'=>'Something in your brains snap!');
if (!empty($_POST['sex'])) {
$sex=clean_post($_POST['sex']);
if ($row->sex !== $sex and array_key_exists($sex,$sexable)) {

$update_it .= ", `gold`=`gold`-1000, `sex`='$sex'";
print $sexable[$sex];

}else{print 'The medicine man refuses to give you a treatment.';}
}
print '<form method=post><table width=100%><tr><th colspan=3>'; echo $title.' Medicine man</th></tr>
<tr><td>Sex change</td><td><select name=sex>';
foreach ($sexable as $key=>$val){
print '<option>'.$key.'</option>';
}print '</select></td><td><input type=submit name=Action value="Go to the operation room!"></td></tr>
</table></form>';
} else {
			print '<p>Sorry we are not allowed to give this treatment to <b>'.$row->sex.'\'s</b>.</p>';
}
include_once($game_footer);
?>