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

class eventLoop
{
    public $_max_thread = 2;
    public $_events = [];
    public $_queue = [];

    public function loop()
    {
        set_time_limit(0);
        ob_implicit_flush();
        while (true) {
            foreach ($this->_events as $key) {
                if (get_class($key) == 'netEvent') {
                    $key->listen();
                }
                if (get_class($key) == 'event' && call_user_func($key->_listenner)) {
                    call_user_func_array($key->_callback, $key->_args);
                }
            }
        }
    }
}

class event
{
    public $_listenner = null; //callable
    public $_callback = null; //callable
    public $_args = [];
}

class netEvent extends event
{
    public $_port = null;
    public $_socket = null;
    private $_bfr = 1024;

    public function prepare_socket()
    {
        $this->_socket = socket_create_listen($this->_port);
        socket_set_nonblock($this->_socket);
    }

    public function listen()
    {
        if ($this->_socket === null) {
            $this->prepare_socket();
        }
        $s = @socket_accept($this->_socket);
        if ($s !== false) {
            $v = call_user_func_array($this->_callback, $this->_args);
            while (strlen($v) > 0) {
                $this->_bfr = strlen($v) > 12 ? 12 : strlen($v);
                $d = substr($v, 0, $this->_bfr);
                $v = substr($v, $this->_bfr);
                socket_write($s, $d, strlen($d));
            }
            socket_close($s);
        }
    }

    public function __destruct()
    {
        socket_close($this->_socket);
    }
}

  function my_ip($dest = '8.8.8.8', $port = 80)
  {
      $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
      socket_connect($socket, $dest, $port);
      socket_getsockname($socket, $addr, $port);
      socket_close($socket);

      return $addr;
  }

define('ip', isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : my_ip());

$n = new netEvent();
$n->_port = '8888';
$n->_callback = function () {
    $date = date();
    $header = "Date:$date\nHTTP/1.1 200 OK\nContent-type: text/html; charset=UTF-8\n\n";

    return $header.'this';
};

$e = new netEvent();
$e->_port = '8889';
$e->_callback = function () {
    return 'this';
};

$l = new eventLoop();
$l->_events[] = $n;
$l->_events[] = $e;
$l->loop();

/*
TODO faire version async ?
preciser version sync ?


function closure_to_str($func)
{
    $refl = new \ReflectionFunction($func); // get reflection object
    $path = $refl->getFileName();  // absolute path of php file
    $begn = $refl->getStartLine(); // have to `-1` for array index
    $endn = $refl->getEndLine();
    $dlim = "\n"; // or PHP_EOL
    $list = explode($dlim, file_get_contents($path));         // lines of php-file source
    $list = array_slice($list, ($begn-1), ($endn-($begn-1))); // lines of closure definition
    $last = (count($list)-1); // last line number

    if((substr_count($list[0],'function')>1)|| (substr_count($list[0],'){')>1) || (substr_count($list[$last],')}')>1))
    { throw new \Exception("Too complex context definition in: `$path`. Check lines: $begn & $endn."); }

    $list[0] = ('function'.explode('function',$list[0])[1]);
    $list[$last] = (explode('}',$list[$last])[0].'}');

    return implode($list,$dlim);
}

function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r"));
    }
    else {
        exec($cmd . " > /dev/null &");
    }
}

system("yourCommandName 2>&1",$output) ;
