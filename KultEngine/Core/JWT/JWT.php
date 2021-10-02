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
 * @copyright Copyright (c) 2016-2020, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace KultEngine\Core\JWT;
use KultEngine\Core\JWT\JWTHeader;
use KultEngine\Core\JWT\JWTPayload;

class JWT
{
    public ?JWTHeader $_header = null;
    public ?JWTPayload $_payload = null;

    public static string $key = 'AAZAB3NzaC1yc2EA42069AAADAQABAAAByXFwdYGWTl14ItFk2zv4Bs12rgZa27081742016fQU9WBtLJxixn7Zh7vjsqyR7A1EV20ZJd5pXYeVKkX8P883M=';

    const ALG_HS256 = 'HS256';
    const ALG_HS384 = 'HS384';
    const ALG_HS512 = 'HS512';
    const ALG_RS256 = 'RS256';
    const ALG_RS384 = 'RS384';
    const ALG_RS512 = 'RS512';
    const ALG_NONE = 'none';
    const DEFAULT_ALG = JWT::ALG_HS256;

    const ALGS = [
        JWT::ALG_HS256 => ['hash_hmac', 'SHA256'],
        JWT::ALG_HS384 => ['hash_hmac', 'SHA384'],
        JWT::ALG_HS512 => ['hash_hmac', 'SHA512'],
        JWT::ALG_RS256 => ['openssl', 'SHA256'],
        JWT::ALG_RS384 => ['openssl', 'SHA384'],
        JWT::ALG_RS512 => ['openssl', 'SHA512'],
        JWT::ALG_NONE  => ['none', 'none'],
    ];

    public function __construct()
    {
        $this->_header = new JWTHeader();
        $this->setAlg($this::DEFAULT_ALG);
        $this->_payload = new JWTPayload();
    }

    public function setAlg(string $alg)
    {
        if (!in_array($alg, array_keys($this::ALGS))) {
            throw new Exception($alg.' not available');
        }
        $this->_header->alg = $alg;
    }

    public static function decode(string $data): ?self
    {
        $raw = explode('.', $data);
        $decoded = [];
        $decoded[0] = JWTHeader::__fromJsonObject(json_decode(JWT::base64_url_decode($raw[0])));
        $decoded[1] = JWTPayload::__fromJsonObject(json_decode(JWT::base64_url_decode($raw[1])));

        if (!JWT::verify($raw[0].'.'.$raw[1], JWT::base64_url_decode($raw[2]), JWT::$key, $decoded[0]->alg)) {
            throw new \Exception('invalid jwt');
        }
        if (!$decoded[1]->verifyClaims()) {
            throw new \Exception('invalid informations');
        }
        $o = new JWT();
        $o->_header = $decoded[0];
        $o->_payload = $decoded[1];

        return $o;
    }

    public function encode(): string
    {
        $encoded = [];
        $encoded[0] = JWT::base64_url_encode($this->_header->__toJson());
        $encoded[1] = JWT::base64_url_encode($this->_payload->generateClaims()->__toJson());

        $jwt = $encoded[0].'.'.$encoded[1];
        $encoded[2] = $this->base64_url_encode($this->sign($jwt, JWT::$key, $this->_header->alg));

        return $jwt.'.'.$encoded[2];
    }

    public static function base64_url_decode(string $input): string
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function base64_url_encode(string $input): string
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    public static function sign(string $message, string $key, string $algorithm): string
    {
        list($function, $type) = JWT::ALGS[$algorithm];

        switch ($function) {
            case 'hash_hmac':
                try {
                    $signature = hash_hmac($type, $message, $key, true);
                } catch (Exception $e) {
                    throw new \Exception(sprintf('Signing failed: %s', $e->getMessage()));
                }
                if ($signature === false) {
                    throw new \Exception('Signing failed');
                }

                return $signature;
            case 'openssl':
                $signature = '';

                try {
                    $sign = openssl_sign($message, $signature, $key, $type);
                } catch (Exception $e) {
                    throw new \Exception($e->getMessage());
                }

                if (!$sign) {
                    throw new \Exception('Signing failed');
                }

                return $signature;
             case 'none':
               return $message;
            default:
                throw new \Exception('Invalid function');
        }
    }

    public static function verify(string $message, string $signature, string $key, string $algorithm): bool
    {
        list($function, $type) = JWT::ALGS[$algorithm];
        switch ($function) {
            case 'openssl':
                $success = openssl_verify($message, $signature, $key, $type);
                if ($success === 1) {
                    return true;
                } elseif ($success === 0) {
                    return false;
                }

                throw new \Exception(\openssl_error_string());
            case 'hash_hmac':
                $hash = hash_hmac($type, $message, $key, true);

                return hash_equals($signature, $hash);
             case 'none':
                return $message == $signature;
             default:
                  throw new \Exception('Invalid function');
        }
    }
}

/* ex
$d = new JWT();
$d->setAlg(JWT::ALG_HS256);
$e = $d->encode();
var_dump( JWT::decode($e) );
*/
