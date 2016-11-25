<?php
namespace kult_engine;

class mlText{
	public $_keys = [];
	public $_textes = [];

	public function __construct($fnord=null)
	{
		if (!is_null($fnord) )
		{
			$this->import($fnord);
		}
	}

	public function add_text($k,$t)
	{
		$this->_textes[$k] = $t;
	}

	public function add_lang($k,$l)
	{
		$l = is_array($l) ? $l : [$l];
		if(isset($this->_keys[$k]))
		{
			foreach ($l as $key) {
				array_push($this->_keys[$k], $key);
			}
		}else{
			$this->_keys[$k] = $l;
		}
	}

	public function export()
	{
		$r = [];
		$r[0] = [];
		$r[1] = [];
		foreach ($this->_keys as $key => $value) {
			$r[0][$key] = $value;
		}
		foreach ($this->_textes as $key => $value) {
			$r[1][$key] = $value;
		}
		return json_encode($r);
	}

	public function import($fnord)
	{
		$fnord = json_decode($fnord);
		foreach ($fnord[0] as $key => $value) {
			$this->_keys[$key] = $value;
		}
		foreach ($fnord[1] as $key => $value) {
			$this->_textes[$key] = $value;
		}
	}

	public function get($lang=null)
	{
		$lang = is_null($lang) ? get_lang() : $lang;
		$k = null;
		foreach ($this->_keys as $key => $value) {
			if(in_array($lang, $value))
			{
				$k = $key;
			}
		}
		$k = is_null($k) ? 0 : $k;
		return $this->_textes[$k];
	}

}