<?php

namespace kult_engine;

	class uploadHelper{
		public $_authorize_extentions = 0;
		public $_convertion = 0;
		public $_max_size = 0;
		public $_min_size = 0;
		public $_dest_folder = 0;
		public $_file = 0;
		public $_name = 0;
		public $_extention = 0;
		public $_fullpath = 0;


		public function __construct($fnord)
		{
			$this->_file = $fnord;
			$e=  strrchr($fnord['name'], '.');
			$this->_extention = strtolower(  substr(  $e ,1)  );
			$this->_name = substr($fnord['name'], 0, strrpos($fnord['name'], '.'));
			$this->_dest_folder = $this->default_dest_folder();
			$this->_max_size = 10*1024*1024;
		}


		public function run()
		{
			if(!$this->verif()) return false;
			$this->_extention = $this->_convertion ? $this->_convertion : $this->_extention;
			$this->_fullpath = $this->_dest_folder . $this->_name ."." .$this->_extention ; 
   			return move_uploaded_file($this->_file['tmp_name'], $this->_fullpath );
		}


		public function verif()
		{
			if($this->_authorize_extentions != 0)
			{
				if(is_array($this->_authorize_extentions))
				{
					if(!in_array($this->_extention, $this->_authorize_extentions)) return false;
				}else{
					if( $this->_extention != $this->_authorize_extentions) return false;
				}
			}
			if($this->_min_size != 0 && $this->_file['size'] < $this->_min_size) return false;
			if($this->_max_size != 0 && $this->_file['size'] > $this->_max_size) return false;
			if(!$this->dest_op()) return false;
			return 1;
		}

		public function default_dest_folder()
		{
			return constant('controllerpath'). 'tmp' . constant('filespace');
		}


		public function dest_op($fail=0)
		{
			$i = 1;
			if(!$this->dest_exists())
			{
				$i = $this->_dest_folder == $this->default_dest_folder() ? mkdir(substr($this->_dest_folder, 0, strlen($this->_dest_folder)-1),0777) : mkdir($this->_dest_folder,0777);
			}
			$j = chmod($this->_dest_folder, 0777);
			if((!$i && !$j ) && config::$systeme == "linux" && !$fail)
			{
				exec("su chmod -R 777 ".constant("basepath"));
				return $this->dest_op(1);
			}
			return $i;
		}

		public function dest_exists()
		{
			return is_dir($this->_dest_folder);
		}


	}