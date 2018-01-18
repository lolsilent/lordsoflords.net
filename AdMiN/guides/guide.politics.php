<tr>
<th align="center">Politics</th></tr><tr><td>
you must be in the top 100 ladder to join The High Council,once you are a member of the Council you may vote for new players who applied for a job in the councils.
One player may only have one High Council!! Depending on the population of the current server you are on,it shows how much Councils are allowed.
<br>
<table width="100%">
<tr><th colspan="4">Politic rules</th></tr>
<tr><th>Change to</th><th>Permissions needed of</th><th>Current sex</th><th>Requirement</th></tr>
<?php 
$permissionsss=array(
//Title,Permission Needed,Current sex,Requirements,Descprition,Regulations'admin'=>array('75% admins and 25% cops','cop',"All requirements for cop,mod and support","See that all cops,mods and supports are doing their job. Inactive for $opinactive[0] days will result in lose of title"),
'cop'=>array('25% admins and 75% cops','mod',"Knows the game and enforces the rules","must enforce the rules. must follow the rules. Inactive for $opinactive[1] days will result in lose of title"),
'mod'=>array('1 admin,25% cops and 75% mods','support',"Trust worthy,know the guide verry well.","must follow the rules. Inactive for $opinactive[2] days will result in lose of title"),
'support'=>array('1 admin,25% mods and 75% supports','None',"Need to know the guide a bit","must assist with any questions about the game. support does not mean that they must give you gold or exp. Being inactive for $opinactive[3] days title will be taken away."),
'Demon'=>array('admin','AdMiN/Cop/Mod','Corrupted admin,cop or mod','Players who have abused their power in any way'),
'Lady/Lord'=>array('n/a','n/a','n/a','Normal players of the game','n/a'),
'Danger'=>array('admin','Any','Applies to dangerous players','Players who have repetely broken the rules of the game'),
'Untrust'=>array('admin','Any','Applies to untrusted players','Players who have demonstrated to others that they are not to be trusted'),
'Beggar'=>array('admin','Any','Applies to players who beg too much','Players who often ask for free things,in violation of the rules')
);

foreach($permissionsss as $key=>$val){
if(empty($bgcolor)){$bgcolor=" bgcolor=\"#234567\"";}else{$bgcolor='';}
print "<tr$bgcolor><td>$key</td><td>$val[0]</td><td>$val[1]</td><td>$val[2]</td></tr>";
}
?>
</table>
</td></tr>