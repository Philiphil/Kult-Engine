<?php

namespace kult_engine;

	class product extends daoableObject{
		public $_name=[];
		public $_price=0;
		public $_description=[];
		public $_tags=[];
		public $_pic=[];
		public $_reduction=0;

		public function get_cost()
		{
			return $this->_price-$this->_reduction*$this->_price/100;
		}
	}

	class magasin extends daoableObject{
		public $_nom=[];
		public $_rue="string";
		public $_ville="string";
		public $_pays="string";
		public $_inventory=[];

		public function is_there($id)
		{
			return $this->_inventory[$id];
		}

		public function can_handle($cart)
		{
			foreach ($cart as $key => $value) {
				if($this->_inventory[$key] < $value) return false;
			}
			return true;
		}
	}

	class shopClient extends daoableObject{
		public $_banned=0;
		public $_rue="string";
		public $_ville="string";
		public $_pays="string";
		public $_mail="string";
		public $_password="string";
		public $_nom="string";
		public $_admin=0;
		public $_prenom="string";

		public function have_address()
		{
			if($this->_rue == "") return false;
			if($this->_ville == "") return false;
			if($this->_pays == "") return false;
			if($this->_prenom == "") return false;
			if($this->_nom == "") return false;
			return true;
		}


	}

	class commande extends daoableObject{
		public $_products=[];
		public $_magasin=0;
		public $_statut=0;
		public $_user="longstring";
		public $_time="string";

		public function from_cart($cart, $user, $magasin)
		{
			$d = new daoGenerator(new product());
			foreach ($cart as $item ) {
				$v = $d->select($item);
				$this->_products[] = json_encode($v);
			}
			$this->_user = json_encode($user);
			$this->_magasin = $magasin;
			$this->_time = time();
		}
	}

function create_user($user, $pass, $conf)
{
	$s = new sanitizer();
	if( $s($user)->mail()->out() == false) return shopText::get_text("wrong_mail");
	if( $pass != $conf) return shopText::get_text("no_same_pwd");
	$v= new shopClient();
	$d = new daoGenerator($v);
	$v->_mail = $user;
	$v->_password = password_hash (  $pass ,  PASSWORD_BCRYPT );
	return $d->set($v);
}

function login_user($user, $pass)
{
	$s = new sanitizer();

	$d = new daoGenerator(new shopClient());
	$u = $d->select($user,"_mail");
	if($u)
	{
		if(password_verify($pass,$u->_password)){
			return $u;
		}
	}
	return shopText::get_text("wrong_pass");
}

function create_commande($user,$cart,$shop)
{
	var_dump($user);
	$c = new commande();
	$d = new daoGenerator($c);
	$c->from_cart($cart, $user, $shop->_id);
	return $d->set($c);
}

function buy_shop($cart,$shop)
{
	$tcart = array_count_values($cart);
	foreach ($shop->_inventory as $key) {
		if (in_array($key, $tcart)) $shop->_inventory[$key] = $shop->_inventory[$key] - $tcart[$key];
	}
	$e = new daoGenerator($shop);
	$e->set($shop);
	return true;
}