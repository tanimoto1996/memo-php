<?php 

/**
 * 空チェック
 * @param $errors
 * @param $check_value
 * @param $message
 */
function emptyCheck(&$errors, $check_value, $message) {
    if(empty(trim($check_value))) {
        array_push($errors, $message);
    }
}

/** 
 * 最小文字数チェック
 * @param $errors
 * @param $check_value
 * @param $message
 * @param int $min_size
 */ 
function stringMinCheck(&$errors, $check_value, $message, $min_size = 0) {
    if(mb_strlen($check_value) < $min_size) array_push($errors, $message);
}

/**
 * 最大文字数チェック
 * @param $errors
 * @param $check_value
 * @param $message
 * @param int $max_size
 */ 
function stringMaxCheck(&$errors, $check_value, $message, $max_size = 255) {
    if(mb_strlen($check_value) > $max_size) array_push($errors, $message);
}

/** 
 * メールアドレスチェック
 * @param $errors
 * @param $check_value
 * @param $message
 */ 
function mailAddressCheck(&$errors, $check_value, $message) {
    if(filter_var($check_value, FILTER_VALIDATE_EMAIL) == false) {
        array_push($errors, $message);
    }
}

/**
 * 半角英数字チェック
 * @param $errors
 * @param $check_value
 * @param $message
 */
function halfAlphanumericCheck(&$errors, $check_value, $message) {
    if(preg_match("/^[a-zA-Z0-9]+$/", $check_value) == false) {
        array_push($errors, $message);
    }
}

/**
 * メールアドレス重複チェック
 * @param $errors
 * @param $check_value
 * @param $message
 */
function mailAddressDuplicationCheck(&$errors, $check_value, $message) {
    $database_handler = getDatabaseConnection();
    $sql = "SELECT email FROM users where :user_email";
    $statement = $database_handler->prepare($sql);
    if($statement) {
        $statement->bindParam(":user_email", $check_value);
        $statement->execute();
    }

    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if($result)  {
        array_push($errors, $message);
    }
}

?>