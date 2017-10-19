<?php

namespace kult_engine;

class secureSerial{
	public static $_salt = ";0:1,lAur@]9ç";

	public static function serialize($input)
	{
		$output = array();
		$output[0] = serialize($input);
		$output[1] = password_hash(self::$_salt. $output[0] .self::$_salt , PASSWORD_BCRYPT );
		return json_encode($output);
	}

	public static function unserialize($input)
	{
		$input = json_decode($input);
		if(password_verify( self::$_salt . $input[0] . self::$_salt , $input[1]))
		{
			return unserialize($input[0]);
		}
		return false;
	}
}
