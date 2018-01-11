<?php

/**
 * 除了用JS擋之外，防止User重複點擊的小撇步
 */

function lock($key){
	global $lock_fp;
	if(!file_exists("lock/$key"))file_put_contents("lock/$key",'');
	$lock_fp = fopen("lock/$key", "r+");
	flock($lock_fp, LOCK_EX);
	
}

function unlock($key){
	global $lock_fp;
	flock($lock_fp, LOCK_UN);
	fclose($lock_fp);
}


?>