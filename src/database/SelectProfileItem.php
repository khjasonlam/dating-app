<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

// 表示するユーザーIDの検証と取得
if (isset($_GET["targetUserId"])) {
    $targetUserId = (int)$_GET["targetUserId"];
    $currentUserId = getUserIdSession();

    if ($targetUserId === $currentUserId) {
        header("Location: ../pages/Profile.php");
        exit();
    } elseif ($targetUserId > 0) {
        $displayUserId = $targetUserId;
    } else {
        setErrorMessage("無効なユーザーIDです");
        header("Location: ../pages/Profile.php");
        exit();
    }
} elseif (isset($_GET["messageUserId"])) {
    $messageUserId = (int)$_GET["messageUserId"];
    if ($messageUserId > 0) {
        $displayUserId = $messageUserId;
    } else {
        setErrorMessage("無効なユーザーIDです");
        header("Location: ../pages/Profile.php");
        exit();
    }
} else {
    $displayUserId = requireLogin();
}

try {
    $profileSql =
        "SELECT u.userId, u.username, u.gender, u.age,
        u.bloodType, u.location, u.interests,
        u.description, u.height, u.weight, u.education,
        u.occupation, u.smokingHabits, u.drinkingHabits,
        up.pictureContents, up.pictureType
        FROM Users u LEFT JOIN User_Pictures up
        ON u.userId = up.userId WHERE u.userId = ?";

    $stmt = $conn->prepare($profileSql);

    $stmt->bindValue(1, $displayUserId);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($result)) {
        setErrorMessage("ユーザーが見つかりません");
        header("Location: ../pages/Profile.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("SelectProfileItem error: " . $e->getMessage());
    setErrorMessage("プロフィールの取得に失敗しました");
    header("Location: ../pages/Profile.php");
    exit();
}

$userId = checkValue($result['userId'] ?? '');
$username = checkValue($result['username']);
$gender = checkValue($result['gender']);
$age = $result['age'];
$bloodType = checkValue($result['bloodType']);
$location = checkValue($result['location']);
$interests = checkValue($result['interests']);
$description = checkValue($result['description']);
$height = checkValue($result['height']);
$weight = checkValue($result['weight']);
$education = checkValue($result['education']);
$occupation = checkValue($result['occupation']);
$smokingHabits = checkValue($result['smokingHabits']);
$drinkingHabits = checkValue($result['drinkingHabits']);

$pictureContents = checkValue($result['pictureContents']);
$pictureType = checkValue($result['pictureType']);

$profileArray = [
    "性別" => $gender,
    "年齢" => $age,
    "血液型" => $bloodType,
    "出身地" => $location,
    "趣味" => $interests,
    "自己紹介" => $description,
    "身長" => $height,
    "体重" => $weight,
    "学歴" => $education,
    "職業" => $occupation,
    "喫煙" => $smokingHabits,
    "飲酒" => $drinkingHabits
];

// 現在のユーザーがこのプロフィールにいいねしているか、マッチングしているかを確認
if (isset($_GET["targetUserId"]) && (int)$_GET["targetUserId"] === $displayUserId) {
    $currentUserId = getUserIdSession();
    if ($currentUserId) {
        $checkLikedSql =
            "SELECT userId, targetUserId FROM User_Interactions
            WHERE userId = ? AND targetUserId = ? AND interactionType = 'like'
            LIMIT 1";
        try {
            $stmt = $conn->prepare($checkLikedSql);
            $stmt->bindValue(1, $currentUserId);
            $stmt->bindValue(2, $displayUserId);
            $stmt->execute();

            $liked = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($liked) {
                // 相手もいいねを返しているか確認（マッチング）
                $stmt = $conn->prepare($checkLikedSql);
                $stmt->bindValue(1, $displayUserId);
                $stmt->bindValue(2, $currentUserId);
                $stmt->execute();

                $matched = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            error_log("Check liked error: " . $e->getMessage());
            // ユーザーにエラーを表示せず、処理を続行
        }
    }
}
