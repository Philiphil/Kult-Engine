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

namespace KultEngine\Core\Security\JWT;

class JWTService
{
    public static string $key = 'AAZAB3NzaC1yc2EA42069AAADAQABAAAByXFwdYGWTl14ItFk2zv4Bs12rgZa27081742016fQU9WBtLJxixn7Zh7vjsqyR7A1EV20ZJd5pXYeVKkX8P883M=';
    public static int $maxage = 3600;
    public static ?string $alg = null;

    const ALG_HS256 = 'HS256';
    const ALG_HS384 = 'HS384';
    const ALG_HS512 = 'HS512';
    const ALG_RS256 = 'RS256';
    const ALG_RS384 = 'RS384';
    const ALG_RS512 = 'RS512';
    const ALG_NONE = 'none';
    const DEFAULT_ALG = self::ALG_HS256;

    const ALGS = [
        self::ALG_HS256 => ['hash_hmac', 'SHA256'],
        self::ALG_HS384 => ['hash_hmac', 'SHA384'],
        self::ALG_HS512 => ['hash_hmac', 'SHA512'],
        self::ALG_RS256 => ['openssl', 'SHA256'],
        self::ALG_RS384 => ['openssl', 'SHA384'],
        self::ALG_RS512 => ['openssl', 'SHA512'],
        self::ALG_NONE  => ['none', 'none'],
    ];

    public static function createJwt(array $claims = []): JWT
    {
        $jwt = new JWT();
        if (!self::$alg) {
            self::$alg = self::DEFAULT_ALG;
        }

        if (!in_array(self::$alg, array_keys(self::ALGS))) {
            throw new \Exception(self::$alg.' not available');
        }
        $jwt->setAlg(self::$alg);
        $jwt->_payload->maxage = self::$maxage;

        foreach ($claims as $claim => $value) {
            $jwt->_payload->$claim = $value;
        }

        return $jwt;
    }

    public static function decode(string $data): ?JWT
    {
        $raw = explode('.', $data);
        $decoded = [];
        $decoded[0] = JWTHeader::__fromJsonObject(json_decode(self::base64_url_decode($raw[0])));
        $decoded[1] = JWTPayload::__fromJsonObject(json_decode(self::base64_url_decode($raw[1])));

        if (!self::verify($raw[0].'.'.$raw[1], self::base64_url_decode($raw[2]), self::$key, $decoded[0]->alg)) {
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

    public static function encode(JWT $token): string
    {
        $encoded = [];
        $encoded[0] = self::base64_url_encode($token->_header->__toJson());
        $encoded[1] = self::base64_url_encode($token->_payload->generateClaims()->__toJson());

        $jwt = $encoded[0].'.'.$encoded[1];
        $encoded[2] = self::base64_url_encode(self::sign($jwt, self::$key, $token->_header->alg));

        return $jwt.'.'.$encoded[2];
    }

    private static function base64_url_decode(string $input): string
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    private static function base64_url_encode(string $input): string
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    private static function sign(string $message, string $key, string $algorithm): string
    {
        list($function, $type) = self::ALGS[$algorithm];

        switch ($function) {
            case 'hash_hmac':
                try {
                    $signature = hash_hmac($type, $message, $key, true);
                } catch (\Exception $e) {
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
                } catch (\Exception $e) {
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

    private static function verify(string $message, string $signature, string $key, string $algorithm): bool
    {
        list($function, $type) = self::ALGS[$algorithm];
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
