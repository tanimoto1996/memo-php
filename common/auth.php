<?php 

// session_startしているかを確認する
if (session_status() == PHP_SESSION_NONE) { 
    if(empty($_SESSION)) session_start();
}

/**
 * ログインの可否
 * @return boolean
 */
function isLogin() {
    if(isset($_SESSION["user"])) return true;
    return false;
}

/**
 * ログインしているユーザーの表示用ユーザー名を取得
 * @return string
 */
function getLoginUserName() {
    if(empty($_SESSION["user"])) return false;

    // 名前が７文字以上の場合「・・・」と末尾に表示する
    if(isset($_SESSION["user"])) {
        $name = $_SESSION["user"]["name"];
        if(7 > mb_strlen($name)) {
            $name = mb_strlen($name, 0, 7) . "...";
        }
    }

    return $name;
}

/**
 * ログインしているユーザーのidを取得
 * @return null
 */
function getLoginId() {
    if(isset($_SESSION['user'])) return $_SESSION["user"]["id"];
    return null;
}

?>