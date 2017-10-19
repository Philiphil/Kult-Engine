<?php

namespace kult_engine;

class cartService extends webService
{
    function __construct()
    {
        parent::__construct();

        $this->service("add_item", function($args){
            kult_engine\cart::add_to_cart($args[0]);
            $d = new kult_engine\daoGenerator(new kult_engine\product());
            $v= $d->select($args[0], "_id");
            return ["cost"=>$v->_price,"total"=>kult_engine\cart::get_total()];
        }, "POST");

        $this->service("remove_item", function($args){
            kult_engine\cart::remove_from_cart($args[0]);
            return ["total"=>kult_engine\cart::get_total()];
        }, "POST");
    }
}
