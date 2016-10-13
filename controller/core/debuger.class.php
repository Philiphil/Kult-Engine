<?php
namespace kult_engine;
	use kult_engine\singleton;
	use kult_engine\debugable;

trait debugable{
	public static function debug()
	{
		echo '<br>DEBUG :: '.get_called_class().'<br>';
		$reflection = new \ReflectionClass(get_called_class());
        $vars = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
        $_vars = array();
        foreach ($vars as $var) {
			array_push($_vars, $var->name);
		}
		$vars = $reflection->getProperties(\ReflectionProperty::IS_PROTECTED);
        foreach ($vars as $var) {
			array_push($_vars, $var->name);
		}
		foreach ( get_class_vars(get_called_class()) as $key => $value) {
			if(!in_array($key, $_vars))
			{
				echo $key.'->';
				$bfr = is_array($value) || is_object($value) ? $value : htmlentities($value);
				var_dump($bfr);
				echo '<br>';
			}
		}
		echo 'END<br>';
	}
}

trait singleton{
	private static $instance;
	public static function getInstance()
	{
	    if (null === static::$instance) {
	        static::$instance = new static();
	    }
	    return static::$instance;
	}
	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
}

class debuger{

    use singleton;
    use debugable;
	public static $_debug=1;
	public static function setter()
	{
		debuger::$_debug = constant('debug');
		set_error_handler(__NAMESPACE__ .'\debuger::handler');
		return 0;
	}

	public static function handler($errno, $errstr, $errfile, $errline)
	{
		if(!debuger::$_debug)
		{
			if( $errno != E_USER_ERROR || $errno != E_ERROR )
			{
				return;
			}
			echo '<br><b>FATAL</b>';
			die;
		}
		$file = substr($errfile, strripos($errfile, constant('filespace'))+1);
		$file = substr($file, 0, strpos($file, '.'));
		$status =  $errno == E_USER_ERROR || $errno == E_ERROR ? '<b>FATAL</b><br>' : '';

		$saying = $errstr != '' ?$errstr : $errno;
		$saying = contains('(output', $saying) ?  substr($saying, 0, strpos($saying, '(output')) : $saying;

		echo '<br> <b>E</b> : '.$saying.'<br>';
		echo 'L : <b>'.$errline.'</b> - F : <b>'.$file.'</b><br>';
		echo $status;

		if( class_exists(__NAMESPACE__.'\\'.$file) && in_array(__NAMESPACE__.'\\'.'debugable', class_uses(__NAMESPACE__.'\\'.$file)) )
		{
			$e =  new \ReflectionClass(__NAMESPACE__.'\\'.$file);
			$f = $e->getMethod('debug');
			$f->invoke(null);			
		}
		if( $errno == E_USER_ERROR || $errno == E_ERROR )
		{
			die;
		}
	}
}