<?php
session_start();
require '../../common/validation.php';
require '../../common/database.php';

$user_email = $_POST["user_email"];
$user_password = $_POST["user_password"];

$_SESSION["errors"] = [];

// - 空チェック
emptyCheck($_SESSION['errors'], $user_email, "メールアドレスを入力してください。");
emptyCheck($_SESSION['errors'], $user_password, "パスワードを入力してください。");

// - 文字数チェック
stringMaxCheck($_SESSION['errors'], $user_email, "メールアドレスは255文字以内で入力してください。");
stringMaxCheck($_SESSION['errors'], $user_password, "パスワードは255文字以内で入力してください。");
stringMinCheck($_SESSION['errors'], $user_password, "パスワードは8文字以上で入力してください。");

if (!$_SESSION['errors']) {
    // - メールアドレスチェック
    mailAddressCheck($_SESSION['errors'], $user_email, "正しいメールアドレスを入力してください。");

    // - パスワード半角英数チェック
    halfAlphanumericCheck($_SESSION['errors'], $user_password, "パスワードは半角英数字で入力してください。");
} else {
    header("Location: ../../login/");
    exit;
}

$database_handler = getDatabaseConnection();
$sql = "SELECT id, name, password FROM users WHERE email = :user_email";
$statement = $database_handler->prepare($sql);
if ($statement) {
    $statement->bindParam(':user_email', $user_email);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        $_SESSION['errors'] = [
            "メールアドレスまたはパスワードが間違っています"
        ];
        header('Location: ../../login/');
        exit;
    }

    $name = $user["name"];
    $id = $user["id"];

    if (password_verify($user_password, $user['password'])) {
        // ユーザー情報保持
        $_SESSION['user'] = [
            'name' => $name,
            'id' => $id
        ];

        // 更新日が最新のメモ情報保持
        if ($statement = $database_handler->prepare("SELECT id, title, content FROM memos WHERE user_id = :user_id ORDER BY updated_at DESC LIMIT 1")) {
            $statement->bindParam(":user_id", $id);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $_SESSION['select_memo'] = [
                    'id' => $result['id'],
                    'title' => $result['title'],
                    'content' => $result['content']
                ];
            }
        }

        header('Location: ../../memo/index.php');
        exit;
    } else {
        $_SESSION['errors'] = [
            'メールアドレスまたはパスワードが間違っています。'
        ];
        header('Location: ../../login/');
        exit;
    }
}
