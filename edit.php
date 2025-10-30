<?php
session_start();
$db = new PDO('sqlite:/var/www/passwords.db');

$id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM credentials WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("UPDATE credentials SET name=?, url=?, username=?, password=?, notes=?, otp_secret=? WHERE id=?");
    $stmt->execute([
        $_POST['name'],
        $_POST['url'],
        $_POST['username'],
        encrypt($_POST['password']),
        $_POST['notes'],
        $_POST['otp_secret'],
        $id
    ]);
    header('Location: dashboard.php');
    exit;
}
?>

<form method="POST">
  <input name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
  <input name="url" value="<?= htmlspecialchars($row['url']) ?>">
  <input name="username" value="<?= htmlspecialchars($row['username']) ?>">
  <input name="password" value="<?= htmlspecialchars(decrypt($row['password'])) ?>" required>
  <textarea name="notes"><?= htmlspecialchars($row['notes']) ?></textarea>
  <input name="otp_secret" value="<?= htmlspecialchars($row['otp_secret']) ?>">
  <button type="submit">Update</button>
</form>
