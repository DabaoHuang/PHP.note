<?php

# cat access.log.1| grep index-top-de | awk '{print $1}' > buglist

$listFile ='';
$list ='';
$iplist = Array();
$ip = '';

$listFile = file_get_contents('buglist');

$list = explode("\n",$listFile);

foreach( $list as $ip ) {
    $iplist[$ip]++;
}

arsort($iplist);

ob_start();
foreach($iplist as $ip => $count) {
    echo $ip . ' => ' . $count . "\n";
}
$text = ob_get_clean();

file_put_contents('output',$text);
