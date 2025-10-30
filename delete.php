<?php
session_start();
$db = new PDO('sqlite:/var/www/passwords.db');

$id = $_GET['id'];
$stmt = $db->prepare("DELETE FROM credentials WHERE id = ?");
$stmt->execute([$id]);

header('Location: dashboard.php');
exit;
