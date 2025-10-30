<?php
session_start();
require 'functions.php';
$db = new PDO('sqlite:passwords.db');

if ($_FILES['csv']['tmp_name']) {
    $file = fopen($_FILES['csv']['tmp_name'], 'r');
    fgetcsv($file); // skip header
    while ($row = fgetcsv($file)) {
        $stmt = $db->prepare("INSERT INTO credentials (name, url, username, password, notes, otp_secret) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $row[0], $row[1], $row[2], encrypt($row[3]), $row[4], $row[5]
        ]);
    }
    fclose($file);
}
header('Location: dashboard.php');
