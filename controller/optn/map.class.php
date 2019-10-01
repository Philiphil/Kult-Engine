<?php

namespace kult_engine;

class map extends vector  {
	private $_keys=[];

		public function offsetUnset($key) {
		    if(is_int($key)){
    			for ($i=$key; $i < $this->count()-1 ; $i++) { 
    				$this->_container[$this->_keys[$i]] = $this->_container[$this->_keys[$i+1]];
    			    $this->_keys[$i] = $this->_keys[$i+1];
    			}
                unset($this->_container[$this->_keys[$this->count()-1]]);
                unset($this->_keys[$this->_keys[$this->count()-1]]);
            }else{
                unset($this->_container[$key]);
			     unset($this->_keys[array_search($key,$this->_keys)]);
            }
		
		}

		public function offsetGet($key) {
			return isset($this->_container[$key]) ? $this->_container[$key] : null;
		}

		public function offsetSet($key, $value) {
		    if (is_array($value)) $value = new self($value);//
		    if ($key === null) {
		        $this->_container[] = $value;
		        $this->_keys[] = array_key_last($this->_container);
		    } else {
		        $this->_container[$key] = $value;
		        if(!in_array($key, $this->_keys)) $this->_keys[] = $key;
		    }
		}

		public function front(){
			return $this[$this->_keys[0]];
		}
		public function back(){
			return $this[$this->_keys[$this->count()-1]];
		}
		public function pop_back(){
			$this->erase($this->_keys[$this->count()-1]);
		}
		public function pop_front(){
			$this->erase($this->_keys[0]);
		}

		public function insert($key,$value){
			if(is_int($key)){
				return parent::insert($key,$value);
			}
			if (is_array($value)) $value = new self($value);//
			$this[$key]=$value;
		}
}