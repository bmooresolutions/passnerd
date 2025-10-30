<?php
$db = new PDO('sqlite:passwords.db');
$username = 'admin';
$password = 'yourStrongPassword';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->execute([$username, $hash]);
echo "User created.";
