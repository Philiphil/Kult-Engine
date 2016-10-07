<?php
	require_once("../invoker.class.php");
	invoker::require_basics('api');

	membre::login_required();


	switch ($_POST["fonc"]) {
		case 'query':
			answer($_POST["arg1"],$_POST["arg2"]);
			break;
	}



function answer($arg1, $arg2, $num)
{
}

