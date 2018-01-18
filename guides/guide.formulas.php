<tr><th align="center" colspan="2">Battlefield Formulas(<a href="?Guide=monsters">monsters</a>)</th></tr>
<tr>
<td>Base formulas</td><td>Base weapon(strength%*race attack power).<br>Base magic((Intelligence%*race mystical power)+ Intelligence).<br>Base Defense(agility%*race defend power)+(armor + helm + shield + belt + pants + hand + feet).<br></td></tr>
<tr bgcolor="<?php print $col_th;?>">
<td>Weapon</td><td>minimum strength*(1 + base weapon*1 + weapon minimum).<br>maximum strength*(1 + base weapon*1 + weapon maximum).</td>
</tr>
<tr>
<td>Attackspell</td><td>minimum Intelligence + ring +(base magic*minimum).<br>maximum Intelligence + ring +(base magic*maximum).</td></tr>
<tr bgcolor="<?php print $col_th;?>">
<td>Healspell</td><td>minimum Intelligence + amulet +(base magic*minimum).<br>maximum Intelligence + amulet +(base magic*maximum).</td></tr>
<tr>
<td>Magic shield</td><td>minimum Contravention*base magic +(1 + amulet + ring + Intelligence + Concentration).<br>maximum Contravention*base magic +(1 + amulet + ring + Intelligence + Concentration)+(minimum/2).</td></tr>
<tr bgcolor="<?php print $col_th;?>">
<td>Defense</td><td>minimum agility +(base defence*minimum).<br>maximum agility +(base defence*maximum).</td></tr>
<tr>
<td>Attack rating</td><td>Chance of hitting on melee combats<br>minimum dexterity +(base weapon*minimum).<br>maximum dexterity +(base weapon*maximum).</td></tr>
<tr bgcolor="<?php print $col_th;?>">
<td>Magic rating</td><td>Chance of casting attackspell<br>minimum Concentration +(base magic*minimum).<br>maximum Concentration +(base magic*maximum).</td></tr>

<tr>
<td>Total damage</td><td>
total damage=hit damage + blocked damage
</td></tr>
