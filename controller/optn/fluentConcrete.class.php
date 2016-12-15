<?php

namespace kult_engine;

class fluentConcrete {
	private $i = false;
	private $o = false;
	public function __construct($fnord)
	{
		$this->i = $fnord;
	}
	public function __call($meth, $arg)
	{
			$this->o = call_user_func_array(array($this->i, $meth), $arg);
            return $this;
	}
	public function return()
	{
		return $this->o;
	}
	public function __get($fnord)
	{
		return $this->i->$fnord;
	}
	public function __set($arg,$v)
	{
		$this->i->$arg = $v;
	}
}