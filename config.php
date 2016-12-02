<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016
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
 * @copyright Copyright (c) 2016, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace kult_engine;

class config
{
    //html folder's name.
  public static $webfolder = 'www';
  //model & controller folder's name
  // www/model or www\model should be specified if the folder is in the webfolder
  //protip : they shouldnt
  public static $modelfolder = 'model';
    public static $controllerfolder = 'controller';
  // HTML root folder's name
  public static $htmlfolder = '/';
  //is config.php inside webfolder ?
  //protip : it shouldnt
  public static $config = 0;

  //Is the webserveur 'linux' or 'windows' ?
  //if neither, have in mind that windows means c:\user
  //and linux means /root/user
  //the question is SHOULD I USE A SLASH OR AN ANTI SLASH
  public static $systeme = 'linux';
  //SQL IDs
  public static $host = '';
    public static $db = '';
    public static $user = '';
    public static $pass = '';
  //SHOULD THE WEBSITE BE IN DEBUG MODE ? 0/1
  public static $debug = 1;

  //full path to your logfile
  public static $log = '';

  //CORE
  public static $file = __FILE__;
}
