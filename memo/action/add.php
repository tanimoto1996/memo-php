<?php
require '../../common/auth.php';
require '../../common/database.php';

if (!isLogin()) {
    header('Location: ../login/');
    exit;
}

$user_id = getLoginId();
$database_handler = getDatabaseConnection();

try {
    $title = "新規メモ";
    $sql = "INSERT INTO memos (user_id, title, content) VALUES(:user_id, :title, null)";
    $statement = $database_handler->prepare($sql);
    if ($statement) {
        $statement->bindParam(":user_id", $user_id);
        $statement->bindParam(":title", $title);
        $statement->execute();
    }

    $_SESSION['select_memo'] = [
        'id' => $database_handler->lastInsertId(),
        'title' => $title,
        'content' => '',
    ];

} catch (Throwable $e) {
    echo $e->getMessage();
    exit;
}



header('Location: ../../memo/index.php');
exit;
