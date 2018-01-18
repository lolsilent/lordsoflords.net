<?php 
#!/usr/local/bin/php
if(isset($_GET['listing'])){
while(is_array($_GET)&& list($key,$val)=each($_GET)){
switch($val){
case "weapons":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;
foreach($weapons as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "armors":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($armors as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "shields":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($shields as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "rings":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($rings as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "amulets":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($amulets as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "helms":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($helms as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "belts":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($belts as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "pants":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($pants as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "hands":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($hands as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "heals":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($heals as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "spells":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($spells as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "feets":
require_once("items/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($feets as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

case "monsters":
require_once("AdMiN/array.$val.php");
?><tr><th colspan="2"><?php print ucfirst($val);?> list</th></tr><?php 
$i=1;foreach($monsters_array as $val){
?><tr<?php if(empty($bg)){?> bgcolor="<?php print $col_th;$bg=1;?>"<?php }else{$bg='';}?>><td><?php print $i;?></td><td><?php print ucwords($val);?></td></tr><?php 
$i++;
}
break;

}
}
}
?>