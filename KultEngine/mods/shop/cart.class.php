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

abstract class cart
{
    use CoreElementTrait;
    public static $_cart_life_time = 60 * 60 * 24 * 7; //1 week
    public static $_cart = [];
    public static $_cart_location; //id du magasin
    private static $_serialiser = null;

    public static function setter()
    {
        if (!isset($_SESSION['cart_location'])) {
            $d = new daoGenerator(new magasin());
            $l = $d->get_all();
            if (count($l) != 1) {
                foreach ($l as $key) {
                    if ($key->_pays == text::getLang() || (!isset($_SESSION['cart_location']) && $key->_pays == text::$_default)) {
                        $_SESSION['cart_location'] = $key->_id;
                    }
                }
            } else {
                $_SESSION['cart_location'] = $l[0]->_id;
            }
        }
        self::$_cart_location = $_SESSION['cart_location'];
        self::$_serialiser = new secureSerial();
        if (!isset($_COOKIE['cart'])) {
            self::save_cart();
        } else {
            self::load_cart();
        }
    }

    public static function save_cart()
    {
        setcookie('cart', self::$_serialiser->serialize(self::$_cart), time() + self::$_cart_life_time, constant('htmlpath'));
    }

    public static function load_cart()
    {
        self::$_cart = self::$_serialiser->unserialize($_COOKIE['cart']);
    }

    public static function add_to_cart($item)
    {
        self::$_cart[count(self::$_cart)] = $item;
        self::save_cart();
    }

    public static function remove_from_cart($item)
    {
        foreach (self::$_cart as $k => $key) {
            if ($item == $key) {
                unset(self::$_cart[$k]);
                break;
            }
        }
        self::save_cart();
    }

    public static function get_total()
    {
        $x = 0;
        $d = new daoGenerator(new product());
        foreach (self::$_cart as $key) {
            $o = $d->select($key);
            $x += $o->get_cost();
        }

        return $x;
    }
}
