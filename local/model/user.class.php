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

class User extends KultEngine\DaoableObject
{
    use KultEngine\TimableTrait;
    public OneToOneRelation $emails;

    public function __setRelations()
    {
        $this->emails = new OneToOneRelation(UserEmail::class);
    }
}

class UserEmail extends KultEngine\DaoableObject
{
    public string $email;
    public bool $main = true;
    public OneToOneRelation $user;

    public function __setRelations()
    {
        $this->user = new OneToOneRelation(User::class);
    }
}

class UserPassword extends KultEngine\DaoableObject
{
    use KultEngine\TimableTrait;
    public string $password;
    public int $userId = 0;
}

class UserSocialAccount extends KultEngine\DaoableObject
{
    public string $uid;
    public int $platform = 0;
    public int $userId = 0;

    const PLATFORM_TYPE_FACEBOOK = 1;
    const PLATFORM_TYPE_GOOGLE = 2;
    const PLATFORM_TYPE_GITHUB = 3;
    const PLATFORM_TYPE_TWITTER = 4;
}
