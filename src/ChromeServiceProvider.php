<?php

declare(strict_types=1);

namespace LastDino\ChromeLaravel;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ChromeServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/chrome.php', 'chrome');

        $this->app->singleton(ChromeManager::class, function ($app) {
            /** @var array{binary: ?string, headless: string, args: array<int,string>, connection_timeout: int, send_timeout: int, idle_timeout: int, pdf: array<string, mixed>} $config */
            $config = $app['config']->get('chrome', []);

            return new ChromeManager(
                binary: $config['binary'] ?? null,
                headless: $config['headless'] ?? 'new',
                args: $config['args'] ?? [],
                connectionTimeout: (int) ($config['connection_timeout'] ?? 10),
                sendTimeout: (int) ($config['send_timeout'] ?? 60),
                idleTimeout: (int) ($config['idle_timeout'] ?? 60),
                pdfDefaults: (array) ($config['pdf'] ?? []),
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/chrome.php' => $this->app->configPath('chrome.php'),
        ], 'chrome-config');
    }

    /**
     * @return array<int, class-string>
     */
    public function provides(): array
    {
        return [ChromeManager::class];
    }
}
