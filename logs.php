<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');include_once($html_header);
$dirs=array('chat','duels','graves','ladder','matches','paper','steals');

if(!empty($_GET['logs'])){
$logs=strtolower(clean_input($_GET['logs']));
if(in_array($logs,$dirs)){
$dir=$logs;
$files=array();
if ($handle = opendir($dir)) {
$i=0;while (false !== ($file = readdir($handle))) {$i++;
if (preg_match("/(\d{2})-(\d{2})-(\d{4}).*?/",$file)) {
$files[filemtime($dir.'/'.$file)]=$file;
}
if($i>=1000){break;}
}
closedir($handle);
}


if(!empty($files)){
print ucfirst($dir).' logs <table cellpadding="1" cellspacing="0" border="0" width="100%"><tr>';
ksort($files);
$tfiles=count($files);
$del=0;$i=0;

foreach ($files as $key=>$file){
	$i++;

if ($tfiles >= 999 and ($tfiles-$del) >= 999){
if (file_exists($logs.'/'.$file)) {
unlink($logs.'/'.$file);$del++;
}
}else{


if($i == 1 or $i == round($tfiles/3)){
		if(!empty($otd)){
			print '</td>';$otd=0;
		}
	print '<td valign="top">';$otd=1;
$i=1;}


print '<a href="'.$dir.'/'.$file.'">'. preg_replace("/.php/i","",$file).'</a><br>';
}
}

print '</td></tr></table><b><br>'.$tfiles.' log files kept.<br></b>';
}else{print 'No logs available.';}
}
}
include_once($html_footer);
?>