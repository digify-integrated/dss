<?php   
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== 1) {
    session_unset();
    session_destroy();
    header('location: index.php');
    exit();
} 
else {
    $username = $_SESSION['username'];
}
?>