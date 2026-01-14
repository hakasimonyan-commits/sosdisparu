<?php
session_start();

// Եթե user-ը login չի արել → ուղարկում ենք login էջ
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
