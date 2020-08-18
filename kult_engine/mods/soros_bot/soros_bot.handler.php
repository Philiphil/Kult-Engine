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

namespace KultEngine;

class soros_bot
{
    use CoreElementTrait;
    /*
    ░░░░░░░░░░░░ ░████░░░░░░░░░░░░░░
    ░░░░░░░░▄▄████████▄▄░████░░░░░░░
    ░░░░░░▄██████████████████░░░░░░░
    ░░░░░▄█████▀░░████▀██████░░░░░░░
    ░░░░░██████░░░████░░▀████░░░░░░░
    ░░░░░███████▄▄████░░░▀███░░░░░░░
    ░░░░░░████████████▄▄▄░░░░░░░░░░░
    ░░░░░░░▀███████████████▄░░░░░░░░
    ░░░░░░░░░░░▀▀████████████▄░░░░░░
    ░░░░░███▄░░░░░████▀████████░░░░░
    ░░░░░█████░░░░████░░▀██████░░░░░
    ░░░░░██████▄░░████░░▄██████░░░░░
    ░░░░░████████████████████▀░░░░░░
    ░░░░░████░▀███████████▀▀░░░░░░░░
    ░░░░░▀▀▀░░░░░░████▀▀░░░░░░░░░░░░
    ░░░░░░░░░░░░░░████░░░░░░░░░░░░░░
    */
    public static $visuel = false;
    public static $cryptos = [];

    public static $etalon = 'BTC';
    public static $api = null;

    public static $test = null;
    public static $no_follow = null;

    public static $depth = null;
    public static $ticker = null;
    public static $records = [];
    public static $tick = 0;

    public static $min_buy_val = 0;
    public static $min_buy_var = 0;
    public static $min_sell_val = 0;
    public static $min_sell_var = 0;

    public static $dao_stock = null;
    public static $dao_records = null;

    public static $min_records = 0;
    public static $max_records = 0;
    public static $db_records = 0;

    public static function require()
    {
        set_time_limit(0);
        require 'config.php';
        require 'binance.php';
        require 'class.php';

        self::$etalon = $ETALON;
        self::$test = $test_currency;
        self::$api = new \Binance\API($KEY, $SECRET);
        self::$no_follow = $NOFOLLOW;
        self::$tick = $tick;
        self::$min_buy_val = $min_buy_value;
        self::$min_buy_var = $min_buy_variation;
        self::$min_sell_val = $min_sell_value;
        self::$min_sell_var = $min_sell_variation;
        self::$min_records = $min_records;
        self::$max_records = $max_records;
        self::$records = [];
        self::$db_records = $db_records;
        self::$dao_stock = new daoGenerator(new \stock());
        if (self::$db_records) {
            self::$dao_records = new daoGenerator(new \stock_record());
        }
        self::$ticker = self::$api->prices();
        self::$depth = self::$api->exchangeInfo();
    }

    public static function run($brin)
    {
        self::require();
        self::actualise_bag(self::$dao_stock->get_all(), true);
        $brin = $brin === 'visuel' || $brin === 'visual' ? true : false;
        self::$visuel = $brin;
        buffer::send();
        echo "soros init'd\n";
        $norders = 0;
        for (;;) {
            self::loop();
            self::actualise_bag();
        }
    }

    public static function setter()
    {
    }

    public static function setter_conf($brin)
    {
    }

    public static function actualise_bag($a = false, $o = false)
    {
        $bal = self::$api->balances(self::$ticker);
        if (!$a) {
            $a = self::$cryptos;
        }
        foreach ($bal as $val => $key) {
            if (in_array($val, self::$no_follow)) {
                continue;
            }
            if (self::$test != null && $val != self::$test) {
                continue;
            }

            $e = false;
            foreach ($a as $cey => $wal) {
                if ($wal->_sym == $val) {
                    $e = $wal;
                    break;
                }
            }
            if ($key['available'] == 0 && !$e) {
                continue;
            }
            $prix = (bool) self::$ticker[$val.self::$etalon] ? self::$ticker[$val.self::$etalon] : self::$ticker[$val.'BNB'];
            if (!$e) {
                $e = new \stock();
                $e->_sym = $val;
                $e->_prix_achat = $prix;
                $e->_prix_vente = $prix;
                $e->_min_price = $prix;
                $e->_max_price = $prix;
                $e->_min_qtt = 0;
                $e->_max_qtt = $key['available'];
            }
            $e->_qtt = $key['available'];
            if ($o) {
                $e->_min_price = $prix;
                $e->_max_price = $prix;
                $e->_prix_vente = $prix;
                $e->_prix_achat = $prix;
            }
            if ($e->_max_qtt == 0) {
                $e->_max_qtt = $key['available'];
            }
            if ($e->_round == 0 && $val != self::$etalon) {
                $c = (bool) self::$ticker[$val.self::$etalon] ? self::$etalon : 'BNB';
                foreach (self::$depth['symbols'] as $depth) {
                    if ($depth['symbol'] != $val.$c) {
                        continue;
                    }
                    $e->_round = $depth['filters'][1]['minQty'];
                }
            }
            self::$cryptos[$e->_sym] = $e;
        }
    }

    public static function analyse_crypto($crypto)
    {
        $ecart = '   ';
        if (self::$test != null && $crypto->_sym != self::$test) {
            return false;
        }
        if (in_array($crypto->_sym, self::$no_follow)) {
            return false;
        }

        self::$ticker = self::$api->prices();

        $prix = (bool) self::$ticker[$crypto->_sym.self::$etalon] ? self::$ticker[$crypto->_sym.self::$etalon] : self::$ticker[$crypto->_sym.'BNB'];

        $c = (bool) self::$ticker[$crypto->_sym.self::$etalon] ? self::$etalon : 'BNB';

        if ($prix > $crypto->_max_price) {
            $crypto->_max_price = $prix;
        }

        if ($prix < $crypto->_min_price) {
            $crypto->_min_price = $prix;
        }

        self::$cryptos[$crypto->_sym] = $crypto;

        return [$prix, $c];
    }

    public static function loop()
    {
        $orders = 0;
        foreach (self::$cryptos as $val) {
            $result = self::analyse_crypto($val);
            $val = self::$cryptos[$val->_sym];

            if (!$result) {
                continue;
            }
            $transaction = null;
            $order = '';

            if ($val->_qtt > $val->_min_qtt &&
                $result[0] < $val->_max_price &&
                $result[0] > $val->_prix_achat
            ) {
                $diff3 = \stock::compare($val->_prix_achat, $result[0]);
                if (self::$visuel) {
                    echo  's'.'    '.$diff2.' '.self::$min_sell_var.'   '.self::$min_sell_val.' '.$diff3."\n";
                }

                if (
                    $diff3 > self::$min_sell_val
                ) {
                    $qtn = \stock::round_qtn($val->_qtt - $val->_min_qtt, self::$cryptos[$val->_sym]->_round);
                    if ($qtn != 0) {
                        $transaction = self::$api->marketSell($val->_sym.$result[1], $qtn);
                        if (!isset($transaction['code']) && !isset($transaction['msg'])) {
                            $val->_prix_vente = $result[0];
                        }
                    }
                }
            } elseif ($result[0] > $val->_min_price &&
                    $result[0] < $val->_prix_vente &&
                    $val->_qtt < $val->_max_qtt
            ) {
                $diff3 = \stock::compare($val->_prix_vente, $result[0]);
                if (self::$visuel) {
                    echo 'b'.'    '.$diff2.'    '.$diff3."\n";
                }

                if ($diff3 < self::$min_buy_val
                ) {
                    $qtn = $val->_max_qtt - $val->_qtt;
                    $qtn = \stock::round_qtn($qtn, self::$cryptos[$val->_sym]->_round);
                    if ($qtn != 0) {
                        $transaction = self::$api->marketBuy($val->_sym.$result[1], $qtn);
                        $order = 'BUY';
                    }
                    if (!isset($transaction['code']) && !isset($transaction['msg'])) {
                        $val->_prix_achat = $result[0];
                    }
                }
            }
            if (!is_null($transaction)) {
                if (!isset($transaction['code']) && !isset($transaction['msg'])) {
                    $orders++;
                    $val_qtt = $order == 'BUY' ? self::$cryptos[$val->_sym]->_qtt + $qtn : self::$cryptos[$val->_sym]->_qtt - $qtn;
                    $val->_min_price = $result[0];
                    $val->_max_price = $result[0];
                }
            }
        }

        return $orders;
    }
}
