<?php
namespace kult_engine;

abstract class inquisitor
{
	use singleton;
	use debuggable;
	use settable;
	use injectable;
	public static $_tempo = array();
	public static $_watcher=0;
	public static $_flag=0;
	public static $_deny=0;

	public static function setter()
	{
		inquisitor::$_watcher = isset($_SESSION['watcher']) ? intval($_SESSION['watcher']) : 0;
		inquisitor::$_flag = isset($_SESSION['flag']) ? intval($_SESSION['flag']) : 0;
		inquisitor::$_deny = isset($_SESSION['deny']) ? intval($_SESSION['deny']) : 0;
		inquisitor::$_tempo = isset($_SESSION['tempo']) ? $_SESSION['tempo'] : array();
		inquisitor::compute();
	}

	public static function save()
	{
		$_SESSION['watcher'] = inquisitor::$_watcher;
		$_SESSION['flag'] = inquisitor::$_flag;
		$_SESSION['deny'] = inquisitor::$_deny;
		$_SESSION['tempo'] = inquisitor::$_tempo;
	}

	public static function compute()
	{
		if(isset(inquisitor::$_tempo['time']))
		{
			if(time() - inquisitor::$_tempo['time'] > 60*5)
			{
				unset(inquisitor::$_tempo['time']);
				unset(inquisitor::$_tempo['flags']);
			}else{
				inquisitor::$_watcher += inquisitor::$_tempo['flags']/3 > 1  ? intval(round(inquisitor::$_tempo['flags']/3)) : 0;
				inquisitor::$_tempo['flags'] = intval(round(inquisitor::$_tempo['flags']))/3 > 1 ? inquisitor::$_tempo['flags']%3 : inquisitor::$_tempo['flags'];
			}
		}
		inquisitor::$_flag += inquisitor::$_watcher/3 > 1  ? intval(round(inquisitor::$_watcher/3)) : 0;
		inquisitor::$_watcher= inquisitor::$_watcher/3 > 1 ? inquisitor::$_watcher%3 : inquisitor::$_watcher;

		inquisitor::$_deny = inquisitor::$_flag >= 5 ? 1 : 0;

		inquisitor::save();
		if(inquisitor::$_deny)
		{
			echo 'Inquisit\'d';
			die;
		}
	}

	public static function add_tmp()
	{
		inquisitor::$_tempo['time'] = time();
		inquisitor::$_tempo['flags'] = isset(inquisitor::$_tempo['flags']) ? inquisitor::$_tempo['flags']+1: 1;
		sleep(1);
		inquisitor::compute();
	}

	public static function add_watcher()
	{
		inquisitor::$_watcher++;
		inquisitor::compute();
	}


	public static function add_flag()
	{
		inquisitor::$_flag++;
		inquisitor::compute();
	}

	public static function add_deny()
	{
		inquisitor::$_deny++;
		inquisitor::compute();
	}
}