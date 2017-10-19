<?php

namespace kult_engine;

class fatData
{
	public $_data;
	public $_timestamp;

	public function __construct($data=null, $time=null){
		$this->_data = $data;
		$this->_timestamp= is_null($time) ? time() : $time;
	}

	public function set($name)
	{
		file_put_contents(constant("controllerpath")."tmp".constant("filespace") . $name . ".fat", json_encode($this));
	}

	static function get($name)
	{
		$v= json_decode(file_get_contents(constant("controllerpath")."tmp".constant("filespace") . $name . ".fat"), true);
		return new fatData($v["_data"],$v["_timestamp"]);
	}

	public function __invoke($data){
		$this->_data = $data;
		$this->_timestamp= time();
	}



}


