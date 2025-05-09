<?php
session_start();
if($_SESSION['role'] == 'admin'){
    session_unset();
    session_destroy();
    header("Location: admin/landing_page.php");
}else{
    session_unset();
    session_destroy();
    header("Location: /index.php");
}
?>