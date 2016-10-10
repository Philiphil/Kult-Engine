<?php

function timer_init()
{
    $GLOBALS['timer'] = microtime(true);
}
timer_init();

function timer_get()
{
    $return = round(((microtime(true) - $GLOBALS['timer']) * 1000), 1);

    return $return;
}

function is_string_legit($fnord = null, $max = 20, $min = 1, $null = 0)
{
    //$fnord : string
        //$max : taille max
        //null : can be null
        //$min : taille min
    if ($min == 0) {
        $null = 1;
    }
    $int = strlen($fnord);
    if ($int > $max) {
        return 0;
    }
    if (is_null($fnord) && $null == 0) {
        return 0;
    }
    if ($int < $min) {
        return 0;
    }

    return 1;
}

function is_int_legit($fnord = null)
{
    /*
            fnord = int > 0
        */
            $fnord = floatval($fnord);
    if ($fnord < 0 || $fnord == 0) {
        return 0;
    }

    return 1;
}


        function hello()
        {
            echo '<br>hello world<br>';
        }

        function redirect($fnord = null, $time = 2)
        {
            if (is_null($fnord)) {
                if (isset($_SERVER['HTTP_REFERER'])) {
                    $fnord = $_SERVER['HTTP_REFERER'];
                } else {
                    $fnord = constant('htmlpath').'index.php';
                }
            }

            if (!headers_sent()) {
                header("refresh: $time;url=$fnord");
                exit;
            } else {
                echo '<meta http-equiv="refresh" content="',$time,';url=',$fnord,'">';
            }
        }

        function echo_br()
        {
            echo '<br><br>';
        }

        function contains($needle, $haystack)
        {
            return strpos($haystack, $needle) !== false;
        }

        function safest($fnord = null)
        {
            $fnord = strip_tags($fnord);
            $fnord = htmlentities($fnord);

            $forbiden = [
                '<script',
                ];
            $nul = [];
            $subject = str_replace($forbiden, $nul, $fnord);

            return $fnord;
        }

        function is_safest($fnord = null)
        {
            $forbiden = [
                '<script',
                ];
            $nul = [];
            str_replace($forbiden, $nul, $fnord, $count);

            return $count == 0;
        }
