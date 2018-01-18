<?php 
#!/usr/local/bin/php
require_once('AdMiN/www.main.php');
require_once($inc_functions);
require_once($html_header);
print '
<p><b>Lords of Lords Cookie Station</b></p>
';
if(!empty($_GET['delete_cookie'])){
	print '<b>';
$delete_cookie=clean_post($_GET['delete_cookie']);
if(!empty($_COOKIE[$delete_cookie])){
setcookie ("$delete_cookie", "", time() - 3600);
print 'Cookie name : <b>'.$delete_cookie.'</b> deleted.';
}else{print 'Cookie does not exist, was already deleted or you have double clicked on the link.';}
	print '</b>';
}
print '<table width="100%" border="0">

	<tr>
	<dl><dt>What are Cookies?</dt><dd>
Cookies holds information about your computer and is saved to your hard disk in a text file. The information may be needed to play the games or using our site, all of them can be changed and or deleted here or if you do it manually with your  browser.<br>
Here you can delete cookies from this particular part of this site.
	</dd>
	</dl>
	</tr>
<tr><dl><dt>Cookies on '. $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'</dt><dd></dd></dl></tr>
';
if (!empty($_COOKIE)){
foreach ($_COOKIE as $key=>$val){
print '<tr><dl><dt>Name: '.$key.' <a href="?delete_cookie='.$key.'" title="Delete this cookie named '.$key.'.">Delete</a></dt><dd>Value: '.$val.'</dd></dl></tr>';
}
}else{
print '<tr><dl><dt>No cookies found</dt><dd>If you plan to register on this site be sure to allow cookies from this site to enter the most parts of this site.</dd></dl></tr>';
}
print '



	<tr>
	<dl><dt><hr></dt><dd>
<script type="text/javascript"><!--
google_ad_client = "pub-2087744073845065";
google_ad_width = 336;
google_ad_height = 280;
google_ad_format = "336x280_as";
google_ad_type = "text";
google_ad_channel ="6745771031";
google_color_border = "000000";
google_color_bg = "000000";
google_color_link = "FFF888";
google_color_url = "FFF888";
google_color_text = "FFFFFF";
//--></script>
<script type="text/javascript"
  src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	</dd>
	</dl>
	</tr>

	</table>

';

require_once($html_footer);
?>