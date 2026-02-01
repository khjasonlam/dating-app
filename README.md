# Dating App 💕

日本語対応のデートアプリケーションです。ユーザーはプロフィールを作成し、他のユーザーとマッチングしてメッセージを交換できます。

## 主な機能

- ユーザー登録・ログイン
- プロフィール作成・編集
- いいね・マッチング機能
- メッセージ送受信
- アカウント削除

## 必要な環境

以下のソフトウェアがインストールされている必要があります：

- **PHP 7.4以上** - `php -v`でバージョンを確認
- **MySQL 5.7以上** - `mysql --version`でバージョンを確認
- **Webサーバー**（Apache/Nginx、またはPHPビルトインサーバー）

## セットアップ

### 1. リポジトリのクローン

```bash
git clone <repository-url>
cd dating-app
```

### 2. データベースのセットアップ

MySQLにログインしてデータベースを作成します：

```bash
mysql -u root -p
```

MySQLプロンプトで以下を実行：

```sql
source src/database/schema.sql
```

または、コマンドラインから直接実行：

```bash
mysql -u root -p < src/database/schema.sql
```

詳細は`src/database/DATABASE_SETUP.md`を参照してください。

### 3. 環境変数の設定

プロジェクトルートに`.env`ファイルを作成し、データベース認証情報を設定します：

```bash
# .envファイルを編集
nano .env
```

以下の内容を設定（必要に応じて値を変更）：

```env
DB_HOST=localhost
DB_NAME=datingAppDB
DB_USER=root
DB_PASS=your_password
```

### 4. アップロードディレクトリの確認

`uploads/`ディレクトリが存在することを確認します（既に作成済み）：

```bash
ls -la uploads/
```

書き込み権限があることを確認：

```bash
chmod 755 uploads/
```

### 5. Webサーバーの起動

#### オプションA: PHPビルトインサーバー（推奨・開発用）

プロジェクトルートディレクトリで以下を実行：

```bash
php -S localhost:8000
```

ブラウザで `http://localhost:8000` にアクセスします。

#### オプションB: Apache/Nginx

ApacheまたはNginxを使用する場合：

**Apacheの場合：**
1. `httpd.conf`または`.htaccess`でプロジェクトルートを設定
2. Apacheを再起動
3. `http://localhost/dating-app` にアクセス

**Nginxの場合：**
1. 設定ファイルでプロジェクトルートを設定
2. Nginxを再起動
3. 設定したURLにアクセス

### 6. 動作確認

1. ブラウザで `http://localhost:8000` にアクセス
2. ログインページが表示されることを確認
3. 「新規登録」からテストユーザーを作成
4. ログインして動作を確認

## プロジェクト構造

```
dating-app/
├── .env                    # 環境変数（.gitignoreに含まれる）
├── .env.sample             # 環境変数のテンプレート
├── index.php               # エントリーポイント
└── src/
    ├── assets/             # CSS、アイコンなどの静的ファイル
    ├── components/         # 共通コンポーネント
    ├── config.php          # 設定ファイル
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

## トラブルシューティング

### データベース接続エラー

- `.env`ファイルの設定を確認
- MySQLが起動していることを確認：`mysql.server start`（macOS）または`sudo systemctl start mysql`（Linux）
- データベースが作成されていることを確認：`mysql -u root -p -e "SHOW DATABASES;"`

### ファイルアップロードエラー

- `uploads/`ディレクトリの権限を確認：`chmod 755 uploads/`
- `config.php`の`UPLOAD_DIR`設定を確認

### PHPエラー

- PHPのバージョンを確認：`php -v`
- エラーログを確認：`tail -f /var/log/php_errors.log`（設定による）

## 次のステップ

- テストユーザーを作成して機能を確認
- プロフィール写真をアップロードして動作確認
- マッチング機能をテスト

## 本番環境での注意事項

1. `.env`ファイルの権限を設定：`chmod 600 .env`
2. `config.php`で`DEBUG_MODE`を`false`に設定
3. HTTPSを使用
4. データベースのバックアップを設定