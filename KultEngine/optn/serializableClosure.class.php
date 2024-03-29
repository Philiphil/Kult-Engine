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
 * @copyright Copyright (c) 2016-2222, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine;

class serializableClosure
{
    public $_val = '';
    private $_closure = null;
    private $_reflection = null;
    public $_vars = [];

    public function __construct($o)
    {
        $this->setter($o);
    }

    public function __set($c, $p)
    {
        if ($c == '_closure') {
            $this->setter($p);
        }
    }

    private function setter($c)
    {
        $this->_closure = $c;
        $this->_reflection = new \ReflectionFunction($c);
        $this->_val = $this->_fetchCode();
        $this->_vars = $this->_fetchUsedVariables();
    }

    public function __get($c)
    {
        if ($c == '_closure') {
            return $this->_closure;
        }
    }

    public function _closure()
    {
        call_user_func_array($this->_closure, $this->_vars);
    }

    public function __sleep()
    {
        return ['_val', '_vars'];
    }

    public function __wakeup()
    {
        $this->_closure = eval('return '.$this->_val.';');
    }

    private function _fetchCode()
    {
        $file = new \SplFileObject($this->_reflection->getFileName());
        $file->seek($this->_reflection->getStartLine() - 1);
        $code = '';
        while ($file->key() < $this->_reflection->getEndLine()) {
            $code .= $file->current();
            $file->next();
        }
        $begin = strpos($code, 'function');
        $end = strrpos($code, '}');
        $code = substr($code, $begin, $end - $begin + 1);

        return $code;
    }

    private function _fetchUsedVariables()
    {
        $use_index = stripos($this->_val, 'use');
        if (!$use_index) {
            return [];
        }
        $begin = strpos($this->_val, '(', $use_index) + 1;
        $end = strpos($this->_val, ')', $begin);
        $vars = explode(',', substr($this->_val, $begin, $end - $begin));
        $static_vars = $this->_reflection->getStaticVariables();
        $used_vars = [];
        foreach ($vars as $var) {
            $var = trim($var, ' $&amp;');
            $used_vars[$var] = $static_vars[$var];
        }

        return $used_vars;
    }

    public function asyncExec()
    {
        $cmd = 'php '.constant('clipath').' async '.base64_encode(serialize($this));
        if (substr(php_uname(), 0, 7) == 'Windows') {
            pclose(popen('start /B '.$cmd, 'r'));
        } else {
            exec($cmd.' > /dev/null &');
        }
    }
}
