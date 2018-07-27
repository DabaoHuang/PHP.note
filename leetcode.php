<?php

/* 1. 任意數字的字串，每格數字相加，例: 輸入 1367 後，回傳 17，必須使用[遞迴] */

$str = "1367";
function caculate( $num ) {
	$unitNum = (int)$num[strlen($num)-1];
	return ( ( strlen( $num ) === 1 ) ? $unitNum : caculate( substr( $num , 0 , strlen( $num )-1 ) ) + $unitNum ) ;
}
caculate($str);
exit();

/* 2. 給你一個純正整數陣列取出第二大的數字，不能用內建 sort */

$nums = Array(1,3,2,10,9);

function dabaorank( $nums , $order = 'ASC' ){
	$sortArray = array();
	foreach ( $nums as $num ) {
		$rank = ( $order === 'DESC' ) ? 0 : count($nums)-1 ;
		for ( $i=0; $i < count( $nums ) ; $i++ ) { 
			if( $nums[$i] > $num && $order === 'DESC')
				$rank++;
			else if( $nums[$i] > $num && $order === 'ASC')
				$rank--;
		}
		$sortArray[$rank] = $num;
	}
	return $sortArray;
}

$nums = dabaorank( $nums , 'DESC' ) ;

echo "Sencond max : {$nums[1]}";

exit;
