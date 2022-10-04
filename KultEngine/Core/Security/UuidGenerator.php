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

namespace KultEngine\Core\Security;

class UuidGenerator
{
    public static function v3(string $namespace, string $name): string
    {
        if (!self::is_valid($namespace)) {
            throw new \Exception('invalid namespace');
        }
        $nhex = str_replace(['-', '{', '}'], '', $namespace);
        $nstr = '';
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i].$nhex[$i + 1]));
        }
        $hash = md5($nstr.$name);

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            (hexdec(substr($hash, 12, 4)) & 0x0FFF) | 0x3000,
            (hexdec(substr($hash, 16, 4)) & 0x3FFF) | 0x8000,
            substr($hash, 20, 12)
        );
    }

    public static function v4(): string
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0F | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3F | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public static function v5(string $namespace, string $name): string
    {
        if (!self::is_valid($namespace)) {
            throw new \Exception('invalid namespace');
        }
        $nhex = str_replace(['-', '{', '}'], '', $namespace);
        $nstr = '';
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i].$nhex[$i + 1]));
        }
        $hash = sha1($nstr.$name);

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            (hexdec(substr($hash, 12, 4)) & 0x0FFF) | 0x5000,
            (hexdec(substr($hash, 16, 4)) & 0x3FFF) | 0x8000,
            substr($hash, 20, 12)
        );
    }

    public static function isValid(string $uuid): bool
    {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }
}
