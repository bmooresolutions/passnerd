<?php
require 'functions.php';
require 'vendor/autoload.php';
use RobThree\Auth\TwoFactorAuth;

$tfa = new TwoFactorAuth('PassNERD');
$secret = $tfa->createSecret();
$qrCode = $tfa->getQRCodeImageAsDataUri('PassNERD', $secret);

$db = new PDO('sqlite:passwords.db');
$username = 'admin';
$password = 'yourStrongPassword';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (username, password_hash, otp_secret) VALUES (?, ?, ?)");
$stmt->execute([$username, $hash, $secret]);

echo "<h2>User created with OTP</h2>";
echo "<p>Scan this QR code with your authenticator app:</p>";
echo "<img src='$qrCode'>";
echo "<p>OTP Secret: <strong>$secret</strong></p>";
