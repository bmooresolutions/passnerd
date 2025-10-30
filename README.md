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

## Installation

1. **Install dependencies**  
   ```bash
   sudo apt update
   sudo apt install apache2 php php-sqlite3 sqlite3 unzip composer

