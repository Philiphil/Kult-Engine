<?php

namespace kult_engine;

	class product{
		public $_id;
		public $_name;
		public $_price;
		public $_description;
		public $_nombre;
		public $_tags=[];
		public $_pic=[];
		public $_reduction;

		public function get_cost()
		{
			return $this->_price-$this->_reduction*$this->_price/100;
		}

		
	}

	class magasin{
		public $_id;
		public $_name;
		public $_location;
		public $_inventory;
	}

	class user{
		/*
			todo
		*/
		public $_id;
		public $_rue;
		public $_ville;
		public $_pays;
		public $_mail;
		public $_password;
		public $_nom;
		public $_prenom;

	}

/*
cart sauvegardé dans short cookie reinit regulierement
user can be admin soon

*/