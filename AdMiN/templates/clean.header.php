<?php 
if(isset($_COOKIE['pc']) and !empty($_COOKIE['pc'])){$pc=clean_post($_COOKIE['pc']);}else{$pc='';}

if(isset($_COOKIE['bg']) and !empty($_COOKIE['bg'])){$colbg=clean_post($_COOKIE['bg']);}
if(isset($_COOKIE['text']) and !empty($_COOKIE['text'])){$coltext=clean_post($_COOKIE['text']);}
if(isset($_COOKIE['alink']) and !empty($_COOKIE['alink'])){$collink=clean_post($_COOKIE['alink']);}
if(isset($_COOKIE['th']) and !empty($_COOKIE['th'])){$colth=clean_post($_COOKIE['th']);}
if(isset($_COOKIE['form']) and !empty($_COOKIE['form'])){$colform=clean_post($_COOKIE['form']);}
if(isset($_COOKIE['family']) and !empty($_COOKIE['family'])){$fontfamily=clean_post($_COOKIE['family']);}
if(isset($_COOKIE['fsize']) and !empty($_COOKIE['fsize'])){$fontsize=clean_post($_COOKIE['fsize']);}

print '<html><head><title>'.$title.'</title>';
include_once($html_style);
print '</head><body><center>';
?>