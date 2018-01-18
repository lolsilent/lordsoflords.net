<tr>
<th colspan="2">Chat</th>
</tr><tr><td colspan="2">First field is for the message,post message button,clear is chat message by moving your mouse over it or clicking it to reload.
<br>
Click Post button to post a message. To clear your message click in the message field,move or click your mouse over the Clear button. level 100 is required to chat.
</td></tr>
<tr><td width="50%">Type this...</td><td>...to get this</td></tr>
<tr bgcolor="<?php print $col_th;?>"><td width="50%">~c[#FF0000]TEST</td><td><font color="#FF0000">TEST</font></td></tr>
<tr><td width="50%">~c[RED]TEST</td><td><font color="RED">TEST</font></td></tr>
<?php 
require_once('AdMiN/www.faces.php');
foreach($faces as $face){
$face=strtolower($face);
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td>[<?php print $face;?>]</td><td><img src="<?php print $emotions_url.'/'.$face;?>.gif" alt="<?php print $face;?>" border="0"></td></tr><?php 
}