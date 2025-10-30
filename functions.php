<?php
function getEncryptionKey() {
    return trim(file_get_contents('/etc/password_manager/key.txt'));
}

function encrypt($data) {
    $key = getEncryptionKey();
    $iv = random_bytes(16);
    $ciphertext = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . $ciphertext);
}

function decrypt($data) {
    $key = getEncryptionKey();
    $data = base64_decode($data);
    $iv = substr($data, 0, 16);
    $ciphertext = substr($data, 16);
    return openssl_decrypt($ciphertext, 'AES-256-CBC', $key, 0, $iv);
}
?>
