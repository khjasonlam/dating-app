# データベースセットアップ

このディレクトリにはデータベース操作に関するファイルが含まれています。

## データベースの作成

以下のSQLを実行してデータベースとテーブルを作成してください。

```sql
CREATE DATABASE datingAppDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE datingAppDB;

-- Usersテーブル
CREATE TABLE Users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    loginId VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    username VARCHAR(100) NOT NULL,
    gender ENUM('男', '女') NOT NULL,
    age INT NOT NULL,
    bloodType VARCHAR(10),
    location VARCHAR(100),
    interests TEXT,
    description TEXT,
    height INT,
    weight INT,
    education VARCHAR(100),
    occupation VARCHAR(100),
    smokingHabits VARCHAR(50),
    drinkingHabits VARCHAR(50),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User_Picturesテーブル
CREATE TABLE User_Pictures (
    pictureId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    pictureName VARCHAR(255),
    pictureType VARCHAR(50),
    pictureContents LONGTEXT,
    FOREIGN KEY (userId) REFERENCES Users(userId) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User_Interactionsテーブル
CREATE TABLE User_Interactions (
    interactionId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    targetUserId INT NOT NULL,
    interactionType ENUM('like', 'dislike') NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES Users(userId) ON DELETE CASCADE,
    FOREIGN KEY (targetUserId) REFERENCES Users(userId) ON DELETE CASCADE,
    UNIQUE KEY unique_interaction (userId, targetUserId, interactionType)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Messagesテーブル
CREATE TABLE Messages (
    messageId INT AUTO_INCREMENT PRIMARY KEY,
    senderId INT NOT NULL,
    receiverId INT NOT NULL,
    messageContent TEXT NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (senderId) REFERENCES Users(userId) ON DELETE CASCADE,
    FOREIGN KEY (receiverId) REFERENCES Users(userId) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## ファイル説明

- `Pdo.php` - データベース接続設定
- `LoginStatus.php` - ログイン状態のチェック
- `ProcessLogin.php` - ログイン処理
- `ProcessRegister.php` - ユーザー登録処理
- `ProcessMessage.php` - メッセージ送信処理
- `ProcessInteractions.php` - いいね/マッチング処理
- `SelectProfileItem.php` - プロフィール情報の取得
- `SelectMessage.php` - メッセージの取得
- `SelectInteractions.php` - いいね一覧の取得
- `SelectMatchedList.php` - マッチング一覧の取得
- `UpdateEditProfile.php` - プロフィール更新処理
- `DeleteAccount.php` - アカウント削除処理
