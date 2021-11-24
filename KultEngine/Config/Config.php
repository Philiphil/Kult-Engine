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

namespace KultEngine\Config;

class Config
{
    //html folder's name.
    public static $webFolder = 'www';
    //local code folder compared to Config.php or fullpath
    public static $localCodeFolder = 'local';

    //kult_engine folder compared to Config.php or fullpath
    public static $kult_engineFolder = 'KultEngine';

    // HTML root folder's name
    public static $htmlFolder = '';

    //SQL IDs
    public static $host = 'localhost:3301';
    public static $db = 'test';
    public static $user = 'user';
    public static $pass = '';
    public static $driver = 'mysql';

    //SHOULD THE WEBSITE BE IN DEBUG MODE ? true/false
    public static $debug = true;

    //full path to your logfile
    public static $log = '';
    public static $defaultLang = 'en';
    public static $serverLang = ['en', 'fr'];
    public static $loginPage = '';

    //Does multiple site run on the same kult engine ?  true/false
    // localCodeFolder should be in webfolder then.
    public static $multi = false;

    public static $file = __FILE__;
}