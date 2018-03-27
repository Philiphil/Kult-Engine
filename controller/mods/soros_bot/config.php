<?php
namespace kult_engine;

	$KEY = "";

	$SECRET = "";

	$SYMBOLE = "EUR";

	$NOFOLLOW = ["BTC",
		"XRP",
		"TRX",
		"LINK",
		"REQ",
		"XLM",
		"STORM",
	];

	$ETALON="BTC";

	$test_currency = null;

	$min_buy_value=-0.5;
	$min_buy_variation=0.2;

	$min_sell_value=0.5;
	$min_sell_variation=-0.2;


	$tick=0;

//Periods: 1m,3m,5m,15m,30m,1h,2h,4h,6h,8h,12h,1d,3d,1w,1M
	$delay="15m";

	$min_records=4;
	$max_records=8;
	$db_records=0;




$kraken_key = "";
$kraken_secret = "";