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

class Analytics
{
    use CoreElementTrait;
    public static ?array $_logs = null;
    public static ?string $_file = null;
    public static ?array $_analytics = null;

    public static function setter()
    {
        self::$_logs = [];
        self::$_analytics = [];
        self::$_file = constant('logfile');
        if (self::$_file === '' || is_null(self::$_file)) {
            self::$_file = constant('controllerpath').'log.kult';
        }
        self::get_info();
        self::main();
    }

    public static function setter_conf($file)
    {
        self::$_logs = [];
        self::$_analytics = [];
        self::$_file = $file;
        if (self::$_file === '' || is_null(self::$_file)) {
            self::$_file = constant('controllerpath').'log.kult';
        }
        self::get_info();
        self::main();
    }

    public static function read_line($line)
    {
        preg_match_all("/\[(.*?)\]/", $line, $d);
        $f = [];
        foreach ($d[0] as $key) {
            $c = substr($key, 1, strlen($key) - 2);
            array_push($f, $c);
        }
        array_push(self::$_logs, $f);
    }

    public static function read_file($file)
    {
        $handle = fopen($file, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                self::read_line($line);
            }
            fclose($handle);
        }
    }

    public static function get_info()
    {
        self::read_file(self::$_file);
        $_visites = [];
        foreach (self::$_logs as $key) {
            $_tmp = [];
            $_tmp[0] = $key[0].$key[1];
            $tmp = str_replace('-', ' ', $key[2]);
            $tmp = str_replace('/', '-', $tmp);
            $_tmp[1] = new \DateTime($tmp);
            $_tmp[2] = $key[4];

            array_push($_visites, $_tmp);
        }
        foreach ($_visites as $key) {
            $new = true;
            $d = self::$_analytics;
            $i = 0;
            foreach ($d as $y) {
                if ($y['id'] == $key[0]) {
                    $v = date_diff($y['hist'][count($y['hist']) - 1]['time'], $key[1]);
                    if (dateIntervalToSeconds($v) < 30 * 60) {
                        $new = false;
                        self::$_analytics[$i]['hist'][count($y['hist'])] = ['page' =>$key[2], 'time'=>$key[1]];
                    }
                }
                $i++;
            }
            if ($new) {
                array_push(self::$_analytics, ['id' =>  $key[0], 'hist' => [['page' => $key[2], 'time' => $key[1]]]]);
            }
        }
    }

    public static function main()
    {
        $_v = count(self::$_analytics);
        $_t = [];
        $_vu = 0;
        $_r = 0;
        $_c = 0;
        $_tmp = [];

        foreach (self::$_analytics as $key) {
            $v = $key['hist'][0]['time'];
            $b = $key['hist'][count($key['hist']) - 1]['time'];
            $t = date_diff($v, $b);

            if ($v != $b) {
                array_push($_t, dateIntervalToSeconds($t));
            }

            if (!in_array($key['id'], $_tmp)) {
                array_push($_tmp, $key['id']);
            }
            if (count($key['hist']) == 1) {
                $_r++;
            }
            foreach ($key['hist'] as $tmp) {
                $_vu++;
            }
        }

        $_tmy = round(array_sum($_t) / count($_t));
        $_t = [];

        foreach (self::$_analytics as $key) {
            $v = $key['hist'][0]['time'];
            $b = $key['hist'][count($key['hist']) - 1]['time'];
            $t = date_diff($v, $b);

            if ($v != $b && dateIntervalToSeconds($t) > $_tmy) {
                array_push($_t, dateIntervalToSeconds($t));
            }
        }

        $_tmx = round(array_sum($_t) / count($_t));

        $_r = round(100 * $_r / $_v, 3);

        $_v_u = count($_tmp);
        $_pn = [];
        $_p = [];
        for ($i = 0; $i < count(self::$_analytics); $i++) {
            for ($j = 0; $j < count(self::$_analytics[$i]['hist']); $j++) {
                if (!in_array(self::$_analytics[$i]['hist'][$j]['page'], $_pn)) {
                    array_push($_pn, self::$_analytics[$i]['hist'][$j]['page']);

                    if (isset(self::$_analytics[$i]['hist'][$j + 1])) {
                        $v = self::$_analytics[$i]['hist'][$j + 1]['time'];
                        $b = self::$_analytics[$i]['hist'][$j]['time'];
                        $t = date_diff($v, $b);

                        $_p[self::$_analytics[$i]['hist'][$j]['page']] = [1, dateIntervalToSeconds($t), 0];
                    } else {
                        $_p[self::$_analytics[$i]['hist'][$j]['page']] = [1, 0, 100];
                    }
                } else {
                    if (isset(self::$_analytics[$i]['hist'][$j + 1])) {
                        $v = self::$_analytics[$i]['hist'][$j + 1]['time'];
                        $b = self::$_analytics[$i]['hist'][$j]['time'];
                        $t = date_diff($v, $b);

                        $_p[self::$_analytics[$i]['hist'][$j]['page']] = [$_p[self::$_analytics[$i]['hist'][$j]['page']][0] + 1,  round(($_p[self::$_analytics[$i]['hist'][$j]['page']][1] + dateIntervalToSeconds($t)) / 2), round(($_p[self::$_analytics[$i]['hist'][$j]['page']][2] + 0) / 2, 2)];
                    } else {
                        $_p[self::$_analytics[$i]['hist'][$j]['page']] = [$_p[self::$_analytics[$i]['hist'][$j]['page']][0] + 1, $_p[self::$_analytics[$i]['hist'][$j]['page']][1], round(($_p[self::$_analytics[$i]['hist'][$j]['page']][2] + 100) / 2, 2)];
                    }
                }
            }
        }

        $_visitetype = [];
        $maxj = 0;
        for ($i = 0; $i < count(self::$_analytics); $i++) {
            for ($j = 0; $j < count(self::$_analytics[$i]['hist']); $j++) {
                $_visitetype[self::$_analytics[$i]['hist'][$j]['page']][$j] = isset($_visitetype[self::$_analytics[$i]['hist'][$j]['page']][$j]) ? $_visitetype[self::$_analytics[$i]['hist'][$j]['page']][$j] + 1 : 1;
                $maxj = $j > $maxj ? $j : $maxj;
            }
        }

        $curentray = [];

        for ($i = 0; $i < $maxj; $i++) {
            $curentray[$i] = [];
            foreach ($_visitetype as $key => $value) {
                if (isset($value[$i])) {
                    array_push($curentray[$i], [$key, $value[$i]]);
                }
            }
        }

        $output = '<table><tr><td>Page View : '.$_vu.'</td><td>Visit : '.$_v.'</td><td>Unique visitor : '.$_v_u.'</td></tr><tr><td>Moy Session : '.$_tmy.'s</td><td>Max Session : '.$_tmx.'s</td><td>Bounce : '.$_r.'%</td></tr></table>';
        $table = '<br><table>';
        $table .= '<tr><td>Visit</td><td>Second</td><td>bounce</td><td>page</td></tr>';
        foreach ($_p as $key => $val) {
            $table .= '<tr><td>'.$val[0].'</td><td>'.$val[1].'</td><td>'.$val[2]."</td><td  style=' display:block;overflow-x: auto; width:450px'>".$key.'</td></tr>';
        }
        $table .= '</table>';

        echo $output.$table;
        echo '<br><br><br>';

        foreach ($curentray as $key => $value) {
            $tt = 0;
            $n = [];
            for ($i = 0; $i < count($value); $i++) {
                $tt += $value[$i][1];
                $n[$i][0] = $value[$i][0];
                $n[$i][1] = $value[$i][1];
            }
            echo $key.' : <br>';
            $s = 0;
            foreach ($n as $key) {
                if (round($key[1] * 100 / $tt) < 3 || $key[1] < 2) {
                    continue;
                }
                echo '&nbsp&nbsp&nbsp'.substr($key[0], 0, 200).' -> '.round($key[1] * 100 / $tt).'%<br>';
                $s++;
            }
            if ($s) {
                echo '<br><br>';
            }
        }
    }
}
