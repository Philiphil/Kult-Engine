<?php

namespace kult_engine;

abstract class cart
{
    use coreElement;
	public static $_cart_life_time=60*60*24*7;//1 week
	public static $_cart=[];
	public static $_cart_location;//id du magasin
	private static $_serialiser=null;

	public static function setter()
    {
        if(!isset($_SESSION["cart_location"])){
            $d = new daoGenerator(new magasin());
            $l = $d->get_all();
            if(count($l)!=1){
                foreach ($l as $key) {
                    if($key->_pays == text::get_lang() || (!isset( $_SESSION["cart_location"]) && $key->_pays == text::$_default)){
                         $_SESSION["cart_location"]= $key->_id;
                    }
                }
            }else{ $_SESSION["cart_location"]=$l[0]->_id;}
        }
        self::$_cart_location = $_SESSION["cart_location"];
    	self::$_serialiser = new secureSerial();
        if(!isset($_COOKIE["cart"]))
        {
			self::save_cart();
        }else{
        	self::load_cart();
        }
    }

    public static function save_cart()
    {
    	setcookie("cart",self::$_serialiser->serialize(self::$_cart), time()+self::$_cart_life_time, constant("htmlpath"));
    }

    public static function load_cart()
    {
    	self::$_cart = self::$_serialiser->unserialize($_COOKIE["cart"]);
    }

    public static function add_to_cart($item)
    {
    	self::$_cart[count(self::$_cart)] = $item;
		self::save_cart();
    }

    public static function remove_from_cart($item)
    {
    	foreach (self::$_cart as $k => $key) {
    		if($item == $key){
             unset(self::$_cart[$k]);
             break;
         }
    	}
		self::save_cart();
    }

    public static function get_total()
    {
    	$x = 0;
        $d = new daoGenerator(new product());
    	foreach (self::$_cart as $key) {
            $o = $d->select($key);
    		$x += $o->get_cost();
    	}
    	return $x;
    }
}