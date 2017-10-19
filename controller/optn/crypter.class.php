<?php

namespace kult_engine;

class crypter
{

    public $_key;
    public $_salt;
    public $_iv;

    function __construct()
    {
        $this->_salt = random_bytes(32);
        $this->_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
    }

    function generate_key($key)
    {
        $this->_key = hash_pbkdf2 ('sha256',$key,$this->_salt,10000,32);
    }


    function encrypt($txt)
    {
        $return = [];
        $return['iv']= base64_encode($this->_iv);
        $return['salt']= base64_encode($this->_salt);
        $return['txt']= base64_encode( openssl_encrypt($txt, "AES-256-CBC", $this->_key, 0, $this->_iv));
        return json_encode($return);

    }

    function decrypt($json, $key)
    {
        $obj = json_decode( $json, TRUE );
        $this->_iv= base64_decode($obj['iv']);
        $this->_salt= base64_decode($obj['salt']);
        $this->generate_key($key);         
        $h = base64_decode($obj['txt']);
        return openssl_decrypt($h, "AES-256-CBC", $this->_key, 0, $this->_iv);
    }

}
