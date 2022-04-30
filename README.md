# Rese-backend
Rese(リーズ)はある企業のグループ会社の飲食店予約サービスを想定したAPIです。
<br>
<br>
<br>
フロントエンドとしてrese-frontendの使用を前提としています。
<br>
[rese-frontend](https://github.com/mayu6v0/rese-fronrtend.git)

## 開発環境
* Nuxt.js　2.15.8
* Laravel　8.75

## URL
<https://rese-nuxt.herokuapp.com>


## 機能一覧
### 店舗ページ
  * エリア、ジャンル、店舗名での検索
  * お気に入り店舗登録
  * 店舗詳細情報の表示
  * 予約機能

### マイページ
  * 認証機能
  * 予約一覧の表示
  * 予約情報の変更・削除
  * お気に入り店舗の編集
  * 過去の予約一覧の表示
  * 店舗のレビュー（5段階評価とコメント）

### 管理画面
  * 店舗代表者
    * 店舗情報の作成、更新、予約情報の作成
  * 管理者
    * 店舗代表者の作成
    * メール送信機能

### その他
  * メール認証機能
  * 予約時と予約当日に認証QRコード付きのメール送信
  * レスポンシブデザイン対応


## 環境構築方法

```bash
# パッケージのインストール

$ composer install


# データベース作成

MySQLにて
$ create database [データベース名];


# 環境変数の設定

.envファイルを作成し必要に応じて環境変数を設定してください。

DB_DATABASEは上記で作成したデータベース名を設定してください。
フロントエンドのURLはFRONTEND_URLに記述します。


# テーブルの作成

$ php artisan migrate


# 暗号化処理のkeyを生成

プロジェクトフォルダ直下にて
$ php artisan key:generate

生成されたキーは自動的に.envのAPP_KEYに反映されます。


# JWT-authのシークレットキー生成

$ php artisan jwt:secret

こちらも生成されたキーは自動的に.envのJWT_SECRETに反映されます。


# ローカルサーバーの立ち上げ（localhost:8000）

$ php artisan serve

```





<!-- <p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p> -->

<!-- ## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT). -->
