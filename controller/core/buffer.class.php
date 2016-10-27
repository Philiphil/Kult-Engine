<?php

namespace kult_engine;

abstract class buffer
{
	use debuggable;
    use singleton;
    use settable;
    use injectable;
	public static $_is_buffering_on = 0;
	private static $_auto_executor = null;

	public static function setter()
	{
		self::store();
		self::$_auto_executor = new buffer_executor();
	}

	public static function store()
	{
		if(!self::$_is_buffering_on)
		{
			self::$_is_buffering_on = 1;
			mb_http_output("UTF-8");
			ob_start("mb_output_handler");
		}
	}

	public static function get()
	{
		if(self::$_is_buffering_on)
		{
			self::$_is_buffering_on = 0;
			return ob_get_clean();
		}
	}

	public static function delete()
	{
		if(self::$_is_buffering_on)
		{
			self::$_is_buffering_on = 0;
			ob_clean();
		}
	}

	public static function send()
	{
		if(self::$_is_buffering_on)
		{
			self::$_is_buffering_on = 0;
			ob_end_flush();
		}
	}
}

class buffer_executor
{
	public function __destruct()
	{
		buffer::send();
	}
}