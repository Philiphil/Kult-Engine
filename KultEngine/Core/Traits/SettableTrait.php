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
 * @copyright Copyright (c) 2016-2021, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine;

trait SettableTrait
{
    private static bool $set = false;

    abstract public static function setter();

    abstract public static function setter_conf($fnord);

    public static function init($fnord = null)
    {
        if (!self::$set) {
            self::$set = true;
            $r = is_null($fnord) ? self::setter() : self::setter_conf($fnord);
            if (in_array(
                "KultEngine\Core\Hook\HookableTrait",
                class_uses(get_called_class())
            )
            ) {
                self::hook();
            }

            return $r;
        }
        trigger_error(get_called_class().' ALREADY SET');
    }

    public static function uninit(): bool
    {
        if (self::$set) {
            self::$set = false;

            return false;
        }
        trigger_error(get_called_class().' NOT SET YET');

        return true;
    }

    public static function init_required(): bool
    {
        if (!self::$set) {
            trigger_error(get_called_class().' NOT SET', E_USER_ERROR);

            return false;
        }

        return true;
    }
}
