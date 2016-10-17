<?php

namespace kult_engine;

/*
    Centralisation des textes
*/

    function get_lang()
    {
        $server = ['fr', 'en'];
        $default = 'en';

        $user = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $bfr = [];
        $i = 0;
        foreach ($user as $key) {
            $i++;
            $bfr[$i] = $key;
            if (strpos($key, ';') != false) {
                $bfr[$i] = substr($key, 0, strpos($key, ';'));
            }
        }
        $choice = array_merge(array_intersect($bfr, $server));
        $result = '';
        if (count($choice) == 0) {
            $result = $default;
        } else {
            $result = $choice[0];
        }

        return $result;
    }





    function get_text($texte)
    {
        $lang = get_lang();
        $array = textes();

        return isset($array[$lang][$texte]) ? $array[$lang][$texte] : $texte;
    }
