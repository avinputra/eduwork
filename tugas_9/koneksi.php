<?php
// ==========================================
// FILE: koneksi.php
// ==========================================

$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; 
$db_name = 'eduwork';
$folder_upload = 'upload_gambar/';

$db_error = '';
$pdo = null;

if (!is_dir($folder_upload)) {
    mkdir($folder_upload, 0777, true);
}

try {
    $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $db_name");
    $pdo->exec("USE $db_name");

    // Tabel Product
    $sql_create_table = "CREATE TABLE IF NOT EXISTS product (
        id_product INT AUTO_INCREMENT PRIMARY KEY,
        nama_product VARCHAR(100) NOT NULL,
        deskripsi_product TEXT NOT NULL,
        gambar1_product VARCHAR(255) DEFAULT NULL,
        gambar2_product VARCHAR(255) DEFAULT NULL,
        gambar3_product VARCHAR(255) DEFAULT NULL,
        kategori_product INT NOT NULL,
        harga_product DECIMAL(15,2) NOT NULL,
        stok_product INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_create_table);

    // Tabel Cart (BARU)
    $sql_create_cart = "CREATE TABLE IF NOT EXISTS cart (
        id_cart INT AUTO_INCREMENT PRIMARY KEY,
        id_product INT NOT NULL,
        quantity INT NOT NULL,
        session_id VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_product) REFERENCES product(id_product) ON DELETE CASCADE
    )";
    $pdo->exec($sql_create_cart);

    // Cek kolom updated_at
    $check_col = $pdo->query("SHOW COLUMNS FROM product LIKE 'updated_at'");
    if ($check_col->rowCount() == 0) {
        $pdo->exec("ALTER TABLE product ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }

} catch (PDOException $e) {
    $db_error = "Gagal terhubung ke Database: " . $e->getMessage();
}
?>