<?php
namespace kult_engine;

class webPolice
{
	function __construct()
	{
		if( strpos($_SERVER['HTTP_USER_AGENT'], "Chrome") !== false)  header('Location: https://www.google.com/');   
	} 
}