<?php
session_start();
require 'functions.php';
require 'vendor/autoload.php';
use RobThree\Auth\TwoFactorAuth;

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = new PDO('sqlite:passwords.db');
$tfa = new TwoFactorAuth('MyPasswordManager');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("INSERT INTO credentials (name, url, username, password, notes, otp_secret) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['name'],
        $_POST['url'],
        $_POST['username'],
        encrypt($_POST['password']),
        $_POST['notes'],
        $_POST['otp_secret']
    ]);
}

$search = $_GET['search'] ?? '';
$stmt = $db->prepare("SELECT * FROM credentials WHERE name LIKE ? OR url LIKE ? OR username LIKE ?");
$stmt->execute(["%$search%", "%$search%", "%$search%"]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PassNERD Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div style="display: flex; justify-content: space-between; align-items: center;">
  <h1>PassNERD</h1>
  <img src="logo.png" alt="PassNERD Logo" style="height: 60px;">
</div>

<a href="logout.php">Logout</a>

<form method="POST">
  <input name="name" placeholder="Account Name" required>
  <input name="url" placeholder="Website URL">
  <input name="username" placeholder="Username">
  <input name="password" type="password" placeholder="Password" required>
  <textarea name="notes" placeholder="Notes"></textarea>
  <input name="otp_secret" placeholder="OTP Secret (optional)">
  <button type="submit">Save</button>
</form>

<form method="GET">
  <input name="search" placeholder="Search by name, URL, or username">
  <button type="submit">Search</button>
</form>

<form method="POST" action="import.php" enctype="multipart/form-data">
  <input type="file" name="csv" accept=".csv" required>
  <button type="submit">Import CSV</button>
</form>

<form method="POST" action="export.php">
  <button type="submit">Export to CSV</button>
</form>

<table>
  <tr><th>Name</th><th>URL</th><th>Username</th><th>Password</th><th>Notes</th><th>OTP</th><th>Actions</th></tr>
  <?php foreach ($rows as $row): ?>
    <tr>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><a href="<?= htmlspecialchars($row['url']) ?>" target="_blank">Visit</a></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= htmlspecialchars(decrypt($row['password'])) ?></td>
      <td><?= nl2br(htmlspecialchars($row['notes'])) ?></td>
      <td><?= $row['otp_secret'] ? $tfa->getCode($row['otp_secret']) : '' ?></td>
      <td>
        <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
        <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

</body>
</html>
