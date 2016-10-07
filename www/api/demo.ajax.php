<?php
	require_once("../invoker.class.php");
	invoker::require_basics('api');
	membre::login_required();
	membre::dont_wait();

	$req = $_GET['req'];
	$args = $_GET['args'];

	switch ($req) {
		case 'JsCall':
			Anwser();
			break;
	}

	function Anwser()
	{
		echo '1';
	}
