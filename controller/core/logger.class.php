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

abstract class logger
{
    use singleton;
    use debuggable;
    public static $_max = 200;

    public static function write($fnord)
    {
        $line = self::get_standard_header();
        self::$_max -= strlen($line);

        $number = round((strlen($fnord) / self::$_max), 0, PHP_ROUND_HALF_UP) + 1;
        $array = [];

        for ($i = 0; $i < $number; $i++) {
            $y = 1 + $i;
            $array[$i] = utf8_to_safe(substr($fnord, 0, self::$_max));
            $fnord = substr($fnord, self::$_max);
        }

        if ($array[count($array) - 1] == '') {
            unset($array[count($array) - 1]);
        }

        $i = 0;
        foreach ($array as $key) {
            $i++;
            $msg = $line.':'.'['.$i.'/'.count($array).']'.$key;
            $syslog = new Syslog();
            $syslog->SetMsg($msg);
            $retour = $syslog->Send();
        }
    }

    public static function get_standard_header()
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

    public static function write_local($fnord)
    {
        $file = constant('controllerpath').'log.kult';
        $line = self::get_standard_header();
        $line .= ':'.$fnord;
        $line .= "\n";
        file_put_contents($file, $line, FILE_APPEND);
    }
}
