Chrome Laravel Integration (chrome-php)

English | 日本語

---

About

This Laravel 12 application demonstrates rendering web pages to PDF using Headless Chrome via the chrome-php/chrome library. It includes a small integration package at packages/lastdino/chrome-laravel that provides a ChromeManager service and a Chrome facade.

Features

- Render any URL to PDF using a real Chrome instance (headless)
- Sensible defaults (e.g., printBackground: true)
- Configure Chrome binary path, headless mode, custom flags, and timeouts
- Clean shutdown to avoid zombie processes

Requirements

- PHP 8.4+
- Laravel 12
- Chrome/Chromium binary available on the host

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

Common environment variables:
- CHROME_PATH: absolute path to Chrome/Chromium (optional)
- CHROME_HEADLESS: new | old | disabled (default: new)
- CHROME_ARGS: extra flags (e.g., --no-sandbox --disable-gpu)
- CHROME_CONNECT_TIMEOUT: seconds to wait for Chrome to start (default: 10)
- CHROME_SEND_TIMEOUT: sendSync default timeout in seconds (default: 60)
- CHROME_IDLE_TIMEOUT: network idle wait in seconds (default: 60)
- CHROME_PDF_DEFAULTS: JSON string of default PDF options (e.g., {"printBackground":true})

Usage

Use the Chrome facade to render a URL to a temporary PDF file path.

Code (PHP):

use LastDino\ChromeLaravel\Facades\Chrome;

public function download()
{
    $url = route('home');
    $tmpPath = Chrome::pdf($url, [
        'printBackground' => true,
    ]);

    return response()->download($tmpPath, 'page.pdf')->deleteFileAfterSend();
}

Testing

- php artisan test

Code Style

- vendor/bin/pint --dirty

Troubleshooting

- Frontend changes not visible? Try: npm run dev, npm run build, or composer run dev
- Ensure Chrome/Chromium exists on the system, set CHROME_PATH if needed

License

MIT

---

README (日本語)

概要

本プロジェクトは、chrome-php/chrome を用いて Headless Chrome により Web ページを PDF 化する Laravel 12 アプリケーションの例です。packages/lastdino/chrome-laravel 配下に、ChromeManager サービスと Chrome ファサードを提供する軽量な統合パッケージが含まれています。

特長

- 実際の Chrome（ヘッドレス）で任意の URL を PDF にレンダリング
- 使いやすい既定値（例: printBackground: true）
- Chrome バイナリパス、ヘッドレス動作、カスタムフラグ、各種タイムアウトを設定可能
- プロセスを確実に終了し、ゾンビプロセスを回避

必要条件

- PHP 8.4+
- Laravel 12
- システム上に Chrome/Chromium がインストールされていること

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

主な環境変数:
- CHROME_PATH: Chrome/Chromium 実行ファイルのパス（任意）
- CHROME_HEADLESS: new | old | disabled（既定: new）
- CHROME_ARGS: 追加フラグ（例: --no-sandbox --disable-gpu）
- CHROME_CONNECT_TIMEOUT: Chrome 起動待ち秒数（既定: 10）
- CHROME_SEND_TIMEOUT: sendSync 既定タイムアウト秒数（既定: 60）
- CHROME_IDLE_TIMEOUT: ネットワークアイドル待ち秒数（既定: 60）
- CHROME_PDF_DEFAULTS: 既定の PDF オプション（JSON 文字列。例: {"printBackground":true}）

使い方

Chrome ファサードを使って URL を PDF にし、一時ファイルパスを取得します。

コード（PHP）:

use LastDino\ChromeLaravel\Facades\Chrome;

public function download()
{
    $url = route('home');
    $tmpPath = Chrome::pdf($url, [
        'printBackground' => true,
    ]);

    return response()->download($tmpPath, 'page.pdf')->deleteFileAfterSend();
}

テスト

- php artisan test

コーディング規約

- vendor/bin/pint --dirty を実行して整形してください

トラブルシューティング

- フロントエンドの変更が反映されない場合: npm run dev / npm run build / composer run dev（定義時）
- システムに Chrome/Chromium が存在することを確認し、必要に応じて CHROME_PATH を設定してください

ライセンス

MIT
