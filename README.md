# Dating App 💕

日本語対応のデートアプリケーションです。ユーザーはプロフィールを作成し、他のユーザーとマッチングしてメッセージを交換できます。

## 主な機能

- ユーザー登録・ログイン
- プロフィール作成・編集
- いいね・マッチング機能
- メッセージ送受信
- アカウント削除

## 必要な環境

- PHP 7.4以上
- MySQL 5.7以上
- Webサーバー（Apache/Nginx）

## セットアップ

### 1. リポジトリのクローン

```bash
git clone <repository-url>
cd dating-app
```

### 2. 環境変数の設定

```bash
cp .env.sample .env
```

`.env`ファイルを編集してデータベース認証情報を設定：

```env
DB_HOST=localhost
DB_NAME=datingAppDB
DB_USER=your_username
DB_PASS=your_password
```

### 3. データベースの作成

`src/database/schema.sql`を実行してデータベースとテーブルを作成してください：

```bash
mysql -u root -p < src/database/schema.sql
```

または、`src/database/DATABASE_SETUP.md`を参照してください。

### 4. Webサーバーの設定

プロジェクトのルートディレクトリをWebサーバーのドキュメントルートに設定してください。

## プロジェクト構造

```
dating-app/
├── config.php              # 設定ファイル
├── .env                    # 環境変数（.gitignoreに含まれる）
├── .env.sample             # 環境変数のテンプレート
└── src/
    ├── assets/             # CSS、アイコンなどの静的ファイル
    ├── components/         # 共通コンポーネント
    ├── database/           # データベース操作
    ├── js/                 # JavaScriptファイル
    └── pages/              # ページファイル
```

## セキュリティ機能

- パスワードハッシュ化（`password_hash()`）
- SQLインジェクション対策（プリペアドステートメント）
- XSS対策（入力サニタイズ）
- ファイルアップロード検証
- セッション管理とタイムアウト

## 本番環境での注意事項

1. `.env`ファイルの権限を設定：`chmod 600 .env`
2. `config.php`で`DEBUG_MODE`を`false`に設定
3. HTTPSを使用
