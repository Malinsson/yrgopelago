<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require_once __DIR__ . '/../app/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$errorMessage = '';

if (isset($_SESSION['adminLoggedIn']) && $_SESSION['adminLoggedIn'] === true) {
    header('Location: ../admin.php');
    exit();
} elseif (isset($_POST['login'])) {
    $username = $_POST['user'];
    $password = $_POST['password'];

    $adminUser = $_ENV['ADMIN_USER'] ?? $_ENV['admin_user'];
    $adminPass = $_ENV['ADMIN_KEY'] ?? $_ENV['admin_key'];

    // Debug: Check what values we got
    error_log("Username: $username, Password: $password");
    error_log("Admin User: $adminUser, Admin Pass: $adminPass");

    if ($username === $adminUser && $password === $adminPass) {
        $_SESSION['adminLoggedIn'] = true;
        header('Location: ../admin.php');
        exit();
    } else {
        $errorMessage = 'Invalid username or password.';

        exit();
    }
}
