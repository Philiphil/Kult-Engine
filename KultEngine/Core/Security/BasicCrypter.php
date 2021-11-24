<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Kult Engine
 * @author Théo Sorriaux (philiphil)
 * @copyright Copyright (c) 2016-2020, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine\Core\Security;

class BasicCrypter
{
    public $_key;
    public $_salt;
    public $_iv;
    public $_cipher = 'AES-256-CBC';

    public function __construct($key = 'D3f4ultKey!')
    {
        $this->_salt = random_bytes(32);
        $this->_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->_cipher));
        $this->generate_key($key);
    }

    public function generate_key($key)
    {
        $this->_key = hash_pbkdf2('sha256', $key, $this->_salt, 10000, 32);
    }

    public function encrypt($txt)
    {
        $return = [];
        $return['iv'] = base64_encode($this->_iv);
        $return['salt'] = base64_encode($this->_salt);
        $return['value'] = base64_encode(openssl_encrypt($txt, $this->_cipher, $this->_key, 0, $this->_iv));

        return json_encode($return);
    }

    public function decrypt($json, $key = 'D3f4ultKey!')
    {
        $obj = json_decode($json, true);
        $this->_iv = base64_decode($obj['iv']);
        $this->_salt = base64_decode($obj['salt']);
        $this->generate_key($key);
        $h = base64_decode($obj['value']);

        return openssl_decrypt($h, $this->_cipher, $this->_key, 0, $this->_iv);
    }
}
