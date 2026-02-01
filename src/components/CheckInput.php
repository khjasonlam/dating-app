<?php

/**
 * 入力検証とセッション管理関数
 */

// セッションが開始されていない場合は開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 設定ファイルの読み込み
require_once(__DIR__ . '/../config.php');

/**
 * 入力値をサニタイズ
 * @param string $data 入力データ
 * @return string サニタイズされたデータ
 */
function testInputValue($data)
{
    if (!isset($data)) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
}

/**
 * 値をチェックしてサニタイズし、空の場合はデフォルト値を返す
 * @param string $data 入力データ
 * @return string サニタイズされたデータまたはデフォルトメッセージ
 */
function checkValue($data)
{
    if (empty($data)) {
        $data = "入力なし";
    }
    return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
}

/**
 * パスワードの強度を検証
 * @param string $password 検証するパスワード
 * @return array ['valid' => bool, 'message' => string]
 */
function validatePassword($password)
{
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        return [
            'valid' => false,
            'message' => 'パスワードは' . PASSWORD_MIN_LENGTH . '文字以上である必要があります'
        ];
    }

    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        return [
            'valid' => false,
            'message' => 'パスワードは英字と数字を含む必要があります'
        ];
    }

    return ['valid' => true, 'message' => ''];
}

/**
 * アップロードされた画像ファイルを検証
 * @param array $file $_FILES配列の要素
 * @return array ['valid' => bool, 'message' => string]
 */
function validateImageFile($file)
{
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return [
            'valid' => false,
            'message' => 'ファイルのアップロードに失敗しました'
        ];
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return [
            'valid' => false,
            'message' => '画像サイズが' . (MAX_FILE_SIZE / 1024 / 1024) . 'MBを超えました'
        ];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        return [
            'valid' => false,
            'message' => '許可されていないファイル形式です。JPEG、PNG、GIF、WebPのみ許可されています'
        ];
    }

    return ['valid' => true, 'message' => ''];
}

/**
 * セッションにエラーメッセージを設定
 * @param string $message エラーメッセージ
 */
function setErrorMessage($message)
{
    $_SESSION["error_message"] = $message;
}

/**
 * セッションからエラーメッセージを表示してクリア
 */
function displayErrorMessage()
{
    if (isset($_SESSION["error_message"])) {
        $message = $_SESSION["error_message"];
        // <br>タグを許可してHTMLとして解釈させる（XSS対策のため、他のHTMLタグはエスケープ）
        // まず、<br>タグを一時的なプレースホルダーに置き換え
        $message = str_replace(['<br>', '<br/>', '<br />'], '___BR_TAG___', $message);
        // すべてのHTMLをエスケープ
        $message = htmlspecialchars($message, ENT_QUOTES, "UTF-8");
        // プレースホルダーを<br>タグに戻す
        $message = str_replace('___BR_TAG___', '<br>', $message);
        echo $message;
        unset($_SESSION["error_message"]);
    }
}

/**
 * セッションにユーザーIDを設定
 * @param int $userId ユーザーID
 */
function setUserIdSession($userId)
{
    $_SESSION["userId"] = (int)$userId;
    $_SESSION["last_activity"] = time();
}

/**
 * セッションからユーザーIDを取得
 * @return int|null ユーザーID、設定されていない場合はnull
 */
function getUserIdSession()
{
    // セッションタイムアウトをチェック
    if (
        isset($_SESSION["last_activity"]) &&
        (time() - $_SESSION["last_activity"] > SESSION_LIFETIME)
    ) {
        unsetAllSession();
        return null;
    }

    if (isset($_SESSION["last_activity"])) {
        $_SESSION["last_activity"] = time();
    }

    return isset($_SESSION["userId"]) ? (int)$_SESSION["userId"] : null;
}

/**
 * マッチしたユーザーのセッションフラグを設定
 */
function setMatchedUserSession()
{
    $_SESSION["matched"] = true;
}

/**
 * マッチしたユーザーのセッションフラグを取得してクリア
 * @return bool マッチフラグが設定されていた場合はtrue
 */
function getMatchedUserSession()
{
    if (isset($_SESSION["matched"])) {
        $matched = true;
        unset($_SESSION["matched"]);
        return $matched;
    }
    return false;
}

/**
 * ユーザーのログインを必須とする
 * ログインしていない場合はログインページにリダイレクト
 * @return int ログインしている場合のユーザーID（ログインしていない場合は戻らない）
 */
function requireLogin()
{
    $userId = getUserIdSession();

    if (!$userId) {
        setErrorMessage("ログインが必要です");
        header("Location: ../pages/Login.php");
        exit;
    }

    return $userId;
}

/**
 * すべてのセッションデータをクリアしてログインページにリダイレクト
 */
function unsetAllSession()
{
    session_unset();
    session_destroy();
    header("Location: ../pages/Login.php");
    exit();
}
