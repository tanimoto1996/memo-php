<?php 

session_start();

require "./common/auth.php";

if(isLogin()) {
    header("Location: ./memo/index.php");
    exit;
}

header("Location: ./login/");

?>