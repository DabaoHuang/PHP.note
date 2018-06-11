<?php

/* 1. 任意數字的字串，每格數字相加，例: 輸入 1367 後，回傳 17，必須使用[遞迴] */

$str = "1367";
function caculate( $num ) {
	$unitNum = (int)$num[strlen($num)-1];
	return ( ( strlen( $num ) === 1 ) ? $unitNum : caculate( substr( $num , 0 , strlen( $num )-1 ) ) + $unitNum ) ;
}
caculate($str);
exit();

