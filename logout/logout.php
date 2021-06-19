<?php 
    session_start();
    include_once "../common/auth.php";

    $isLogin = isLogin();
    if($isLogin) {
        $_SESSION = array();
        session_destroy();
        header('Location: ../login/');
    }
?>