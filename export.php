<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = new PDO('sqlite:/var/www/passwords.db');
$rows = $db->query("SELECT * FROM credentials")->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="passwords_export.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['name', 'url', 'username', 'password', 'notes', 'otp_secret']);

foreach ($rows as $row) {
    fputcsv($output, [
        $row['name'],
        $row['url'],
        $row['username'],
        decrypt($row['password']),
        $row['notes'],
        $row['otp_secret']
    ]);
}
fclose($output);

