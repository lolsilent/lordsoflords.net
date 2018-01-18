<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($game_header);
print '<p><b><h2>YOU FOUND A SECRET IN THE GAME!</h2><br>THE SUPER ONE MERGER!<br>It will merge your char with a chosen char and the other will die!</b></p>work still in progress';

if($fp >= 1){
print '<form method=post><table cellpadding="0" cellspacing="0" border="0" width="300">
<tr>
<th colspan="2">Target Account Information</th>
</tr>
<tr>
<td width="50%">Username</td><td width="50%"><input type="text" name="username" maxlength="10"></td>
</tr>
<tr>
<td>Password</td><td><input type="password" name="password" maxlength="32"></td>
</tr>
<tr>
<th colspan="2"><input type="submit" value="MERGE WITH THIS PLAYER!" name="action"></th>
</tr>
</table></form>';
}else{
print 'Merging is Freeplayers only!';
}
require_once($game_footer);
?>