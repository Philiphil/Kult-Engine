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

class product
{
    public $_id;
    public $_name;
    public $_price;
    public $_description;
    public $_nombre;
    public $_tags = [];
    public $_pic = [];
    public $_reduction;

    public function get_cost()
    {
        return $this->_price - $this->_reduction * $this->_price / 100;
    }
}

    class magasin
    {
        public $_id;
        public $_name;
        public $_location;
        public $_inventory;
    }

    class user
    {
        /*
            todo
        */
        public $_id;
        public $_rue;
        public $_ville;
        public $_pays;
        public $_mail;
        public $_password;
        public $_nom;
        public $_prenom;
    }

/*
cart sauvegardé dans short cookie reinit regulierement
user can be admin soon

*/
