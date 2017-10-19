<?php
	require_once '../../config.php';
	$w = kult_engine\invoker::webService();


	$w->service("test", function($args){
		return ["op" => 1];
	}, "POST");


;


	$w->execute();