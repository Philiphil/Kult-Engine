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

trait DebuggableTrait
{
    public static function debug()
    {
        echo '<br>DEBUG :: '.get_called_class().'<br>';
        $reflection = new \ReflectionClass(get_called_class());
        $vars = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
        $_vars = [];
        foreach ($vars as $var) {
            array_push($_vars, $var->name);
        }
        $vars = $reflection->getProperties(\ReflectionProperty::IS_PROTECTED);
        foreach ($vars as $var) {
            array_push($_vars, $var->name);
        }
        foreach (get_class_vars(get_called_class()) as $key => $value) {
            if (!in_array($key, $_vars)) {
                echo $key.'->';
                $bfr = is_array($value) || is_object($value) ? $value : htmlentities($value);
                var_dump($bfr);
                echo '<br>';
            }
        }
        echo 'END<br>';
    }
}
