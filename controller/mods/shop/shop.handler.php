<?php
namespace kult_engine;

require_once(constant('optnpath').'daoableObject.class.php');
require_once(constant('optnpath').'daoGenerator.class.php');

define("shoppath", substr(__FILE__,0, strlen(__FILE__)-16));
define("shoptemplate", constant("shoppath")."template".constant("filespace"));
define("shopmodel", constant("shoppath")."model".constant("filespace"));

require_once(constant("shoppath")."shop.config.php");
define("currency_html", shopConfig::$currency_char);
switch (shopConfig::$currency_name) {
	case 'euro':
		define("currency_pdf", chr(128));
		break;
	case 'usd':
	case 'cad':
	define("currency_pdf", chr(36));
	 break;
	 case 'gbp':
	 define("currency_pdf", chr(163));
	 break;
	 case 'jpy':
	 	 define("currency_pdf", chr(165));
	 break;
	default:
	define("currency_pdf", chr(36));
		break;
}


require_once(constant("shoppath")."cart.class.php");
require_once(constant("shoptemplate")."shopText.class.php");
require_once(constant("shoppath")."shop.class.php");
require_once(constant("shopmodel")."model.php");


cart::init();