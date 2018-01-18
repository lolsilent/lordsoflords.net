<?php 
#!/usr/local/bin/php
$sroot_url = $_SERVER['DOCUMENT_ROOT'];
$images_url='/images';
$emotions_url='images/emotions';

$admin_name='admin SilenT';
$admin_email='admin@thesilent.com';
$error_email='xbros13@xs4all.nl';
$notify_url='https://thesilent.com/paypal/index.php';
$paypal_email='paypal@thesilent.com';

function microtime_float(){list($usec, $sec) = explode(" ", microtime());return ((float)$usec + (float)$sec);}
$current_time=microtime_float();
$current_date = date('d M Y');
$current_month = date('n');
$current_clock = date('H:i:s');
$logdate=date('d-m-Y');

$punished_sex=array('BeggaR','UntrusT','DangeR','SpammeR','CriminaL','DemoN','OutlaW','FrauD','StealeR','AnnoyancE','HelpeR');
$sap=array('+','-','*','/');

$colbg='#000000';
$coltext='#FFFFFF';
$collink='#888FFF';
$col_hover='#FFFFFF';
$col_table='#123456';
$colth='#123456';
$colform='#123456';
$col_frame="#123456";
$fontfamily='Verdana,Arial,Monaco';
$fontsize='8pt';
$align='center';
$valign='top';

$ip=$_SERVER['REMOTE_ADDR'];if(empty($ip)){header("Location: $sroot_url");}

//some_error(__FILE__.'-'.__LINE__.'-'.__FUNCTION__.'-'.__CLASS__.'-'.__METHOD__);
function some_error($in){
global $error_email,$server;
print $in;
require_once('AdMiN/some_error.php');
exit;
}
?>