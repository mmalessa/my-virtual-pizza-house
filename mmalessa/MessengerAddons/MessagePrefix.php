<?php

declare(strict_types=1);

namespace Mmalessa\MessengerAddons;

class MessagePrefix
{
    public static function remove(string $className, string $messageClassPrefix): string
    {
        $pattern =sprintf("/^%s/", preg_quote($messageClassPrefix));
        return preg_replace($pattern,'',$className,1);
    }

    public static function add(string $messageType, string $messageClassPrefix): string
    {
        return sprintf("%s%s", $messageClassPrefix, $messageType);
    }
}