<?php

session_start();

if ($_SESSION['logged_in'] != 1) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}
else {
    $username = $_SESSION['username'];
}
?>