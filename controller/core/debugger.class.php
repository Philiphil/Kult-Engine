<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016
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
 * @copyright Copyright (c) 2016, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace kult_engine;

abstract class debugger
{
    use singleton;
    use debuggable;
    use settable;
    use injectable;
    use inquisitable;
    public static $_debug = 1;

    public static function setter()
    {
        self::$_debug = constant('debug');
        set_error_handler(__NAMESPACE__.'\debuger::handler');

        return 0;
    }

    public static function handler($errno, $errstr, $errfile, $errline)
    {
        self::init_required();

        if (!self::$_debug) {
            if ($errno != E_USER_ERROR || $errno != E_ERROR) {
                return;
            }
            echo '<br><b>FATAL</b>';
            die;
        }
        $file = substr($errfile, strripos($errfile, constant('filespace')) + 1);
        $file = substr($file, 0, strpos($file, '.'));
        $status = $errno == E_USER_ERROR || $errno == E_ERROR ? '<b>FATAL</b><br>' : '';

        $saying = $errstr != '' ? $errstr : $errno;
        $saying = contains('(output', $saying) ? substr($saying, 0, strpos($saying, '(output')) : $saying;

        echo '<br> <b>E</b> : '.$saying.'<br>';
        echo 'L : <b>'.$errline.'</b> - F : <b>'.$file.'</b><br>';
        echo $status;

        if (class_exists(__NAMESPACE__.'\\'.$file) && in_array(__NAMESPACE__.'\\'.'debugable', class_uses(__NAMESPACE__.'\\'.$file))) {
            $e = new \ReflectionClass(__NAMESPACE__.'\\'.$file);
            $f = $e->getMethod('debug');
            $f->invoke(null);
        }
        if ($errno == E_USER_ERROR || $errno == E_ERROR) {
            if ($errno == E_USER_ERROR) {
                self::inquisite('flag');
            }
            die;
        }
    }
}
