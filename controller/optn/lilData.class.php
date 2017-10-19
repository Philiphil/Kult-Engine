<?php

namespace kult_engine;

class lilData implements \ArrayAccess{
		public $_datas=[];
		public $_name="";

		public function __construct($n="main"){
			$this->_name=$n;
			if($t=unserialize(file_get_contents(constant("tmppath") . $name . ".fat")))
			{
				$this->_data = $t->_data;
			}
		}

		public function offsetSet($offset, $value) {
			$this->_datas[$offset] = $value;
		}

		public function offsetExists($offset) {
			return isset($this->_datas[$offset]);
		}

		public function offsetUnset($offset) {
			unset($this->_datas[$offset]);
		}

		public function offsetGet($offset) {
			return $this->_datas[$offset;
		}

		public function save(){
			file_put_contents(constant("tmppath") . $this->_name . ".lil", serialize($this));
		}
	}
