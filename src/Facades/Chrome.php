<?php

declare(strict_types=1);

namespace LastDino\ChromeLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string pdf(string $url, array $options = [])
 */
class Chrome extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LastDino\ChromeLaravel\ChromeManager::class;
    }
}
