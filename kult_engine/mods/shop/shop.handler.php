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
 * @copyright Copyright (c) 2016-2020, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine;

require_once constant('optnpath').'daoableObject.class.php';
require_once constant('optnpath').'daoGenerator.class.php';

define('shoppath', substr(__FILE__, 0, strlen(__FILE__) - 16));
define('shoptemplate', constant('shoppath').'template'.DIRECTORY_SEPARATOR);
define('shopmodel', constant('shoppath').'model'.DIRECTORY_SEPARATOR);

require_once constant('shoppath').'shop.config.php';
define('currency_html', shopConfig::$currency_char);
switch (shopConfig::$currency_name) {
    case 'euro':
        define('currency_pdf', chr(128));
        break;
    case 'usd':
    case 'cad':
    define('currency_pdf', chr(36));
     break;
     case 'gbp':
     define('currency_pdf', chr(163));
     break;
     case 'jpy':
         define('currency_pdf', chr(165));
     break;
    default:
    define('currency_pdf', chr(36));
        break;
}

require_once constant('shoppath').'cart.class.php';
require_once constant('shoptemplate').'shopText.class.php';
require_once constant('shoppath').'shop.class.php';
require_once constant('shopmodel').'model.php';

cart::init();
