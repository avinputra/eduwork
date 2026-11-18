<?php
// ==========================================
// 1. KONFIGURASI DATABASE & KONEKSI (MySQLi Procedural)
// ==========================================
$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; 
$db_name = 'eduwork';

$db_error = '';
$conn = null;

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($db_host, $db_user, $db_pass);
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $db_name");
    mysqli_select_db($conn, $db_name);

    // Buat tabel jika belum ada
    $sql_create_table = "CREATE TABLE IF NOT EXISTS product (
        id_produk INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        harga DECIMAL(15,2) NOT NULL,
        deskripsi TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $sql_create_table);

} catch (mysqli_sql_exception $e) {
    $db_error = "Gagal terhubung atau menyiapkan Database: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form & Data Barang - Eduwork</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 15px 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .btn-custom {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
        }
        .form-control:focus {
            border-color: #0dcaf0;
            box-shadow: 0 0 0 0.25rem rgba(13, 202, 240, 0.25);
        }
        
        /* Styling Khusus Div Tabel (Baris & Kolom) */
        .div-table-header {
            background-color: #e9ecef;
            font-weight: 700;
            color: #495057;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .div-table-row {
            background-color: #ffffff;
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .div-table-row:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .div-table-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        /* Label untuk tampilan Mobile */
        .mobile-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }
        
        /* Warna Harga */
        .price-text {
            color: #198754;
            font-weight: 700;
        }
        
        /* Badge ID */
        .badge-id {
            background-color: #e2e8f0;
            color: #475569;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        
        <!-- BAGIAN 1: FORM INPUT -->
        <div class="col-lg-10">
            <div class="card card-custom">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0 text-center"><i class="bi bi-box-seam me-2"></i>Input Data Barang</h5>
                </div>
                
                <div class="card-body p-4">
                    
                    <?php if ($db_error): ?>
                        <div class="alert alert-danger">
                            <strong>Error Sistem:</strong> <?= htmlspecialchars($db_error) ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    $errors = [];
                    $success = false;

                    if (isset($_POST['submit_data']) && !$db_error) {
                        $nama = trim($_POST['nama'] ?? '');
                        $harga = trim($_POST['harga'] ?? '');
                        $deskripsi = trim($_POST['deskripsi'] ?? '');

                        if (empty($nama)) $errors[] = "Nama barang tidak boleh kosong.";
                        if (empty($harga)) {
                            $errors[] = "Harga barang tidak boleh kosong.";
                        } else if (!is_numeric($harga)) {
                            $errors[] = "Inputan harga barang harus berupa angka.";
                        }
                        if (empty($deskripsi)) $errors[] = "Deskripsi barang tidak boleh kosong.";

                        if (empty($errors)) {
                            try {
                                $stmt = mysqli_prepare($conn, "INSERT INTO product (nama, harga, deskripsi) VALUES (?, ?, ?)");
                                mysqli_stmt_bind_param($stmt, "sds", $nama, $harga, $deskripsi);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);
                                $success = true;
                            } catch (mysqli_sql_exception $e) {
                                $errors[] = "Gagal menyimpan ke database: " . $e->getMessage();
                            }
                        }
                    }
                    ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Oops! Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><strong><i class="bi bi-check-circle me-2"></i>Berhasil Disimpan!</strong></h6>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Barang</label>
                                <input type="text" class="form-control" id="nama" name="nama" 
                                       placeholder="Contoh: Laptop ASUS" 
                                       value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" <?= $db_error ? 'disabled' : '' ?>>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="harga" class="form-label fw-bold">Harga Barang</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" id="harga" name="harga" 
                                           placeholder="Contoh: 15000000" 
                                           value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>" <?= $db_error ? 'disabled' : '' ?>>
                                </div>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="deskripsi" class="form-label fw-bold">Deskripsi Barang</label>
                                <input type="text" class="form-control" id="deskripsi" name="deskripsi" 
                                          placeholder="Spesifikasi singkat..." 
                                          value="<?= htmlspecialchars($_POST['deskripsi'] ?? '') ?>" <?= $db_error ? 'disabled' : '' ?>>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-2">
                            <button type="submit" name="submit_data" class="btn btn-primary btn-custom text-white" <?= $db_error ? 'disabled' : '' ?>>
                                <i class="bi bi-save me-2"></i>Simpan Data Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: MENAMPILKAN DATA (DIV BARIS & KOLOM) -->
        <div class="col-lg-12 mt-4">
            <div class="card card-custom">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0 text-center"><i class="bi bi-list-ul me-2"></i>Daftar Barang Tersimpan</h5>
                </div>
                
                <div class="card-body p-4">
                    
                    <!-- HEADER (FIELD) -->
                    <div class="row div-table-header d-none d-md-flex text-center">
                        <div class="col-md-1">ID</div>
                        <div class="col-md-3 text-start">Nama Barang</div>
                        <div class="col-md-2 text-start">Harga</div>
                        <div class="col-md-4 text-start">Deskripsi</div>
                        <div class="col-md-2">Waktu Input</div>
                    </div>

                    <!-- ISI DATA (LOOPING) -->
                    <?php
                    if (!$db_error) {
                        $sql_tampil = "SELECT * FROM product ORDER BY id_produk DESC";
                        $result = mysqli_query($conn, $sql_tampil);

                        if (mysqli_num_rows($result) == 0) {
                            echo '
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 mb-3 d-block"></i>
                                <h6>Belum ada data barang yang tersimpan.</h6>
                            </div>';
                        } else {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $harga_format = number_format((float)$row['harga'], 0, ',', '.');
                                $tanggal_format = date('d/m/Y H:i', strtotime($row['created_at']));
                                ?>
                                
                                <!-- BARIS DATA -->
                                <div class="row div-table-row align-items-center text-center text-md-start">
                                    
                                    <!-- Kolom ID -->
                                    <div class="col-12 col-md-1 mb-2 mb-md-0">
                                        <span class="d-md-none mobile-label">ID:</span>
                                        <span class="badge-id">#<?= htmlspecialchars($row['id_produk']) ?></span>
                                    </div>
                                    
                                    <!-- Kolom Nama -->
                                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                                        <span class="d-md-none mobile-label">Nama Barang:</span>
                                        <span class="fw-bold text-dark"><?= htmlspecialchars($row['nama']) ?></span>
                                    </div>
                                    
                                    <!-- Kolom Harga -->
                                    <div class="col-12 col-md-2 mb-2 mb-md-0">
                                        <span class="d-md-none mobile-label">Harga:</span>
                                        <span class="price-text">Rp <?= $harga_format ?></span>
                                    </div>
                                    
                                    <!-- Kolom Deskripsi -->
                                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                                        <span class="d-md-none mobile-label">Deskripsi:</span>
                                        <span class="text-muted" style="font-size: 0.95rem;">
                                            <?= htmlspecialchars($row['deskripsi']) ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Kolom Tanggal -->
                                    <div class="col-12 col-md-2">
                                        <span class="d-md-none mobile-label">Waktu Input:</span>
                                        <span class="text-muted" style="font-size: 0.85rem;">
                                            <i class="bi bi-clock me-1"></i><?= $tanggal_format ?>
                                        </span>
                                    </div>
                                    
                                </div>
                                
                                <?php
                            }
                        }
                    }
                    ?>
                    
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>