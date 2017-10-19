<?php


namespace kult_engine;

abstract class daoableObject
{
   /* 
   int = 0
   string = "string"
   id = "id"
   array = []
   obj=new obj()
	*/
	public $_id = "id";
	public $_iduniq = "string";

    public function __construct(){
        $this->_iduniq = uniqid();
         foreach($this as $key => $value){
            if($value=="string")$this->$key ="";
        }
    }
}
