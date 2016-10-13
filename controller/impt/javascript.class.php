<?php
namespace kult_engine;
use kult_engine\get_text;
/*
	Couplage PHP/JS

*/

	$javascript = "";
	$javascript .="\n<script>\n";
	$javascript .="var kons = kons || {};\n";
	$javascript .="kons.htmlpath = '".constant('htmlpath')."';\n";
	$javascript .="kons.contentpath = '".constant('contentpath')."';\n";
	$javascript .="kons.imagepath = '".constant('imagepath')."';\n";



	$javascript .="var textekons = textekons || {};\n";
	$javascript .="textekons.hello = '".get_text('hello')."';\n";


	$javascript .="</script>\n";
	echo $javascript;