<?php
include 'php/config.php';
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: php/login.php");
    exit();
}

