<?php
	date_default_timezone_set("PRC");
	$fp = fopen("test.txt","a+");
	fwrite($fp, date('Y-m-d H:i:s')."****"."\r\n");
	fclose($fp);
?>