<?php
    session_start();
    require "../../common/validation.php";
    require "../../common/database.php";

    $user_name = $_POST["user_name"];
    $user_email = $_POST["user_email"];
    $user_password = $_POST["user_password"];

    $_SESSION["errors"] = [];

    // 空文字チェック
    emptyCheck($_SESSION["errors"], $user_name, "ユーザーの名前を入力してください");
    emptyCheck($_SESSION["errors"], $user_email, "メールアドレスを入力してください");
    emptyCheck($_SESSION["errors"], $user_password, "パスワードを入力してください");

    // - 文字数チェック
    stringMaxCheck($_SESSION['errors'], $user_name, "ユーザー名は255文字以内で入力してください。");
    stringMaxCheck($_SESSION['errors'], $user_email, "メールアドレスは255文字以内で入力してください。");
    stringMaxCheck($_SESSION['errors'], $user_password, "パスワードは255文字以内で入力してください。");
    stringMinCheck($_SESSION['errors'], $user_password, "パスワードは8文字以上で入力してください。");

    if(!$_SESSION['errors']) {
        // - メールアドレスチェック
        mailAddressCheck($_SESSION['errors'], $user_email, "正しいメールアドレスを入力してください。");

        // - ユーザー名・パスワード半角英数チェック
        halfAlphanumericCheck($_SESSION['errors'], $user_name, "ユーザー名は半角英数字で入力してください。");
        halfAlphanumericCheck($_SESSION['errors'], $user_password, "パスワードは半角英数字で入力してください。");

        // - メールアドレス重複チェック
        mailAddressDuplicationCheck($_SESSION['errors'], $user_email, "既に登録されているメールアドレスです。");
    } else {
        header ('Location: ../index.php');
        exit;
    }

    // PDMでDBに接続
    $database_handler = getDatabaseConnection();

    try {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $statement = $database_handler->prepare($sql);
        if($statement)
        {
            $password = password_hash($user_password, PASSWORD_DEFAULT);

            $statement->bindParam(':name', htmlspecialchars($user_name));
            $statement->bindParam(':email', htmlspecialchars($user_email));
            $statement->bindParam(':password', htmlspecialchars($password));
            // executeでSQL実行
            $statement->execute();

            $_SESSION["user"] = [
                "name" => $user_name,
                "id" => $database_handler->lastInsertId()
            ];
        }
    } catch (Throwable $e) {
        echo $e->getMessage();
        exit;
    }

    header("Location: ../../memo/");
    exit;
?>