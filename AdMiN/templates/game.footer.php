<?php 
if(!empty($update_it)){

if(($current_time-$row->timer) < 0.25){
	if($row->jail>=$current_time-1){
		if($row->jail-$current_time < 50){
		$update_it .= ", `jail`=`jail`+'5'";
		}
	}else{
		$update_it .= ", `jail`='$current_time'";
	}
	print '<hr><b>Be careful you will get jailed if you are flooding the server!</b><hr>';

//print '<hr>Flood protection activated.<hr>';
}else{
	//$update_it .= ", `jail`='0'";
}

mysqli_query($link, "UPDATE `$tbl_members` SET $update_it WHERE `id`=$row->id LIMIT 1") or print(mysqli_error($link));

}
mysqli_close($link);

print '<br><font size="1">&copy; 1999-'.date("Y").' SilenT STFU! '.number_format(microtime_float()-$current_time,5).'</font></center></body></html>';
?>