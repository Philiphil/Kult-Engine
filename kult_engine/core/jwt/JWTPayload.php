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

class JWTPayload
{
    use JsonableTrait;
    public $exp = '';
    public $iat = '';
    public $nbf = '';
    public $jti = '';
    public $iss = '';
    public $sub = '';
    public $aud = '';
    public int $maxage = 3600;

    public function generateClaims(): self
    {
        $time = time();
        $this->iat = $time;
        $this->nbf = $time;
        $this->exp = $time + $this->maxage;
        $this->jti = uniqid();

        return $this;
    }

    public function verifyClaims(): bool
    {
        if ($this->exp < time()) {
            throw new \Exception('expired');
        }
        if ($this->iat > time()) {
            throw new \Exception('issued in the future');
        }
        if ($this->nbf > time()) {
            throw new \Exception('used before');
        }

        return true;
    }
}

/* ex
$d = new JWT();
$d->setAlg(JWT::ALG_HS256);
$e = $d->encode();
var_dump( JWT::decode($e) );
*/
