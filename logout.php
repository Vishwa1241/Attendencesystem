<?php
require_once __DIR__ . '/config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_valid()) logout_admin();
header('Location: ' . BASE_URL . '/login.php');
exit;
