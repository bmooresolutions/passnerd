# PassNERD

**PassNERD** is a lightweight, self-hosted password manager built with PHP and SQLite. Designed for personal use on a Debian web server, it offers secure storage, OTP support, CSV import/export, and a clean web interface.

---

## Features

- ✅ Secure password encryption (AES-256-CBC)
- ✅ OTP (TOTP) integration for 2FA accounts
- ✅ CSV import/export
- ✅ Search, edit, and delete entries
- ✅ User authentication system
- ✅ Responsive UI with clean styling
- ✅ Easy to deploy on Debian/Apache/PHP

---

## Installation Guide

### Prerequisites

- Debian-based server (e.g., Debian, Ubuntu, Raspberry Pi OS)
- Apache2, PHP, SQLite3
- Composer (for OTP library)

---

### 1. Install Dependencies

```bash
sudo apt update
sudo apt install apache2 php php-sqlite3 sqlite3 unzip composer
```

### 2. Create Project Directory

```bash
sudo mkdir /var/www/passnerd
cd /var/www/passnerd
```

### 3. Clone the Repo

```bash
git clone https://github.com/yourusername/passnerd.git .
```

### 4. Set Up the SQLite Database

```bash
sqlite3 passwords.db
```

Inside the SQLite shell:

```sql
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT UNIQUE NOT NULL,
  password_hash TEXT NOT NULL
);

CREATE TABLE credentials (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  url TEXT,
  username TEXT,
  password TEXT NOT NULL,
  notes TEXT,
  otp_secret TEXT
);
.quit
```

### 5. Install OTP Library

```bash
composer require robthree/twofactorauth
```

### 6. Create Encryption Key

```bash
sudo mkdir /etc/password_manager
openssl rand -base64 32 | sudo tee /etc/password_manager/key.txt
sudo chown root:www-data /etc/password_manager/key.txt
sudo chmod 640 /etc/password_manager/key.txt
```

### 7. Add Initial User (Optional)

Create a file called `register.php` and run it once to add your admin user:

```php
<?php
$db = new PDO('sqlite:passwords.db');
$username = 'admin';
$password = 'yourStrongPassword';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->execute([$username, $hash]);
echo "User created.";
?>
```

Run it:

```bash
php register.php
```

### 8. Secure Apache (Optional)

Enable basic authentication:

```bash
sudo apt install apache2-utils
sudo htpasswd -c /etc/apache2/.htpasswd yourusername
```

Edit your Apache config:

```apache
<Directory "/var/www/passnerd">
    AuthType Basic
    AuthName "Restricted Access"
    AuthUserFile /etc/apache2/.htpasswd
    Require valid-user
</Directory>
```

Restart Apache:

```bash
sudo systemctl restart apache2
```

### 9. Enable HTTPS (Recommended)

```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache
```

---

## Usage

- Visit `http://yourserver/login.php`
- Log in with your credentials
- Use `dashboard.php` to:
  - Add new credentials
  - Search, edit, delete entries
  - View OTP codes
  - Import/export CSV files

---

## Styling

Include `style.css` in your HTML `<head>`:

```html
<link rel="stylesheet" href="style.css">
```

---

## Security Notes

- Encryption key is stored outside the web root
- Passwords are encrypted before storage
- OTP secrets are optional and stored securely
- Use HTTPS and strong server hardening practices
- Disable dangerous PHP functions in `php.ini`:

```ini
disable_functions = exec,passthru,shell_exec,system,proc_open,popen
allow_url_fopen = Off
allow_url_include = Off
```

---

##  License

MIT License — feel free to fork, modify, and contribute!

---

##  Credits

Built by BMoore 
Powered by [RobThree/TwoFactorAuth](https://github.com/RobThree/TwoFactorAuth)

---

## Contact

For questions or feedback, open an issue or reach out via GitHub.
