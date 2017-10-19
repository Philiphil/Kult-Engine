<?php
namespace kult_engine;

class sse{
	function __construct()
	{
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		header("Connection: keep-alive");
	}

	function __invoke($msg,$event="",$id="")
	{
		if($id != "")
		{
			echo "id: ".$id . "\n";
		}
		if($event != "")
		{
			echo "event: ".$event . "\n";
		}
		echo "data: ".$msg . "\n";
		echo "\n";
		ob_flush();
		flush();
	}

	
}