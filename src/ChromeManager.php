<?php

declare(strict_types=1);

namespace LastDino\ChromeLaravel;

use RuntimeException;

class ChromeManager
{
    /**
     * @param array<int, string> $args
     */
    public function __construct(
        public ?string $binary = null,
        public string $headless = 'new',
        public array $args = [],
        public int $connectionTimeout = 10,
        public int $sendTimeout = 60,
        public int $idleTimeout = 60,
        /** @var array<string, mixed> */
        public array $pdfDefaults = ['printBackground' => true],
    ) {
    }

    /**
     * Render the given URL to a PDF and return a temporary file path.
     *
     * @param array<string, mixed> $options Per-call PDF options that override config defaults.
     */
    public function pdf(string $url, array $options = []): string
    {
        if (! class_exists('HeadlessChromium\\BrowserFactory')) {
            throw new RuntimeException('chrome-php/chrome is not installed. Please require "chrome-php/chrome" via Composer.');
        }

        // Build browser
        $factory = new \HeadlessChromium\BrowserFactory($this->binary);

        $headlessFlag = match ($this->headless) {
            'disabled' => false,
            'old' => true,
            default => true, // chrome-php toggles to the proper flag internally on newer Chrome
        };

        $browser = $factory->createBrowser([
            'headless' => $headlessFlag,
            'customFlags' => $this->args,
            'sendSyncDefaultTimeout' => $this->sendTimeout * 1000,
            'socketTimeout' => $this->connectionTimeout,
        ]);

        try {
            $page = $browser->createPage();
            $page->navigate($url)->waitForNavigation(\HeadlessChromium\Page::NETWORK_IDLE, $this->idleTimeout * 1000);

            $tmp = tempnam(sys_get_temp_dir(), 'chrome-pdf-');
            if ($tmp === false) {
                throw new RuntimeException('Failed to create temporary file for PDF.');
            }

            // Generate PDF and save
            $pdfOptions = $this->buildPdfOptions($options);
            $page->pdf($pdfOptions)->saveToFile($tmp);

            return $tmp;
        } finally {
            // Always close to avoid zombie processes
            try {
                $browser->close();
            } catch (\Throwable) {
                // ignore
            }
        }
    }

    /**
     * Render the given raw HTML string to a PDF and return a temporary file path.
     *
     * @param array<string, mixed> $options Per-call PDF options that override config defaults.
     */
    public function pdfFromHtml(string $html, array $options = []): string
    {
        if (! class_exists('HeadlessChromium\\BrowserFactory')) {
            throw new RuntimeException('chrome-php/chrome is not installed. Please require "chrome-php/chrome" via Composer.');
        }

        // Build browser
        $factory = new \HeadlessChromium\BrowserFactory($this->binary);

        $headlessFlag = match ($this->headless) {
            'disabled' => false,
            'old' => true,
            default => true,
        };

        $browser = $factory->createBrowser([
            'headless' => $headlessFlag,
            'customFlags' => $this->args,
            'sendSyncDefaultTimeout' => $this->sendTimeout * 1000,
            'socketTimeout' => $this->connectionTimeout,
        ]);

        try {
            $page = $browser->createPage();
            // Load HTML into the page
            $page->setHtml($html);

            $tmp = tempnam(sys_get_temp_dir(), 'chrome-pdf-');
            if ($tmp === false) {
                throw new RuntimeException('Failed to create temporary file for PDF.');
            }

            // Generate PDF and save
            $pdfOptions = $this->buildPdfOptions($options);
            $page->pdf($pdfOptions)->saveToFile($tmp);

            return $tmp;
        } finally {
            // Always close to avoid zombie processes
            try {
                $browser->close();
            } catch (\Throwable) {
                // ignore
            }
        }
    }

    /**
     * Build the final PDF options by merging defaults with per-call overrides.
     *
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public function buildPdfOptions(array $overrides = []): array
    {
        // Shallow merge is typically sufficient, Chrome options are flat.
        return array_replace($this->pdfDefaults, $overrides);
    }
}
