# Rese-backend
Rese(リーズ)はある企業のグループ会社の飲食店予約サービスを想定したAPIです。
<br>
<br>
<br>
フロントエンドとしてrese-frontendの使用を前提としています。
<br>
[rese-frontend](https://github.com/mayu6v0/rese-frontend.git)


## 開発環境
* Nuxt.js　2.15.8
* PHP 7.4.21
* Laravel 8.75

## URL
<https://rese-nuxt.herokuapp.com>


## 機能一覧
### 店舗ページ
  * エリア、ジャンル、店舗名での検索
  * お気に入り店舗登録
  * 店舗詳細情報の表示
  * レビューの表示
  * 予約機能
  * カード決済機能

### マイページ
  * 認証機能
  * 予約一覧の表示
  * 予約情報の変更・削除
  * お気に入り店舗の編集
  * 過去の予約一覧の表示
  * 店舗のレビュー（5段階評価とコメント）

### 管理画面
  * 店舗代表者
    * 店舗情報の作成、更新
    * 店舗画像をストレージ（s3）に保存
    * 予約一覧の表示
  * 管理者
    * 店舗代表者の作成
    * メール送信機能

### その他
  * メール認証機能
  * 予約時と予約当日に認証QRコード付きのメール送信
  * レスポンシブデザイン対応


## 環境構築方法


### パッケージのインストール

```bash
$ composer install
```

### データベース作成

MySQLにて
```bash
$ create database [データベース名];
```

### 環境変数の設定
.env.sample.を.envにリネームして環境変数を設定してください。

DB_DATABASEは上記で作成したデータベース名を設定してください。

フロントエンドのURLはFRONTEND_URLに記述します。


### テーブルの作成
```bash
$ php artisan migrate
```

### 暗号化処理のkeyを生成
```bash
$ php artisan key:generate
```
生成されたキーは自動的に.envのAPP_KEYに反映されます。


### JWT-authのシークレットキー生成
```bash
$ php artisan jwt:secret
```
こちらも生成されたキーは自動的に.envのJWT_SECRETに反映されます。


### ローカルサーバーの立ち上げ（localhost:8000）
```bash
$ php artisan serve
```

