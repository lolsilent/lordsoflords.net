<?php 
require_once($inc_titles);
?>
<tr><th align="center">Noble Titles</th></tr>
<tr><td>Noble Titles get updated when you click on Nobility in the game.
Once you have dropped out of the ladder your title will be kept until you have reached an higher title or within range of getting an other title. To stay/come in the ladder your experience must be greater than level*1.000.000. If you went to vacation for longer than 5 days you need to login to come back on the ladder.
<br>
</td></tr>

<tr><td>

<table width="100%">
<tr><td>#</td><td>Ladies</td><td>Lords</td><td>Aliens</td><td>Evils</td><td>Angels</td><td>Craziest</td></tr>
<?php 
for($i=0;$i<count($male);$i++){
if(empty($bgcolor)){$bgcolor=" bgcolor=\"#234567\"";}else{$bgcolor='';}
?><tr<?php print $bgcolor;?>><td><?php print $i;?></td><td><?php if (isset($female[$i])){print $female[$i];}?></td><td><?php if (isset($male[$i])){print $male[$i];}?></td><td><?php if (isset($alien[$i])){print $alien[$i];}?></td><td><?php if (isset($evil[$i])){print $evil[$i];}?></td><td><?php if (isset($angel[$i])){print $angel[$i];}?></td><td><?php if (isset($crazy[$i])){print $crazy[$i];}?></td></tr><?php 
}
?>
</table></td></tr>