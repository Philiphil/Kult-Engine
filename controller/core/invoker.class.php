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

abstract class invoker extends invokerFactory
{
    public static function require_basics($ext = null)
    {
        self::require_base();

        set_error_handler(__NAMESPACE__.'\invoker::error');
        require_once constant('corepath').'hook.class.php';

        require_once constant('corepath').'logger.class.php';

        require_once constant('imptpath').'lang.php';
        require_once constant('corepath').'text.class.php';

        require_once constant('corepath').'buffer.class.php';

        require_once constant('abstpath').'session.factory.php';

        require_once constant('corepath').'inquisitor.class.php';

        require_once constant('abstpath').'connector.factory.php';
        hook::init();
        text::init();

        buffer::init();
        if(constant("debug"))buffer::send();
        inquisitor::init();

        self::require_impt();

        self::require_mods($ext);
        self::require_local_model();
        self::require_vendor();
        self::require_local_controler();

    }

    public static function analytics()
    {
        self::require_base();
        require_once constant('corepath').'analytics.class.php';
        analytics::init();
    }
}
