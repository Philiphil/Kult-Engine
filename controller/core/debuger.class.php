<?php
	namespace kult_engine;

	use kult_engine\singleton;
	use kult_engine\debugable;


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