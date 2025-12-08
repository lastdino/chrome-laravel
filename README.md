Chrome Laravel Integration (chrome-php)

English | 日本語

---

About

This Laravel 12 application demonstrates rendering web pages to PDF using Headless Chrome via the chrome-php/chrome library. It includes a small integration package at packages/lastdino/chrome-laravel that provides a ChromeManager service and a Chrome facade.

Features

- Render any URL to PDF using a real Chrome instance (headless)
- Render raw HTML to PDF via setHtml
- Sensible defaults (e.g., printBackground: true)
- Configure Chrome binary path, headless mode, custom flags, and timeouts
- Clean shutdown to avoid zombie processes
- Per-call option overrides merged with config defaults

Requirements

- PHP 8.4+
- Laravel 12
- Chrome/Chromium binary available on the host
- composer dependency: chrome-php/chrome

Install

0) Package installation (if using in your own project)
   - composer require lastdino/chrome-laravel

1) Backend dependencies
   - composer install

2) Environment setup
   - Copy .env.example to .env
   - php artisan key:generate

3) Frontend (optional)
   - npm install
   - npm run dev

Configuration

The package configuration lives at packages/lastdino/chrome-laravel/src/config/chrome.php.
Publish it to your app config with:
```
php artisan vendor:publish --provider="LastDino\ChromeLaravel\ChromeServiceProvider" --tag=chrome-config --no-interaction
```

Key settings in config/chrome.php:
- binary: read from env CHROME_BINARY (optional absolute path to Chrome/Chromium)
- headless: read from env CHROME_HEADLESS, one of: new | old | disabled (default: new)
- args: extra flags to pass to Chrome process (array, edit in config if needed)
- connection_timeout, send_timeout, idle_timeout: timeouts in seconds
- pdf: default PDF options (merged with per-call overrides)

Usage

Use the Chrome facade to render to a temporary PDF file path. Two entry points are available:
1) pdf(string $url, array $options = [])
2) pdfFromHtml(string $html, array $options = [])

Code (PHP):
```
use LastDino\ChromeLaravel\Facades\Chrome;

// 1) From URL
public function downloadFromUrl()
{
    $url = route('home');
    $tmpPath = Chrome::pdf($url, [
        'printBackground' => true,
        'landscape' => true,
    ]);

    return response()->download($tmpPath, 'page.pdf')->deleteFileAfterSend();
}

// 2) From raw HTML
public function downloadFromHtml()
{
    $html = view('pdf.invoice', ['invoice' => 123])->render();
    $tmpPath = Chrome::pdfFromHtml($html, [
        'scale' => 0.9,
    ]);

    return response()->download($tmpPath, 'invoice.pdf')->deleteFileAfterSend();
}
```

Options

- Options map to Chrome DevTools Page.printToPDF parameters. Common ones:
  - printBackground (bool)
  - landscape (bool)
  - scale (float)
  - pageRanges (string)
  - paperWidth / paperHeight (inches)
  - marginTop / marginBottom / marginLeft / marginRight (inches)
  - displayHeaderFooter, headerTemplate, footerTemplate
- Per-call options are merged over the defaults defined in config('chrome.pdf').
Testing

- php artisan test

Code Style

- vendor/bin/pint --dirty

Troubleshooting

- Frontend changes not visible? Try: npm run dev, npm run build, or composer run dev
- Ensure Chrome/Chromium exists on the system; set CHROME_BINARY in .env if auto-detection fails
- If you see timeouts, increase values in config/chrome.php (connection_timeout/send_timeout/idle_timeout)
- In restricted environments, you may need flags like --no-sandbox in config('chrome.args')

License

MIT

---

README (日本語)

概要

本プロジェクトは、chrome-php/chrome を用いて Headless Chrome により Web ページを PDF 化する Laravel 12 アプリケーションの例です。packages/lastdino/chrome-laravel 配下に、ChromeManager サービスと Chrome ファサードを提供する軽量な統合パッケージが含まれています。

特長

- 実際の Chrome（ヘッドレス）で任意の URL を PDF にレンダリング
- 生の HTML を setHtml で PDF 化
- 使いやすい既定値（例: printBackground: true）
- Chrome バイナリパス、ヘッドレス動作、カスタムフラグ、各種タイムアウトを設定可能
- プロセスを確実に終了し、ゾンビプロセスを回避
- 呼び出し毎のオプションで設定の既定値を上書き可能

必要条件

- PHP 8.4+
- Laravel 12
- システム上に Chrome/Chromium がインストールされていること
- 依存パッケージ: chrome-php/chrome

インストール手順

0) パッケージのインストール（ご自身のプロジェクトに導入する場合）
   - composer require lastdino/chrome-laravel

1) バックエンド依存関係
   - composer install

2) 環境設定
   - .env.example を .env にコピー
   - php artisan key:generate

3) フロントエンド（任意）
   - npm install
   - npm run dev

設定

設定ファイルは packages/lastdino/chrome-laravel/src/config/chrome.php にあります。
アプリ側へ公開するには次のコマンドを実行します。
```
php artisan vendor:publish --provider="LastDino\ChromeLaravel\ChromeServiceProvider" --tag=chrome-config --no-interaction
```

主な設定項目（config/chrome.php）:
- binary: 環境変数 CHROME_BINARY から読み込み（Chrome/Chromium のパス・任意）
- headless: 環境変数 CHROME_HEADLESS（new | old | disabled、既定 new）
- args: Chrome 起動フラグ（配列。必要に応じて設定ファイルで編集）
- connection_timeout / send_timeout / idle_timeout: タイムアウト（秒）
- pdf: 既定の PDF オプション（呼び出し時の指定で上書き可能）

使い方

Chrome ファサードを使って PDF を生成します。利用可能な 2 つの入口関数:
1) pdf(string $url, array $options = [])
2) pdfFromHtml(string $html, array $options = [])

コード（PHP）:
```
use LastDino\ChromeLaravel\Facades\Chrome;

// 1) URL から
public function downloadFromUrl()
{
    $url = route('home');
    $tmpPath = Chrome::pdf($url, [
        'printBackground' => true,
        'landscape' => true,
    ]);

    return response()->download($tmpPath, 'page.pdf')->deleteFileAfterSend();
}

// 2) 生の HTML から
public function downloadFromHtml()
{
    $html = view('pdf.invoice', ['invoice' => 123])->render();
    $tmpPath = Chrome::pdfFromHtml($html, [
        'scale' => 0.9,
    ]);

    return response()->download($tmpPath, 'invoice.pdf')->deleteFileAfterSend();
}
```

オプション

- オプションは Chrome DevTools の Page.printToPDF に対応します。代表例:
  - printBackground（bool）
  - landscape（bool）
  - scale（float）
  - pageRanges（string）
  - paperWidth / paperHeight（インチ）
  - marginTop / marginBottom / marginLeft / marginRight（インチ）
  - displayHeaderFooter, headerTemplate, footerTemplate
- 呼び出し時のオプションは config('chrome.pdf') の既定値にマージされます。
テスト

- php artisan test

コーディング規約

- vendor/bin/pint --dirty を実行して整形してください

トラブルシューティング

- フロントエンドの変更が反映されない場合: npm run dev / npm run build / composer run dev（定義時）
- システムに Chrome/Chromium が存在することを確認し、自動検出に失敗する場合は .env の CHROME_BINARY を設定してください
- タイムアウトが発生する場合は config/chrome.php の connection_timeout / send_timeout / idle_timeout を増やしてください
- 制限のある環境では --no-sandbox などのフラグを config('chrome.args') に追加してください

ライセンス

MIT
