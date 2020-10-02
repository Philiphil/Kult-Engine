<?php

class User extends KultEngine\DaoableObject
{
    use KultEngine\TimableTrait;
    public ?\DateTime $lastLogin;
}

class UserEmail extends KultEngine\DaoableObject
{
    public string $email;
    public bool $main=true;
    public User $user;
}

class UserPassword extends KultEngine\DaoableObject
{
    use KultEngine\TimableTrait;
    public string $password;
    public int $userId = 0;
}

class UserSocialAccount extends KultEngine\DaoableObject
{
    public string $uid ;
    public int $platform = 0;
    public int $userId = 0;

    const PLATFORM_TYPE_FACEBOOK = 1;
    const PLATFORM_TYPE_GOOGLE = 2;
    const PLATFORM_TYPE_GITHUB = 3;
    const PLATFORM_TYPE_TWITTER = 4;
}
