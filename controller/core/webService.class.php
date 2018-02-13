<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016-208
 *
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
 * @copyright Copyright (c) 2016-2018, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace kult_engine;

class webService
{
    public $_req;
    public $_args;
    public $_method;
    public $_token;
    public $_func = [];

    public function __construct()
    {
        $this->_req = isset($_POST['req']) ? $_POST['req'] : (isset($_GET['req']) ? $_GET['req'] : false);
        $this->_args = isset($_POST['args']) ? json_decode($_POST['args'], true) : (isset($_GET['args']) ? json_decode($_GET['args'], true) : false);
        $this->_token = isset($_POST['token']) ? $_POST['token'] : (isset($_GET['token']) ? $_GET['token'] : false);
        $this->_method = $_SERVER['REQUEST_METHOD'];
    }

    public function send($array = [])
    {
        $array['token'] = $this->_token;
        echo json_encode($array);
    }

    public function execute()
    {
        if (isset($this->_func[$this->_method][$this->_req])) {
            $this->send($this->_func[$this->_method][$this->_req]($this->_args));
        }
    }

    public function service($a, $c, $t = 'POST')
    {
        $this->_func[$t][$a] = $c;
    }
}
