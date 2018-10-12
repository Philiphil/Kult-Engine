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

class config
{
    public static $controllerfolder = 'kult-engine';
    // HTML root folder's name
    public static $htmlfolder = '/';

    //SHOULD THE WEBSITE BE IN DEBUG MODE ? 0/1
    public static $debug = 1;

    //full path to your logfile
    public static $log = '';
    public static $default_lang = 'en';
    public static $server_lang = ['en', 'fr'];
    public static $login_page = '';

    //Does multiple site run on the same kult engine ?
    public static $multi = 0;

    //CORE
    public static $file = __FILE__;
}

require_once substr(config::$file, 0, -10).config::$controllerfolder.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'abst'.DIRECTORY_SEPARATOR.'invoker.abstract.php';
require_once substr(config::$file, 0, -10).config::$controllerfolder.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'invoker.class.php';
