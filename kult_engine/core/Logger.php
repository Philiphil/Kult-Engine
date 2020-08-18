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
 * @copyright Copyright (c) 2016-2018, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine;

class Logger
{
    public ?string $_file = null;

    public function __construct($fnord = null)
    {
        $this->_file = $fnord === null ? (constant('logfile') === null || constant('logfile') == '' ? constant('tmppath').constant('view').'.log' : constant('logfile')) : $fnord;
    }

    public function get_standard_header(): string
    {
        $line = '';
        $line .= '['.$_SERVER['HTTP_USER_AGENT'];
        $line .= ']['.$_SERVER['REMOTE_ADDR'];
        $line .= ']';
        $line .= '['.date('d/m/Y-H:i:s', time()).']';
        $line .= '['.$_SERVER['REQUEST_METHOD'].']';
        $line .= '['.$_SERVER['REQUEST_URI'].']';

        return $line;
    }

    public function write_local(?string $fnord = null)
    {
        $line = $this->get_standard_header();
        $line .= ':'.$fnord;
        $line .= "\n";
        file_put_contents($this->_file, $line, FILE_APPEND);
    }
}
