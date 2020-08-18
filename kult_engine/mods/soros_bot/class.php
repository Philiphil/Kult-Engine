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

class stock extends kult_engine\daoableObject
{
    public $_sym = 'string';
    public $_prix_achat = 0.0;
    public $_prix_vente = 0.0;
    public $_qtt = 0.0;
    public $_min_qtt = 0.0;
    public $_max_qtt = 0.0;
    public $_min_price = 0.0;
    public $_max_price = 0.0;
    public $_round = 0.0;

    public static function round_qtn($qtn, $round)
    {
        return $round === 1.0 ? floor($qtn) : number_format($qtn, strpos(substr($round, 2), '1') + 1, '.', '');
    }

    public static function compare($old, $new)
    {
        return $old != 0 ? 100 * ($new - $old) / $old : 0;
    }
}

class stock_record extends kult_engine\daoableObject
{
    public $_sym = 'string';
    public $_prix = 0.0;

    public static function clean($maxcrypto)
    {
        $sql = '
		SELECT `_id`
		FROM stock_record
		ORDER BY `_id`
		DESC 
		limit 1';
        $e = kult_engine\connector::query($sql);
        $e->execute();
        $e = $e->fetchAll(\PDO::FETCH_ASSOC);
        $max = count(kult_engine\soros_bot::$cryptos) * $maxcrypto;
        $id = $e[0]['_id'] - $max;
        $sql = '
		DELETE FROM stock_record
		WHERE _id < '.$id;
        $e = kult_engine\connector::query($sql);
        $e->execute();
    }

    public static function clean_sym($sym)
    {
        $sql = "
		DELETE FROM stock_record
		WHERE _sym like '$sym' ";
        $e = kult_engine\connector::query($sql);
        $e->execute();
    }

    public static function compare($arr)
    {
        $result = 0;
        for ($i = 0; $i < count($arr) - 1; $i++) {
            if ($i + 1 < count($arr)) {
                $result = $result + stock::compare($arr[$i]->_prix, $arr[$i + 1]->_prix);
            }
        }

        return $result / count($arr);
    }
}

class soros_stats extends kult_engine\daoableObject
{
    public $_current_btc_val = 0.0;
    public $_timestamp = '';
}
