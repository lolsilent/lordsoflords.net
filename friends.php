<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);
print '
<table width="100%">
<tr><th>Friends</th></tr><tr><td>
';
if($fresult=mysqli_query($link, "SELECT `sex`,`charname`,`timer` FROM `$tbl_members` WHERE `friend`='$row->charname' ORDER BY `timer` DESC LIMIT 100")){
while($fobj=mysqli_fetch_object($fresult)){
print $fobj->sex.' '.$fobj->charname.' was last active '.dater($fobj->timer).' ago.<br>';
}
mysqli_free_result($fresult);
}
print '
</td></tr></table>
Bring all your friends to this url to signup and you will get 10% of the xp and gold that he wins in fights, the amount can\'t be greater than 10% of your xp or gold and less than your level.<br><br><b><a href="'.$root_url.'/signup.php?friend='.$row->charname.'">'.$root_url.'/signup.php?friend='.$row->charname.'</a></b><br>
';
require_once($game_footer);
?>