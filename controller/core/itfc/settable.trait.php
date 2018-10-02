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

namespace kult_engine;

trait settable
{
    protected static $set = 0;

    abstract public static function setter();

    abstract public static function setter_conf($fnord);

    public static function init($fnord = null)
    {
        if (!static::$set) {
            static::$set = !static::$set;
            $r = is_null($fnord) ? static::setter() : static::setter_conf($fnord);
            if (in_array(__NAMESPACE__.'\\hookable', class_uses_deep(static::class)) || in_array('hookable', class_uses_deep(static::class))
            ) {
                static::hook();
            }

            return $r;
        }
        trigger_error(get_called_class().' ALREADY SET');
    }

    public static function uninit()
    {
        if (static::$set) {
            static::$set = !static::$set;

            return 0;
        }
        trigger_error(get_called_class().' NOT SET YET');

        return 1;
    }

    public static function init_required()
    {
        if (!static::$set) {
            trigger_error(get_called_class().' NOT SET', E_USER_ERROR);

            return 0;
        }

        return 1;
    }
}
