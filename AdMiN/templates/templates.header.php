<?php 
if(isset($_COOKIE['pc']) and !empty($_COOKIE['pc'])){$pc=clean_post($_COOKIE['pc']);}else{$pc='';}

if(isset($_COOKIE['bg']) and !empty($_COOKIE['bg'])){$colbg=clean_post($_COOKIE['bg']);}
if(isset($_COOKIE['text']) and !empty($_COOKIE['text'])){$coltext=clean_post($_COOKIE['text']);}
if(isset($_COOKIE['alink']) and !empty($_COOKIE['alink'])){$collink=clean_post($_COOKIE['alink']);}
if(isset($_COOKIE['th']) and !empty($_COOKIE['th'])){$colth=clean_post($_COOKIE['th']);}
if(isset($_COOKIE['form']) and !empty($_COOKIE['form'])){$colform=clean_post($_COOKIE['form']);}
if(isset($_COOKIE['family']) and !empty($_COOKIE['family'])){$fontfamily=clean_post($_COOKIE['family']);}
if(isset($_COOKIE['fsize']) and !empty($_COOKIE['fsize'])){$fontsize=clean_post($_COOKIE['fsize']);}

print '<html><head><title>'.$title.' the sword of the sixth elements</title>
<meta NAME="description" CONTENT="Text based browser rpg game.">
<meta NAME="keywords" CONTENT="text based rpg game">';
include_once($html_style);
print '</head>
<body><center>
<!--Online text based role playing game based on lords of lords 1 engine version 3, has been 
launched silently on 03-09-2005 06:16 
modified for lordsoflords.net on 12-1-2006 16:34 
OMG!!! 10 years ago
last update and modified on 4/19/2016 3:07:44 PM
-->
<table width=100%><tr><th><a href="https://lordsoflords.net">Home</a></th>';
	$files=array('login','signup','screenshots');
	foreach($files as $file){
	print '<th><a href="https://lordsoflords.net/'.$file.'.php">'.ucfirst($file).'</a></th>';
	}

print '<th><a href="https://lordsoflords.com/forums/">Forums</a></th>';
print '<th><a href="https://lordsoflords.com">.COM</a></th>';
print '</tr></table>';
print '<img src="images/lol.jpg">';
?>
