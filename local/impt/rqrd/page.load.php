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

class page
{
    public static function standardpage_head(?string $title = null): void
    {
        $title = text::get_text($title);
        if (!is_null($title)) {
            $title .= ' :: ';
        }
        echo '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8" />
                <script>var timer = new Date();</script>
                <title>'.$title.text::get_text('main_title').'</title>
                <meta content="width=device-width, initial-scale=1" name="viewport" />
                <meta name="google-signin-client_id" content="110930602597-hk0icd5fd1cjmr96u93psvsoshgr4puh.apps.googleusercontent.com">
                <link href="'.constant('contentpath').'assets/main.css" rel="stylesheet" type="text/css" />
                <link rel="shortcut icon" href="favicon.ico" />';
        self::standardpage_api();
        self::standardpage_js();
        echo '</head>';
    }

    public static function standardpage_header(): void
    {
        echo '<body>

<div class="g-signin2" data-onsuccess="onSignIn"></div>
';
    }

    public static function standardpage_footer(): void
    {
        echo '
    </body>
    </html>
    ';
    }

    public static function standardpage_api(): void
    {
        //echo '<script src="https://apis.google.com/js/platform.js" async defer></script>';
        //echo '<meta name="google-signin-client_id" content="">';
    }

    public static function standardpage_js(): void
    {
        include constant('rqrdpath').'javascript.php';
        echo'       
        

    <script src="https://apis.google.com/js/platform.js" async defer></script>

        <script src="'.constant('contentpath').'script/jquery.js"></script>
        <script src="'.constant('contentpath').'script/json.js"></script>
        <script src="'.constant('contentpath').'script/ajax.class.js"></script>
        <script src="'.constant('contentpath').'script/cache.class.js"></script>
        <script src="'.constant('contentpath').'script/script.js"></script>
        <script>
            $(document).ready(function(){
                $("#k_loading").addClass("k_invisible");
            });
            </script>
            ';
    }

    public static function standardpage_body_begin(?string $arianne = ''): void
    {
        echo ' <div id="k_loading">
                <br>
                <img src="'.constant('imagepath').'loader.gif" />
            </div>';
    }

    public static function standardpage_body_end(): void
    {
        echo '';
    }
}
