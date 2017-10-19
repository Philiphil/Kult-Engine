<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016-2017
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
 * @copyright Copyright (c) 2016-2017, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace kult_engine;

function timer_init()
{
    $GLOBALS['timer'] = microtime(true);
}
timer_init();

function timer_get()
{
    $return = round((microtime(true) - $GLOBALS['timer']), 5);

    return $return;
}

function hello()
{
    echo '<br>hello world<br>';
}

function redirect($fnord = null, $time = 2)
{
    if (is_null($fnord)) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $fnord = $_SERVER['HTTP_REFERER'];
        } else {
            $fnord = constant('htmlpath').'index.php';
        }
    }

    if (!headers_sent()) {
        header("refresh: $time;url=$fnord");
        exit;
    } else {
        echo '<meta http-equiv="refresh" content="',$time,';url=',$fnord,'">';
    }
}

function echo_br()
{
    echo '<br><br>';
}

function contains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}

function dateIntervalToSeconds($dateInterval)
{
    $reference = new \DateTimeImmutable();
    $endTime = $reference->add($dateInterval);

    return intval($endTime->getTimestamp() - $reference->getTimestamp()) > 0 ? intval($endTime->getTimestamp() - $reference->getTimestamp()) : abs(intval($endTime->getTimestamp() - $reference->getTimestamp()));
}

    function normalize_files($files)
    {
        if (count($files) == 1) {
            $tmp = 0;
            foreach ($files as $key) {
                $tmp = $key;
            }
            $files = $tmp;
        }
        $return = [];
        $i = 0;
        foreach ($files['name'] as $key) {
            $return[$i] = [];
            $return[$i]['name'] = $key;
            $i++;
        }
        $i = 0;
        foreach ($files['type'] as $key) {
            $return[$i]['type'] = $key;
            $i++;
        }
        $i = 0;
        foreach ($files['tmp_name'] as $key) {
            $return[$i]['tmp_name'] = $key;
            $i++;
        }
        $i = 0;
        foreach ($files['error'] as $key) {
            $return[$i]['error'] = $key;
            $i++;
        }
        $i = 0;
        foreach ($files['size'] as $key) {
            $return[$i]['size'] = $key;
            $i++;
        }

        return count($return) > 0 ? $return : $files;
    }
