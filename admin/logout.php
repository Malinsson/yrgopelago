<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/autoload.php';

unset($_SESSION['adminLoggedIn']);
$_SESSION['adminLoggedIn'] = false;
header('Location: ../index.php');
exit();
