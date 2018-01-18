<?php 
$whot_info=array(
'Main'=>"Main overview of character.",
'Paper'=>"This is a in game news paper,you can see latest gold or credits transfers,changes of noble titles and game administrengthator actions.",
'Duels'=>"Results of recently fought duels.",
'Steals'=>"Best thievery operations from the passed 30 days.",
'Graves'=>"To be burried your char must be level 100 or above. How to get burried? Die allot,everything on your char is stolen or don't login to your char for a long period time.",
'Matches'=>"Tournaments and battle information,click on a tournament fight to see clan stats and other information.",

'Clan'=>"To join or create a clan you be level 1.000 or over it. Once you have created a clan you automaticly join your own clan,a password is required for other players to join your clan.
When you are the leader you may remove members and kick clan members when you think they don't belong in your clan.
The top 10 players sorted by level will fight the tournament for your clan.",
'Tourney'=>"Tourney is a shortcut for tournament, to join a tournament you must give a percentage of your experience and gold to fight in the tournament. The amount depends on your Level. If you are low on experience or gold you will be not allowed to join the tournament. You can gain max 50% of the total experience and gold.",
'Challenge'=>"Schedule a duel with other online players.",
'Schedule'=>"Check if someone has challenged you,if so you can accept the challenge or reject it. Or just cancel your own challenges. You can win or loose 5% of your experience and gold! The loser pays 10% of the 5% total experience and gold!",


'Stats'=>"Check your Battlefield stats,or required experience for next level. If you have leveled up you may choose a stats to learn. 'Natural' is your actual stats,'Bonus' is the total charm bonus percentage,and 'Charmed' are stats that can be used in battle but it won't help you to wear more heavy gear. If you have leveled up you can choose a stat to increase.",
'Fight'=>"When you kill a monster you will gain experience and find gold on his corpse. The number behind the monster is the experience of the monster. If you die you can lose up to 5% experience and gold.",
'World'=>"When you go out of the town you can lose more experience gold because your spirit has to go back to town and hire allot mercenaries to bring back your corps. But you can gain more experience and gold,you will notice this especially when you are a low level character.<br><br>
This is the only place you can find free upgrades for your items that can exceed your requirements. The random number gives out one freebie for each ten fights only when the location level+monster level is greater than your level.
<br><br>
And the only place to find powerfull stats charms that gives you a bonus boost for a period of time on one or multiple stats! Best charm looks about like this,it drop like about one out of a million.",
'Shop'=>"Here can you can upgrade your weapon,armors or mystical heals.",
'Inventory'=>"See your equipments. And other items.",


'Transfer'=>"Transfer gold to an other char or help an beginner with money aid. you can carry 1.000.000.000 gold per level at once. If you have credits a credits field is shown that let's you transfer credit in units of 10.
<br>
<b>gold transfers</b>are rounded in units of thousands. If you transfer less than 1.000 gold you will automaticly transfer 10% of your total gold. One level can carry 1.000.000.000 gold.<br>
<b>Credit transfers</b>are rounded in units 10,less will automaticly transfer 10% of your total credits.
All credit transfers are logged any abused of a bug / any kind of way to increase the credit amount of not ment to be will result in a account deletion.",
'Messages'=>"Send a private message to a player or read a message.",
'Stash'=>"Prevent losing your gold when you die to put your gold in the stash.",
'Charms'=>"Because you can only hold up to 5 charms that are personally made for you and not useable for any other chars you can destrengthoy the bad charms here,and then try to look for better charms.
<br>If you like a charm,you can also recharge a charm for 500.000 second at a certain price. If a charm has 50.000 or less seconds left you can recharge again,or just ad 10.000 seconds to it. you hold up to 5 charms max. Sell your charms or give it to other players by clicking on transfer.",
'Market'=>"Sell your charms for gold or credits on the market to other players. Market shows only items that you can afford and your own charms on the market.<br>Click on buy to buy the charm for the amount of gold and/or credits.<br>Click on retract to cancel your own items on the market.<br>Charm advertising stays on the market for 5 days.<br>",


'Steal'=>"This is a place WHERE you can steel experience and gold from other players that haven't logged in for 30 days or longer. When their experience and gold amount is lower than their level they get deleted!. The minimum is 10k experience and gold the best place for beginners and you are cleaning up the inactives!!! But it can't be more than you currently own!.",
'Prefz'=>"Change site colors,font setting and Chat preferences<br><b>[Change password or email]</b>Change password and email here.
<br><b>[fp exchanger]</b>fp can't be transferred it can only be exchanged with one of your other chars. BE CAREFUL if you want it back from the other char because the minimum amount is 5.000 seconds and the other player must be over level 100. This is to prevent creating a new char and transfer the 5.000 start fp to your strengthonger char.",
'Nobility'=>"Noble Titles get updated when you click on Nobility in the game. Once you have dropped out of the ladder your title will be kept until you have reached an higher title or within range of getting an other title.",
'support'=>"support this game by seeing or visiting my sponsors or donate with Paypal.
<br><b>credits</b>$1.00 will give you 100 credits,you can spend it on all these items below at your own choice.
The amount and price will increase at each 250 levels,except 'banner star'. max price for all items is set to 500 credits. ",
'Politics'=>"praise a Lord to make a Lord to be a mod and/or hate a mod to votes against him/her.
you must be in the top 100 ladder to gain the ability to become a possible mod player,please choose wisely all mod players can mute other players.
your level must be or over 500 to vote. One player may only vote once but you can change your vote at anytime.
To become a mod,a player needs at least 5 votes and 5% of the total votes,if a mod is voted away by the players his char will become a Demon and lose the ability to mute!.",
'Logout'=>"Leave this game,after you have left the game you can choose the clear all cookies that was set by the game or just see what cookies WHERE set.",

'Town'=>"See who is in the game.",
'Ladders'=>"To stay/come in the ladder your experience must be greater than level*1.000.000. If you went to vacation for longer than 5 days you need to login to come back on the ladder.",
'Forums'=>"Go to the forums.",
'Guide'=>"Open this guide lol.",
);
?>
<tr><th align="center" colspan="2">Menu</th></tr>
<?php 
foreach($whot_info as $key=>$val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $key;?></td><td><?php print $val;?></td></tr><?php 
}
?>