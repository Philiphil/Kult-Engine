<?php

namespace kult_engine;


class urlTempo{
	public function tempo($url, $time,$key=""){
	    $url = strpos($url,".php?") === -1 ? $url."?" : $url."&";
	    $url .= "time=".$time;
	    $url .= "&token=".hash("crc32b",$time.$key);
	    return $url;
	}

	public function untempo($url,$key)
	{
	    preg_match('/time=(.*)&/', $url, $e);
	    preg_match('/&token=(.*)/', $url, $o);
	    return hash("crc32b",$e[1].$key) === $o[1];
	}
}
