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

abstract class membre
{
    use singleton;
    use debuggable;
    use settable;
    use injectable;

    public static $_id = null;
    public static $_log = 0;
    public static $_token_1 = null;
    public static $_token_2 = null;

    public static function setter()
    {
        if (isset($_SESSION['log']) && password_verify($_SERVER['HTTP_USER_AGENT'], $_SESSION['token_1']) && password_verify("$_S7aTic_:p=rm@tK3y;", $_SESSION['token_2'])) {
            self::$_id = intval($_SESSION['id']);
            self::$_log = intval($_SESSION['log']);
            self::$_token_1 = $_SESSION['token_1'];
            self::$_token_2 = $_SESSION['token_2'];
        } else {
            self::destroy();
        }
    }

    public static function destroy()
    {
        unset($_SESSION['id']);
        unset($_SESSION['log']);
        unset($_SESSION['token_1']);
        unset($_SESSION['token_2']);
    }

    public static function login_required()
    {
        if (!isset($_SESSION['log'])) {
            if (!self::is_on_login_page()) {
                redirect(constant('htmlpath').'connexion.php', 0);
                die;
            }
        }
    }

    public static function is_on_login_page()
    {
        $page_de_connexion = 'connexion.php';

        return substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], constant('filespace')) + 1) === $page_de_connexion;
    }

    public static function connexion($id)
    {
        $_SESSION['id'] = $id;
        $_SESSION['log'] = 1;
        $_SESSION['token_1'] = password_hash($_SERVER['HTTP_USER_AGENT'], PASSWORD_BCRYPT);
        $_SESSION['token_2'] = password_hash("$_S7aTic_:p=rm@tK3y;", PASSWORD_BCRYPT);
        self::$_id = intval($_SESSION['id']);
        self::$_log = 1;
        self::$_token_1 = password_hash($_SERVER['HTTP_USER_AGENT'], PASSWORD_BCRYPT);
        self::$_token_2 = password_hash("$_S7aTic_:p=rm@tK3y;", PASSWORD_BCRYPT);

        return 1;
    }

    public static function dont_wait()
    {
        session_write_close();
    }
}
