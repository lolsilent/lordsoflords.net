<?php 
$search=array("'fuck'i","'nigger'i","'vagina'i","'pussy'i","'penis'i");
$replace=array("","","","","","","","","","",);

function clean_post($in){
	global $search,$replace;
$in=preg_replace($search,$replace,$in);
$in=htmlentities("$in",ENT_QUOTES);
$in=strip_tags($in);
$in=trim($in);
$in=addslashes($in);
return $in;
}

function clean_input($in){
	global $search,$replace;
$in=preg_replace($search,$replace,$in);
$in=strip_tags($in);
$in=trim($in);
$in=addslashes($in);
if(ctype_alnum($in) and strlen($in)>=4){
return $in;
}else{
$in=NULL; return $in;
}
}

function clean_int($in){
$in=preg_replace(array('@,@','@\.@','@\-@'),array('','',''),$in);
if(is_numeric($in) and $in>=1){
return $in;
}else{
return NULL;
}
}

function dater($secs){
global $current_time;
$s='';$i=0;
if ($current_time-$secs < 0){
$secs=round($secs-$current_time);
}else{
$secs=round($current_time-$secs);
}
if($secs>= 86400*356){
$n=(int)($secs/(86400*356));$s.=$n.' year'.($n>1?'s ':' ');$secs %= (86400*356);
}elseif($secs>= 86400*30){
$n=(int)($secs/(86400*30));$s.=$n.' month'.($n>1?'s ':' ');$secs %= (86400*30);
}elseif($secs>= 86400*7){
$n=(int)($secs/(86400*7));$s.=$n.' week'.($n>1?'s ':' ');$secs %= (86400*7);
}elseif($secs>= 86400){
$n=(int)($secs/86400);$s.=$n.' day'.($n>1?'s ':' ');$secs %= 86400;
}elseif($secs>= 3600){
$n=(int)($secs/3600);$s .=$n.' hour'.($n>1?'s ':' ');$secs %= 3600;
}elseif($secs>= 60){
$n=(int)($secs/60);$s .=$n.' minute'.($n>1?'s ':' ');$secs %= 60;
}elseif($secs>=1){
$s .=$secs.' second'.($secs>1?'s ':' ');
}elseif($secs<=0){
$s .='0 seconds';
}
return trim($s);
}


function sdater($secs){
global $current_time;
$s='';$i=0;
if ($current_time-$secs < 0){
$secs=round($secs-$current_time);
}else{
$secs=round($current_time-$secs);
}

if($secs>= 86400){
$n=(int)($secs/86400);$s.=$n.'D ';$secs %= 86400;
}
if($secs>= 3600){
$n=(int)($secs/3600);$s .=$n.':';$secs %= 3600;
}
if($secs>= 60){
$n=(int)($secs/60);$s .=$n.':';$secs %= 60;
}
if($secs>=1){
$s .=$secs;
}elseif($secs<=0){
$s .='00';
}
return trim($s);
}

function get_sap(){
	global $sap;
$sapa=rand(1,9);
$sapb=$sap[array_rand($sap,1)];
$sapc=rand(1,9);
if($sapa<=$sapc){$sapa=$sapc+1;}
if($sapb == '/'){$sapa*=2;$sapc=2;}
return array($sapa,"$sapb",$sapc);
}

function sap_me($asap,$isap){
preg_match("/(.*) (.*) (.*)/i",$asap,$sapi);
//print "$asap | $isap | $sapi[1] | $sapi[2] | $sapi[3] ";
if($isap == ($sapi[1]+$sapi[3]) and $sapi[2] == '+'){
return('OKE');
}elseif($isap == ($sapi[1]-$sapi[3]) and $sapi[2] == '-'){
return('OKE');
}elseif($isap == ($sapi[1]*$sapi[3]) and $sapi[2] == '*'){
return('OKE');
}elseif($isap == ($sapi[1]/$sapi[3]) and $sapi[2] == '/'){
return('OKE');
}
}

function preg_extract($string,$start,$end){
return trim(preg_replace("/$end/si","",preg_replace("/$start/si","",$string)));
}


function writer($file,$mode,$somecontent){
	/*
$file=preg_replace("/ /i","_",$file);
$write_error='';
		if($handle = fopen($file, $mode)){
			if(fwrite($handle, $somecontent) == FALSE){$write_error .= 'Can not write.';}
			fclose($handle);
		}else{$write_error .= 'Can not open.';}

if(!empty($write_error)){
	global $error_email;
	mail($error_email, "WRITER Net ERROR $file", "$write_error \n $file \n $mode \n $somecontent", "From: someerror@{$_SERVER['SERVER_NAME']}", "-fsomeerror@{$_SERVER['SERVER_NAME']}");
}
*/
}

function next_level(){
global $row;
return ((($row->level/10)*500)+$row->level)*($row->level*$row->level)+449;
}

function chatit($in) {
	global $emotions,$emotions_url;
$hi=array (
'@\[img\](https:\/\/.*?)\[/img\]@si',
'@\[c=(.*?)\](.*?)\[/c\]@si',
);

$ha=array (
'<img src="\1" width="16" height="16">',
'<font color="\1">\2</font>',
);
$in=preg_replace($hi, $ha, $in);

if(preg_match("/\[.*?\]/i",$in)){
foreach($emotions as $face){
if(in_array($face,$emotions)){
$face=strtolower($face);
$in=preg_replace("'\[$face\]'i","<img src=\"$emotions_url/$face.gif\" border=\"0\">",$in);
}}}

return stripslashes($in);
}

function postit($in) {
	global $search,$replace,$emotions,$emotions_url;
$hi=array (
'@\n@si',
'@\[quote\](.*?)\[/quote\]@si',
'@\[img\](https:\/\/.*?)\[/img\]@si',
'@\[url\](https:\/\/.*?)\[/url\]@si',
'@\[url=(https:\/\/.*?)\](.*?)\[/url\]@si',
'@\[email\](.*?\@.*?\..*?)\[/email\]@si',
'@\[c=(.*?)\](.*?)\[/c\]@si',
);

$ha=array (
'<br>',
'<blockquote><hr>\1<hr></blockquote>',
'<img src="\1" border=0>',
'<a href="\1" target="_blank">\1</a>',
'<a href="\1" target="_blank">\2</a>',
'<a href="mailto:\1\">\1</a>',
'<font color="\1">\2</font>',
);
$in=preg_replace($hi, $ha, $in);
$in=preg_replace($search, $replace, $in);


if(preg_match("/\[.*?\]/i",$in)){
foreach($emotions as $face){
if(in_array($face,$emotions)){
$face=strtolower($face);
$in=preg_replace("'\[$face\]'i","<img src=\"$emotions_url/$face.gif\" border=\"0\">",$in);
}}}

return stripslashes($in);
}

function lint($in){
$in=number_format($in);
$in=preg_replace("/,/","",$in);
if(strlen($in)>12){
return number_format(substr($in,0,12)).' Z'.round(strlen($in)-12);
}else{
return number_format($in);
}
}
?>