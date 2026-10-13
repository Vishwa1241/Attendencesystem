<?php
// Serves private images only to logged-in admins. Files live in uploads/ which is blocked from direct web access.
require_once __DIR__ . '/config/auth.php';
require_login();
$map = ['faculty'   => ['faculty', 'photo_path', 'faculty'],
        'face'      => ['student_face_refs', 'file_name', 'faces'],
        'classroom' => ['classroom_photos', 'file_path', 'attendance']];
$t = $_GET['t'] ?? '';
if (!isset($map[$t])) { http_response_code(404); exit; }
[$table, $col, $dir] = $map[$t];
$st = db()->prepare("SELECT `$col` FROM `$table` WHERE id = ?"); $st->execute([(int)($_GET['id'] ?? 0)]);
$f = (string)$st->fetchColumn(); $path = __DIR__ . "/uploads/$dir/$f";
if (!preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/', $f, $m) || !is_file($path)) { http_response_code(404); exit; }
header('Content-Type: ' . ['jpg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'][$m[1]]);
header('Cache-Control: private, max-age=600'); header('Content-Length: ' . filesize($path)); readfile($path);
