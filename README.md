# 📦 Inventory Management System / 在庫管理システム

在庫（商品）を管理するためのWebアプリケーションです。商品の登録・一覧・編集・削除（CRUD）と、ログイン認証機能を備えています。
A web application for managing product inventory, featuring full CRUD operations and login authentication.

> 🎓 ポートフォリオ作品 / Portfolio project — PHP と MySQL を用いて、セキュリティ対策を重視して開発しました。
> Built with PHP and MySQL, with a strong focus on security best practices.

---

## 🖼️ スクリーンショット / Screenshots

<!-- ここに画面のスクリーンショットを追加してください / Add your screenshots here -->
<!-- 例 / Example:
![Login](docs/login.png)
![Product List](docs/product-list.png)
-->

| ログイン画面 / Login | 商品一覧 / Product List |
|---|---|
| （画像を追加） | （画像を追加） |

---

## ✨ 主な機能 / Features

- 🔐 **ログイン / ログアウト** — セッションを用いた認証 / Session-based authentication
- 📋 **商品一覧** — カテゴリ名を結合して表示 / Product list with category names (JOIN)
- ➕ **商品登録** — 入力値の検証つき / Add products with validation
- ✏️ **商品編集** — 既存データの更新 / Edit existing products
- 🗑️ **商品削除** — POSTによる安全な削除 / Delete via POST (not GET)
- 🏷️ **カテゴリ管理** — カテゴリ未設定の商品も一覧から漏れない (LEFT JOIN)

---

## 🛠️ 技術スタック / Tech Stack

| 分類 / Category | 技術 / Technology |
|---|---|
| フロントエンド / Frontend | HTML, CSS |
| バックエンド / Backend | PHP (mysqli) |
| データベース / Database | MySQL |
| 開発環境 / Environment | XAMPP (Apache + MySQL) |
| バージョン管理 / Version control | Git, GitHub |

---

## 🔒 セキュリティ対策 / Security Measures

このプロジェクトで最も力を入れた部分です。
This is the area I focused on the most.

| 脆弱性 / Threat | 対策 / Countermeasure |
|---|---|
| **SQLインジェクション** / SQL Injection | プリペアドステートメント (`prepare` + `bind_param`) / Prepared statements |
| **XSS (クロスサイトスクリプティング)** | 出力時に `htmlspecialchars()` でエスケープ / Escape all output |
| **パスワードの漏洩** / Password leakage | `password_hash()` / `password_verify()` によるハッシュ化 (bcrypt) / Hashing with bcrypt — passwords are never stored in plain text |
| **セッションフィクセーション** / Session fixation | ログイン成功時に `session_regenerate_id(true)` / Regenerate session ID on login |
| **意図しないデータ変更** / Unintended changes | 削除・更新は必ず POST で実装 / Destructive actions use POST, never GET |
| **ユーザー列挙** / User enumeration | エラーメッセージを統一 / Uniform error messages on login failure |
| **認証情報の公開** / Credential exposure | `.gitignore` で `config/database.php` を除外し、`database.example.php` のみ公開 / DB credentials excluded from Git |

---

## 🚀 セットアップ / Setup

### 1. リポジトリをクローン / Clone the repository

```bash
git clone https://github.com/zawminthet/inventory_system.git
```

XAMPP の `htdocs` フォルダ内に配置してください。
Place it inside your XAMPP `htdocs` folder.

### 2. データベースを作成 / Create the database

phpMyAdmin で `inventory_system` という名前のデータベースを作成し、以下のテーブルを用意します。
Create a database named `inventory_system` in phpMyAdmin, with the following tables:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    category_id INT NULL,
    price INT NOT NULL,
    stock INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);
```

### 3. データベース接続設定 / Configure the database connection

`config/database.example.php` をコピーして `config/database.php` を作成し、自分の環境に合わせて編集します。
Copy `config/database.example.php` to `config/database.php` and edit it for your environment.

```bash
cp config/database.example.php config/database.php
```

### 4. アクセス / Access

```
http://localhost/inventory_system/login.php
```

---

## 💡 工夫した点 / Highlights

- **入力（登録・編集）はプリペアドステートメント、出力（一覧表示）はエスケープ** と、セキュリティ対策を「入口」と「出口」で分けて実装しました。
  Separated defenses by direction: prepared statements on input, escaping on output.
- **`LEFT JOIN`** を使い、カテゴリが未設定（NULL）の商品も一覧から漏れないようにしました。在庫管理では「商品があるのに一覧に出ない」ことが致命的だからです。
  Used `LEFT JOIN` so products with no category still appear — critical for inventory.
- **日本円は小数を扱わない**ため、価格は整数のみ受け付けるよう検証しています。
  Prices accept integers only, matching JPY (no decimals).

---

## 📚 学んだこと / What I Learned

- HTTPリクエスト（GET/POST）とサーバーサイド処理の流れ / The flow of HTTP requests and server-side processing
- Webアプリケーションにおける代表的な脆弱性とその対策 / Common web vulnerabilities and how to prevent them
- Git / GitHub を用いたバージョン管理と、認証情報を公開しない運用 / Version control and keeping secrets out of the repository

---

## 👤 作者 / Author

**ZAW MIN THET** — IT student in Fukuoka, Japan 🇯🇵
GitHub: [@zawminthet](https://github.com/zawminthet)
