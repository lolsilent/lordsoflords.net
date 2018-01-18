<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);

if(!empty($_POST['action'])){$action=clean_post($_POST['action']);
	$amounted=$row->gold+$row->stash;
if($action == 'Deposit' and $row->gold>=1){
$update_it.=", `gold`=0, `stash`=$amounted";$row->stash=$row->gold+$row->stash;$row->gold=0;
}elseif($action == 'Withdraw' and $row->stash>=1){
$update_it.=", `stash`=0, `gold`=$amounted";$row->stash=0;$row->gold=1;
}
}else{$action='';}

print '<form method="post" action="stash.php?sid='.$sid.'">
<table width="100%">
<tr><th colspan="2">Secret gold stash</th></tr>
<tr><td colspan="2">You can stash all your gold here to prevent it from losing it when you loose a fight.<br>You have '.lint($row->stash).' gold in your stash.';
print (($action == 'Withdraw')?' You put $'.lint($amounted).' gold in your bagpack':'');
print '</td></tr>
<tr><th width="50%">'.($row->gold>=1?'<input type="submit" name="action" value="Deposit">':'').'</th><th width="50%">'.($row->stash>=1?'<input type="submit" name="action" value="Withdraw">':'').'</th></tr>
</table>
</form>';
require_once($game_footer);
?>