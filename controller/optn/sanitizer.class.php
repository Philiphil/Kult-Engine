<?php

namespace kult_engine;

class sanitizer
{
	public $_var = false;

	public function __invoke($fnord)
	{
		$this->_var = $fnord;
		return $this;
	}

	public function out()
	{
		return $this->_var;
	}

	public function int()
	{
		$this->_var = intval($this->_var);
		return $this;
	}

	public function id()
	{
		$this->int();
		$this->_var = $this->_var > 0 ? $this->_var : false;
		return $this;
	}

	public function text()
	{
		$this->_var = htmlspecialchars($this->_var);
		return $this;
	}
}