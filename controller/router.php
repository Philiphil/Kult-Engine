<?php

	class k_rest{
		public static $_a_asked;
		public static $_asked;
		public static $_method;
		public static $_route;
		public static $_global_route;
		public static $_error;
		public static $_argex = '|<!';
		private static $_auto_executor;
		public static $_global_routing = 1;

		public static function setter()
		{
			k_rest::$_method = strtoupper($_SERVER['REQUEST_METHOD']);
			k_rest::$_asked = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']));
			k_rest::$_a_asked = k_rest::read_asked(k_rest::$_asked);
			k_rest::$_error = is_array(k_rest::$_a_asked) ? 1 : 0;
			k_rest::$_route = array();			
			k_rest::$_auto_executor = new k_rest_executor();
		}

		public static function read_asked($brut)
		{
			if(substr($brut,0,1) !== '/')
			{
				return;
			}
			$brut = substr($brut, 1);
			if(!contains('/', $brut))
			{
				return $brut != "" ? [$brut] : ['*'];
			}
			$array = explode('/', $brut);

			if($array[count($array)-1] === "")
			{
				unset($array[count($array)-1]);
			}
			return $array;
		}

		public static function set_route($route,$func,$method='GET')
		{
			k_rest::$_route[count(k_rest::$_route)] = [$route,$func,$method];
		}

		public static function exec()
		{

			if(k_rest::$_global_routing)
			{
				k_rest::disable_global_routing();
				foreach (k_rest::$_global_route as $route)
				{
					k_rest::exec_route($route);
				}
			}

			foreach (k_rest::$_route as $route)
			{
				k_rest::exec_route($route);
			}

		}

		public static function exec_route($route)
		{
			if($route[2] == k_rest::$_method)
				{
					$tmp = k_rest::is_route_applicable($route[0]);
					if($tmp !== 0)
					{
						call_user_func_array($route[1],$tmp);
					}
			}
		}

		public static function disable_global_routing($bool=0)
		{
			k_rest::$_global_routing = $bool;
		}



		public static function is_route_applicable($route)
		{
			$translated_route = k_rest::read_asked($route);
			$args = array();

			if(count($translated_route) > count(k_rest::$_a_asked) &&  (count($translated_route)-1 == count(k_rest::$_a_asked) && $translated_route[count($translated_route)-1] != '*'))
			{
				# if route is longuer than uri, route is probably not applicable
				#and if route is just 1 arg longuer than uri, this arg has to be *
				return 0;
			}

			for ($i=0; $i < count($translated_route) ; $i++)
			{ 
				if($translated_route[$i] != '*' && !contains(k_rest::$_argex, $translated_route[$i]) && $translated_route[$i] != k_rest::$_a_asked[$i])
				{
					return 0;
				}
				if(contains(k_rest::$_argex, $translated_route[$i]))
				{
					$args[intval(substr($translated_route[$i], strlen(k_rest::$_argex)))] = k_rest::$_a_asked[$i];
				}
			}



			return $args;
		}
	}


	class k_rest_executor
	{
	  public function __destruct()
	  {
	    k_rest::exec();
	  }
	}

	class global_route
	{
		public $_route;

		public function __construct($route,$func,$method='GET')
		{
			k_rest::$_global_route[count(k_rest::$_global_route)] = [$route,$func,$method];
		}

	}