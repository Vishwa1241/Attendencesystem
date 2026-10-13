<?php
require_once __DIR__ . '/config/auth.php';
header('Location: ' . BASE_URL . (is_logged_in() ? '/admin/dashboard.php' : '/login.php'));
exit;
