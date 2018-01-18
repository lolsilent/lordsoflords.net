<?php 
require_once('AdMiN/www.main.php');
require_once($inc_functions);
if(!empty($_GET['sid']) and empty($sid)){$sid=clean_post($_GET['sid']);}
if(!empty($_POST['sid']) and empty($sid)){$sid=clean_post($_POST['sid']);}
if(empty($sid)){header("Location: $root_url");exit;}

$served=1;
if (!empty($_GET['served'])){
$served=clean_post($_GET['served']);
}

$layouts = array(
'menu on the left side with chat',
'menu on the right side with chat',

'menu on the left side without chat',
'menu on the right side without chat',

'menu on the left side chat up',
'menu on the right side chat up',

'menu on the left control chat up',
'menu on the right control chat up',

'menu on the left side without chat control up',
'menu on the right side without chat control up',
);

if (!empty($_COOKIE['layout'])) {
	$layout=clean_post($_COOKIE['layout']);
	if (!in_array($layout,$layouts)) {$layout=$layouts[0];}
}

if (!empty($_GET['layout'])) {
	$layout=clean_post($_GET['layout']);
	if (in_array($layout,$layouts)) {
		setcookie ("layout", "$layout",$current_time+5000000) or die("$error_message");
	}else{
		$layout='';
	}
}else{
	$layout='';
}

if ($layout == $layouts[0]) {
print '<html><head><title>'.$title.'</title>';
include_once("$html_style");
print '</head>
<frameset cols="100,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_side" src="'.$root_url.'/menu.php?sid='.$sid.'&served='.$served.'" frameborder="0" framespacing="0" scrolling="auto">
<frameset rows="*,25,25,25%" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_main" src="'.$root_url.'/main.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_fcontrol" src="'.$root_url.'/fight_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_control" src="'.$root_url.'/chat_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_chit" src="'.$root_url.'/chat.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>
</frameset>
<noframes>
<body>
Please use a browser that supports frames.
</body>
</noframes>
</html>';

exit;
}elseif ($layout == $layouts[1]) {
print '<html><head><title>'.$title.'</title>';
include_once("$html_style");
print '</head>

<frameset cols="*,100" frameborder="0" framespacing="0" scrolling="auto">

<frameset rows="*,25,25,25%" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_main" src="'.$root_url.'/main.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_fcontrol" src="'.$root_url.'/fight_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_control" src="'.$root_url.'/chat_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_chit" src="'.$root_url.'/chat.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>

<frame name="'.$server.'_side" src="'.$root_url.'/menu.php?sid='.$sid.'&served='.$served.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>
<noframes>
<body>
Please use a browser that supports frames.
</body>
</noframes>
</html>';

exit;
}elseif ($layout == $layouts[2]) {
print '<html><head><title>'.$title.'</title>';
include_once("$html_style");
print '</head>

<frameset cols="100,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_side" src="'.$root_url.'/menu.php?sid='.$sid.'&served='.$served.'" frameborder="0" framespacing="0" scrolling="auto">
<frameset rows="*,25" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_main" src="'.$root_url.'/main.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_fcontrol" src="'.$root_url.'/fight_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
</frameset>
</frameset>
<noframes>
<body>
Please use a browser that supports frames.
</body>
</noframes>
</html>';
exit;
}elseif ($layout == $layouts[3]) {
print '<html><head><title>'.$title.'</title>';
include_once("$html_style");
print '</head>

<frameset cols="*,100" frameborder="0" framespacing="0" scrolling="auto">

<frameset rows="*,25," frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_main" src="'.$root_url.'/main.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_fcontrol" src="'.$root_url.'/fight_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
</frameset>

<frame name="'.$server.'_side" src="'.$root_url.'/menu.php?sid='.$sid.'&served='.$served.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>
<noframes>
<body>
Please use a browser that supports frames.
</body>
</noframes>
</html>';
exit;
}elseif ($layout == $layouts[4]) {
print '<html><head><title>'.$title.'</title>';
include_once("$html_style");
print '</head>

<frameset cols="100,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_side" src="'.$root_url.'/menu.php?sid='.$sid.'&served='.$served.'" frameborder="0" framespacing="0" scrolling="auto">
<frameset rows="25%,25,25,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_chit" src="'.$root_url.'/chat.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_fcontrol" src="'.$root_url.'/fight_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_control" src="'.$root_url.'/chat_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_main" src="'.$root_url.'/main.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>
</frameset>
<noframes>
<body>
Please use a browser that supports frames.
</body>
</noframes>
</html>';
exit;
}elseif ($layout == $layouts[5]) {
print '<html><head><title>'.$title.'</title>';
include_once("$html_style");
print '</head>

<frameset cols="*,100" frameborder="0" framespacing="0" scrolling="auto">

<frameset rows="25%,25,25,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_chit" src="'.$root_url.'/chat.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_fcontrol" src="'.$root_url.'/fight_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_control" src="'.$root_url.'/chat_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_main" src="'.$root_url.'/main.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>

<frame name="'.$server.'_side" src="'.$root_url.'/menu.php?sid='.$sid.'&served='.$served.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>
<noframes>
<body>
Please use a browser that supports frames.
</body>
</noframes>
</html>';
exit;
}elseif ($layout == $layouts[6]) {
print '<html><head><title>'.$title.'</title>';
include_once("$html_style");
print '</head>

<frameset cols="100,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_side" src="'.$root_url.'/menu.php?sid='.$sid.'&served='.$served.'" frameborder="0" framespacing="0" scrolling="auto">
<frameset rows="25,25,25%,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_fcontrol" src="'.$root_url.'/fight_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_control" src="'.$root_url.'/chat_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_chit" src="'.$root_url.'/chat.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_main" src="'.$root_url.'/main.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>
</frameset>
<noframes>
<body>
Please use a browser that supports frames.
</body>
</noframes>
</html>';

exit;
}elseif ($layout == $layouts[7]) {
print '<html><head><title>'.$title.'</title>';
include_once("$html_style");
print '</head>

<frameset cols="*,100" frameborder="0" framespacing="0" scrolling="auto">
<frameset rows="25,25,25%,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_fcontrol" src="'.$root_url.'/fight_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_control" src="'.$root_url.'/chat_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_chit" src="'.$root_url.'/chat.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_main" src="'.$root_url.'/main.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>

<frame name="'.$server.'_side" src="'.$root_url.'/menu.php?sid='.$sid.'&served='.$served.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>
<noframes>
<body>
Please use a browser that supports frames.
</body>
</noframes>
</html>';

exit;
}elseif ($layout == $layouts[8]) {
print '<html><head><title>'.$title.'</title>';
include_once("$html_style");
print '</head>

<frameset cols="100,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_side" src="'.$root_url.'/menu.php?sid='.$sid.'&served='.$served.'" frameborder="0" framespacing="0" scrolling="auto">
<frameset rows="25,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_fcontrol" src="'.$root_url.'/fight_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_main" src="'.$root_url.'/main.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>
</frameset>
<noframes>
<body>
Please use a browser that supports frames.
</body>
</noframes>
</html>';

exit;
}elseif ($layout == $layouts[9]) {
print '<html><head><title>'.$title.'</title>';
include_once("$html_style");
print '</head>

<frameset cols="*,100" frameborder="0" framespacing="0" scrolling="auto">
<frameset rows="25,*" frameborder="0" framespacing="0" scrolling="auto">
<frame name="'.$server.'_fcontrol" src="'.$root_url.'/fight_control.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="no">
<frame name="'.$server.'_main" src="'.$root_url.'/main.php?sid='.$sid.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>

<frame name="'.$server.'_side" src="'.$root_url.'/menu.php?sid='.$sid.'&served='.$served.'" frameborder="0" framespacing="0" scrolling="auto">
</frameset>
<noframes>
<body>
Please use a browser that supports frames.
</body>
</noframes>
</html>';

exit;
}

include_once($game_header);
print '<table width="100%"><tr><th>Please select an layout that fits you</th></tr><tr><td>';
foreach ($layouts as $val) {
print '<a href="layout.php?sid='.$sid.'&layout='.$val.'&served='.$served.'" target="_top">'.ucfirst($val).'.</a><br>';
}
print '</td></tr></table>';
include_once($game_footer);
?>