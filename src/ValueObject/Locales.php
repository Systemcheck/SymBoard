<?php

declare(strict_types=1);

namespace App\ValueObject;

class Locales
{
    final public const DEFAULT = self::DEUTSCH;
    final public const AVAILABLE = [
        self::DEUTSCH => 'Deutsch',
        self::ENGLISH => 'English',
        self::FRENCH => 'Français',
    ];
    final public const DEUTSCH = 'de';
    final public const ENGLISH = 'en';
    final public const FRENCH = 'fr';
}
