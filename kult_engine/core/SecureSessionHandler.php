<?php

namespace KultEngine;

use SessionHandler;

class SecureSessionHandler extends SessionHandler
{

    private $key;
    private $cookie=[];

    public function __construct()
    {
        $this->cookie += [
            'lifetime' => 0,
            'path'     => ini_get('session.cookie_path'),
            'domain'   => ini_get('session.cookie_domain'),
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true
        ];

        $this->setup();
    }

    private function setup()
    {
        ini_set("session.use_strict_mode",true);
        ini_set("session.use_cookies",true);
        ini_set("session.use_only_cookies ",true);
        ini_set("session.cookie_httponly",true);
        ini_set("session.use_trans_sid",false);        
        ini_set('session.save_handler', 'files');
        session_save_path(sys_get_temp_dir());


        session_set_cookie_params(
            $this->cookie['lifetime'],
            $this->cookie['path'],
            $this->cookie['domain'],
            $this->cookie['secure'],
            $this->cookie['httponly']
        );
    }

    public function forget()
    {
        if (session_id() === '') return false;
        $_SESSION = [];
        setcookie(
            "",
            '',
            time() - 42000,
            $this->cookie['path'],
            $this->cookie['domain'],
            $this->cookie['secure'],
            $this->cookie['httponly']
        );

        return session_destroy();
    }

    public function start()
    {
        return session_id() === '' && session_start() && mt_rand(0, 4) === 0 ? $this->refresh() : true;
    }

    public function refresh()
    {
        return session_regenerate_id(true);
    }

    public function close()
    {
        return session_write_close();
    }

    public function open($save_path, $session_name)
    {
        $this->key = $this->getKey('KEY_' . $session_name);
        return parent::open($save_path, $session_name);
    }

    public function read($id)
    {
        return mcrypt_decrypt(MCRYPT_3DES, $this->key, parent::read($id), MCRYPT_MODE_ECB);
    }

    public function write($id, $data)
    {
        return parent::write($id, mcrypt_encrypt(MCRYPT_3DES, $this->key, $data, MCRYPT_MODE_ECB));
    }


    private function getKey($name)
    {
        if (empty($_COOKIE[$name])) {
            $key         = random_bytes(64);
            $cookieParam = session_get_cookie_params();
            $encKey      = base64_encode($key);
            setcookie(
                $name,
                $encKey,
                ($cookieParam['lifetime'] > 0) ? time() + $cookieParam['lifetime'] : 0,
                $cookieParam['path'],
                $cookieParam['domain'],
                $cookieParam['secure'],
                $cookieParam['httponly']
            );
            $_COOKIE[$name] = $encKey;
        } else {
            $key = base64_decode($_COOKIE[$name]);
        }
        return $key;
    }
}
