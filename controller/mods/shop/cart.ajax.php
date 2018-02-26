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

class cartService extends webService
{
    public function __construct()
    {
        parent::__construct();

        $this->service('add_item', function ($args) {
            kult_engine\cart::add_to_cart($args[0]);
            $d = new kult_engine\daoGenerator(new kult_engine\product());
            $v = $d->select($args[0], '_id');

            return ['cost'=>$v->_price, 'total'=>kult_engine\cart::get_total()];
        }, 'POST');

        $this->service('remove_item', function ($args) {
            kult_engine\cart::remove_from_cart($args[0]);

            return ['total'=>kult_engine\cart::get_total()];
        }, 'POST');
    }
}
