<?php

namespace kult_engine;

class webService
{
    public $_req;
    public $_args;
    public $_method;
    public $_token;
    public $_func = [];

    function __construct()
    {
        $this->_req  = isset($_POST["req"]) ? $_POST["req"] : (isset($_GET["req"]) ? $_GET["req"] : false);
        $this->_args  = isset($_POST["args"]) ? json_decode($_POST["args"],true) : (isset($_GET["args"]) ? json_decode($_GET["args"],true) : false);
        $this->_token  = isset($_POST["token"]) ? $_POST["token"] : (isset($_GET["token"]) ? $_GET["token"] : false);
        $this->_method = $_SERVER['REQUEST_METHOD'];

    }

    function send($array=[])
    {
        $array["token"] = $this->_token;
        echo json_encode($array);
    }

  
    function execute()
    {
        if( isset($this->_func[$this->_method][$this->_req]) ){
            $this->send( $this->_func[$this->_method][$this->_req]($this->_args) );
        }
    }

    function service($a,$c,$t="POST")
    {
        $this->_func[$t][$a] = $c;
    }
}
