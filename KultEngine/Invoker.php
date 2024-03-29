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

use KultEngine\Core\AbstractInvoker;

abstract class Invoker extends AbstractInvoker
{
    public static function requireBase(?array $ext = null)
    {
        parent::_requireBase();
        // set_error_handler(__NAMESPACE__.'\Core\AbstractInvoker::error');

        require_once constant('corepath').'Logger.php';

        require_once constant('rqrdpath').'i18n.php';
        require_once constant('corepath').'Text.php';

        require_once constant('corepath').'Buffer.php';

        Text::init();

        self::require_mods($ext);
        self::require_local_model();
        self::require_vendor();
        self::require_local_controler();
        self::require_impt();
    }

    public static function analytics()
    {
        self::_requireBase();
        require_once constant('corepath').'Analytics.php';
        Analytics::init();
    }
}
