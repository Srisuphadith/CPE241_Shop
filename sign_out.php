<?php
session_start();
if($_SESSION['role'] == 'admin'){
    session_unset();
    session_destroy();
    header("Location: /admin/sign_in.php");
}else{
    session_unset();
    session_destroy();
    header("Location: /index.php");
}
?>