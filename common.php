<?php 

class Ccommon
{
    function getCache($url, $time = 86400, $type = '', $header=array())
    {
        $cache = md5($url);
        $cachedir = substr($cache,0,3);
        $cachedir2 = substr($cache,3,3);
        $cachepath = "cache/$cachedir/$cachedir2/$cache";

        if( file_exists($cachepath) && (strtotime('now') - filectime($cachepath)) < $time ) {
            $content = base64_decode(unserialize(file_get_contents($cachepath)));
        } else {
            if( $type == 'curl' ) {
                $ch = curl_init();
                // 抓最新 // $header[] = "Cache-Control: no-cache"; // $header[] = "Pragma: no-cache";
                if($header) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                // 等待時間
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
                curl_setopt($ch, CURLOPT_TIMEOUT, 4);
                curl_setopt($ch, CURLOPT_URL, $url);
                $content = curl_exec($ch);
                curl_close($ch);
            } else {
                $content = file_get_contents($url);
            } // end if $type == 'curl'
            if( !file_exists('cache') ) mkdir('cache');
            if( !file_exists('cache/'.$cachedir) ) mkdir('cache/'.$cachedir);
            if( !file_exists('cache/'.$cachedir.'/'.$cachedir2) ) mkdir('cache/'.$cachedir.'/'.$cachedir2);
            file_put_contents($cachepath,serialize(base64_encode($content)));
        }  
        return $content;
    }
}

