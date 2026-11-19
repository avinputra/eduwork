<?php
// ==========================================
// FILE: admin_product.php
// Deskripsi: Halaman Admin Manajemen Produk
// ==========================================

session_start();

// Panggil file koneksi database
require_once 'koneksi.php';

// ==========================================
// FUNGSI ENKRIPSI KEAMANAN
// ==========================================
define('SECRET_KEY', 'KunciRahasiaSuperAmanEduwork123!@#');
define('SECRET_IV', '1234567890123456');

function encrypt_id($id) {
    $cipher = "aes-256-cbc";
    $encrypted = openssl_encrypt($id, $cipher, SECRET_KEY, 0, SECRET_IV);
    return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
}

function decrypt_id($hash) {
    $cipher = "aes-256-cbc";
    $hash = strtr($hash, '-_', '+/');
    $hash = base64_decode($hash);
    $decrypted = openssl_decrypt($hash, $cipher, SECRET_KEY, 0, SECRET_IV);
    return $decrypted ? (int)$decrypted : false;
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// ==========================================
// FUNGSI BANTUAN UPLOAD GAMBAR
// ==========================================
function uploadGambar($file, $id_product, $slot) {
    global $folder_upload;
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) return null;
        if ($file['size'] > 10 * 1024 * 1024) return null;

        $nama_baru = "P{$id_product}_G{$slot}." . $ext;
        if (move_uploaded_file($file['tmp_name'], $folder_upload . $nama_baru)) {
            return $nama_baru;
        }
    }
    return null;
}

// ==========================================
// LOGIKA CRUD
// ==========================================
$errors = [];
$success_msg = '';
$edit_mode = false;
$edit_data = null;

if (!$db_error) {
    // --- PROSES DELETE ---
    if (isset($_GET['delete'])) {
        $token = $_GET['token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            $errors[] = "Akses ilegal ditolak (CSRF Token tidak valid)!";
        } else {
            $id_hapus = decrypt_id($_GET['delete']);
            if (!$id_hapus) {
                $errors[] = "ID tidak valid atau sudah dimanipulasi!";
            } else {
                try {
                    $stmt = $pdo->prepare("SELECT gambar1_product, gambar2_product, gambar3_product FROM product WHERE id_product = ?");
                    $stmt->execute([$id_hapus]);
                    $data_hapus = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($data_hapus) {
                        foreach (['gambar1_product', 'gambar2_product', 'gambar3_product'] as $gambar) {
                            if (!empty($data_hapus[$gambar]) && file_exists($folder_upload . $data_hapus[$gambar])) {
                                unlink($folder_upload . $data_hapus[$gambar]);
                            }
                        }
                        $stmt = $pdo->prepare("DELETE FROM product WHERE id_product = ?");
                        $stmt->execute([$id_hapus]);
                        $success_msg = "Data produk berhasil dihapus!";
                    }
                } catch (PDOException $e) {
                    $errors[] = "Gagal menghapus data: " . $e->getMessage();
                }
            }
        }
    }

    // --- PROSES EDIT ---
    if (isset($_GET['edit'])) {
        $id_edit = decrypt_id($_GET['edit']);
        if (!$id_edit) {
            $errors[] = "ID tidak valid atau sudah dimanipulasi!";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM product WHERE id_product = ?");
            $stmt->execute([$id_edit]);
            $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($edit_data) {
                $edit_mode = true;
            }
        }
    }

    // --- PROSES SIMPAN (INSERT / UPDATE) ---
    if (isset($_POST['simpan'])) {
        $post_token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $post_token)) {
            $errors[] = "Akses ilegal ditolak (CSRF Token Form tidak valid)!";
        } else {
            $nama = trim($_POST['nama_product'] ?? '');
            $deskripsi = trim($_POST['deskripsi_product'] ?? '');
            $kategori = trim($_POST['kategori_product'] ?? '');
            $harga = trim($_POST['harga_product'] ?? '');
            $stok = trim($_POST['stok_product'] ?? '');
            $id_update = !empty($_POST['id_product']) ? decrypt_id($_POST['id_product']) : '';

            if (empty($nama)) $errors[] = "Nama produk wajib diisi.";
            if (empty($deskripsi)) $errors[] = "Deskripsi produk wajib diisi.";
            if (empty($kategori)) $errors[] = "Kategori produk wajib dipilih.";
            if (empty($harga) || !is_numeric($harga)) $errors[] = "Harga harus berupa angka.";
            if (empty($stok) || !is_numeric($stok)) $errors[] = "Stok harus berupa angka.";

            if (empty($errors)) {
                try {
                    if (!empty($id_update)) {
                        // UPDATE
                        $stmt = $pdo->prepare("SELECT gambar1_product, gambar2_product, gambar3_product FROM product WHERE id_product = ?");
                        $stmt->execute([$id_update]);
                        $old_data = $stmt->fetch(PDO::FETCH_ASSOC);

                        $g1 = uploadGambar($_FILES['gambar1_product'] ?? null, $id_update, 1) ?? $old_data['gambar1_product'];
                        $g2 = uploadGambar($_FILES['gambar2_product'] ?? null, $id_update, 2) ?? $old_data['gambar2_product'];
                        $g3 = uploadGambar($_FILES['gambar3_product'] ?? null, $id_update, 3) ?? $old_data['gambar3_product'];

                        if ($g1 !== $old_data['gambar1_product'] && !empty($old_data['gambar1_product'])) @unlink($folder_upload . $old_data['gambar1_product']);
                        if ($g2 !== $old_data['gambar2_product'] && !empty($old_data['gambar2_product'])) @unlink($folder_upload . $old_data['gambar2_product']);
                        if ($g3 !== $old_data['gambar3_product'] && !empty($old_data['gambar3_product'])) @unlink($folder_upload . $old_data['gambar3_product']);

                        $sql = "UPDATE product SET nama_product=?, deskripsi_product=?, gambar1_product=?, gambar2_product=?, gambar3_product=?, kategori_product=?, harga_product=?, stok_product=? WHERE id_product=?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$nama, $deskripsi, $g1, $g2, $g3, $kategori, $harga, $stok, $id_update]);
                        $success_msg = "Data produk berhasil diperbarui!";
                    } else {
                        // INSERT
                        $sql = "INSERT INTO product (nama_product, deskripsi_product, kategori_product, harga_product, stok_product) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$nama, $deskripsi, $kategori, $harga, $stok]);
                        
                        $new_id = $pdo->lastInsertId();
                        $g1 = uploadGambar($_FILES['gambar1_product'] ?? null, $new_id, 1);
                        $g2 = uploadGambar($_FILES['gambar2_product'] ?? null, $new_id, 2);
                        $g3 = uploadGambar($_FILES['gambar3_product'] ?? null, $new_id, 3);

                        $sql_update = "UPDATE product SET gambar1_product=?, gambar2_product=?, gambar3_product=? WHERE id_product=?";
                        $stmt = $pdo->prepare($sql_update);
                        $stmt->execute([$g1, $g2, $g3, $new_id]);
                        $success_msg = "Produk baru berhasil ditambahkan!";
                    }
                } catch (PDOException $e) {
                    $errors[] = "Gagal menyimpan data: " . $e->getMessage();
                }
            }
        }
        
        if (empty($errors) && $success_msg) {
            $redirect_params = "";
            if (!empty($_GET['filter_kategori'])) $redirect_params .= "&filter_kategori=" . urlencode($_GET['filter_kategori']);
            if (!empty($_GET['search'])) $redirect_params .= "&search=" . urlencode($_GET['search']);
            
            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?success=" . urlencode($success_msg) . $redirect_params);
            exit;
        }
    }
}

if (isset($_GET['success'])) {
    $success_msg = htmlspecialchars($_GET['success']);
}

$filter_kategori = $_GET['filter_kategori'] ?? '';
$search = trim($_GET['search'] ?? '');

$url_params = "";
if (!empty($filter_kategori)) $url_params .= "&filter_kategori=" . urlencode($filter_kategori);
if (!empty($search)) $url_params .= "&search=" . urlencode($search);
$url_params_first = ltrim($url_params, '&');

$csrf_token = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manajemen Produk Eduwork</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .card-header-custom { background: linear-gradient(135deg, #0d6efd, #0dcaf0); color: white; border-radius: 15px 15px 0 0 !important; padding: 15px 20px; font-weight: bold; }
        .btn-custom { background: linear-gradient(135deg, #0d6efd, #0dcaf0); border: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; }
        .btn-custom:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4); }
        .modal-header { background: linear-gradient(135deg, #0d6efd, #0dcaf0); color: white; }
        .modal-content { border-radius: 15px; border: none; max-height: 90vh; overflow-y: auto; }
        @media (max-width: 576px) { .modal-dialog { margin: 0.5rem; } }
        .div-table-header { background-color: #e9ecef; font-weight: 700; color: #495057; border-radius: 8px; padding: 12px 15px; margin-bottom: 10px; text-transform: uppercase; font-size: 0.85rem; }
        .div-table-row { background-color: #ffffff; padding: 15px; border-bottom: 1px solid #e9ecef; border-radius: 8px; margin-bottom: 8px; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .div-table-row:hover { background-color: #f8f9fa; transform: scale(1.01); box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        .img-product { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0; margin-right: 5px; }
        .mobile-label { font-size: 0.8rem; color: #6c757d; font-weight: 600; display: block; margin-bottom: 2px; }
        .badge-id { background-color: #e2e8f0; color: #475569; padding: 5px 10px; border-radius: 5px; font-weight: 600; }
        .price-text { color: #198754; font-weight: 700; }
        .waktu-info { font-size: 0.75rem; color: #94a3b8; margin-top: 5px; border-top: 1px dashed #e2e8f0; padding-top: 5px;}
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    
    <?php if ($success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($success_msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors) && !isset($_POST['simpan'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Akses Ditolak:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card card-custom">
                
                <div class="card-header card-header-custom">
                    <div class="row align-items-center g-2">
                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Produk</h5>
                        </div>
                        
                        <div class="col-12 col-md-8">
                            <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
                                <form action="" method="GET" class="d-flex flex-column flex-sm-row gap-2 w-100">
                                    <div class="input-group shadow-sm">
                                        <input type="text" class="form-control form-control-sm border-0" name="search" placeholder="Cari nama produk..." value="<?= htmlspecialchars($search) ?>">
                                        <button class="btn btn-light text-primary border-0" type="submit"><i class="bi bi-search"></i></button>
                                    </div>
                                    <select name="filter_kategori" class="form-select form-select-sm shadow-sm border-0" onchange="this.form.submit()" style="min-width: 150px;">
                                        <option value="">Semua Kategori</option>
                                        <option value="1" <?= ($filter_kategori == '1') ? 'selected' : '' ?>>Pakaian Pria</option>
                                        <option value="2" <?= ($filter_kategori == '2') ? 'selected' : '' ?>>Pakaian Wanita</option>
                                        <option value="3" <?= ($filter_kategori == '3') ? 'selected' : '' ?>>Handphone</option>
                                    </select>
                                    <?php if (!empty($search) || !empty($filter_kategori)): ?>
                                        <a href="?" class="btn btn-sm btn-outline-light text-nowrap" title="Reset"><i class="bi bi-x-circle"></i></a>
                                    <?php endif; ?>
                                </form>
                                <button type="button" class="btn btn-light text-primary fw-bold shadow-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#modalForm">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="row div-table-header d-none d-md-flex text-center align-items-center">
                        <div class="col-md-1">ID</div>
                        <div class="col-md-3 text-start">Nama & Kategori</div>
                        <div class="col-md-2 text-start">Harga / Stok</div>
                        <div class="col-md-2 text-start">Gambar</div>
                        <div class="col-md-3 text-start">Deskripsi</div>
                        <div class="col-md-1 text-center">Aksi</div>
                    </div>

                    <?php
                    if (!$db_error) {
                        $sql = "SELECT * FROM product WHERE 1=1";
                        $params = [];
                        if (!empty($filter_kategori)) { $sql .= " AND kategori_product = ?"; $params[] = $filter_kategori; }
                        if (!empty($search)) { $sql .= " AND nama_product LIKE ?"; $params[] = "%" . $search . "%"; }
                        $sql .= " ORDER BY id_product DESC";

                        $stmt = $pdo->prepare($sql);
                        $stmt->execute($params);
                        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($products) == 0) {
                            echo '<div class="text-center py-5 text-muted"><i class="bi bi-search fs-1 d-block mb-3"></i><h6>Tidak ada data produk yang cocok.</h6></div>';
                        } else {
                            $kategori_map = [1 => 'Pakaian Pria', 2 => 'Pakaian Wanita', 3 => 'Handphone'];
                            foreach ($products as $row) {
                                $harga_format = number_format((float)$row['harga_product'], 0, ',', '.');
                                $id_tampil = "P" . $row['id_product'];
                                $tgl_dibuat = date('d/m/Y H:i', strtotime($row['created_at']));
                                $tgl_diedit = date('d/m/Y H:i', strtotime($row['updated_at']));
                                $pernah_diedit = ($row['created_at'] !== $row['updated_at']);
                                
                                $enc_id = encrypt_id($row['id_product']);
                                ?>
                                <div class="row div-table-row align-items-center text-center text-md-start">
                                    <div class="col-12 col-md-1 mb-2 mb-md-0">
                                        <span class="d-md-none mobile-label">ID:</span>
                                        <span class="badge-id"><?= $id_tampil ?></span>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                                        <span class="d-md-none mobile-label">Nama Produk:</span>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($row['nama_product']) ?></div>
                                        <small class="text-primary"><i class="bi bi-tag-fill me-1"></i><?= $kategori_map[$row['kategori_product']] ?? 'Lainnya' ?></small>
                                        <div class="waktu-info d-none d-md-block">
                                            <i class="bi bi-clock me-1"></i> Dibuat: <?= $tgl_dibuat ?>
                                            <?php if($pernah_diedit): ?>
                                                <br><i class="bi bi-pencil-square text-warning me-1"></i> Diedit: <?= $tgl_diedit ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-2 mb-2 mb-md-0">
                                        <span class="d-md-none mobile-label">Harga / Stok:</span>
                                        <div class="price-text">Rp <?= $harga_format ?></div>
                                        <small class="text-muted">Stok: <?= htmlspecialchars($row['stok_product']) ?></small>
                                    </div>
                                    <div class="col-12 col-md-2 mb-2 mb-md-0">
                                        <span class="d-md-none mobile-label">Gambar:</span>
                                        <div class="d-flex justify-content-center justify-content-md-start flex-wrap">
                                            <?php 
                                            $ada_gambar = false;
                                            for ($i = 1; $i <= 3; $i++) {
                                                $gambar = $row["gambar{$i}_product"];
                                                if (!empty($gambar) && file_exists($folder_upload . $gambar)) {
                                                    echo '<img src="'.$folder_upload.htmlspecialchars($gambar).'" class="img-product" alt="G'.$i.'">';
                                                    $ada_gambar = true;
                                                }
                                            }
                                            if (!$ada_gambar) { echo '<small class="text-muted fst-italic">No Image</small>'; }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                                        <span class="d-md-none mobile-label">Deskripsi:</span>
                                        <div class="text-muted" style="font-size: 0.85rem;"><?= nl2br(htmlspecialchars($row['deskripsi_product'])) ?></div>
                                        <div class="waktu-info d-md-none text-start mt-2">
                                            <i class="bi bi-clock me-1"></i> Dibuat: <?= $tgl_dibuat ?>
                                            <?php if($pernah_diedit): ?>
                                                <br><i class="bi bi-pencil-square text-warning me-1"></i> Diedit: <?= $tgl_diedit ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-1 text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="?edit=<?= $enc_id ?><?= $url_params ?>" class="btn btn-sm btn-warning text-white" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="?delete=<?= $enc_id ?>&token=<?= $csrf_token ?><?= $url_params ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus produk <?= $id_tampil ?>?');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
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

<!-- MODAL FORM -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">
                    <i class="bi bi-box-seam me-2"></i><?= $edit_mode ? 'Edit Data Produk' : 'Tambah Data Produk Baru' ?>
                </h5>
                <a href="?<?= $url_params_first ?>" class="btn-close btn-close-white" aria-label="Close"></a>
            </div>
            
            <form action="?<?= $url_params_first ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <?php if (!empty($errors) && isset($_POST['simpan'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Oops! Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="id_product" value="<?= $edit_mode ? encrypt_id($edit_data['id_product']) : '' ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Produk</label>
                        <input type="text" class="form-control" name="nama_product" required placeholder="Contoh: Kemeja Batik Pria"
                               value="<?= $edit_mode ? htmlspecialchars($edit_data['nama_product']) : (isset($_POST['nama_product']) ? htmlspecialchars($_POST['nama_product']) : '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Produk</label>
                        <select class="form-select" name="kategori_product" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="1" <?= ($edit_mode && $edit_data['kategori_product'] == 1) ? 'selected' : '' ?>>Pakaian Pria</option>
                            <option value="2" <?= ($edit_mode && $edit_data['kategori_product'] == 2) ? 'selected' : '' ?>>Pakaian Wanita</option>
                            <option value="3" <?= ($edit_mode && $edit_data['kategori_product'] == 3) ? 'selected' : '' ?>>Handphone</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Harga (Rp)</label>
                            <input type="number" class="form-control" name="harga_product" required placeholder="Contoh: 150000"
                                   value="<?= $edit_mode ? htmlspecialchars((int)$edit_data['harga_product']) : (isset($_POST['harga_product']) ? htmlspecialchars($_POST['harga_product']) : '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stok</label>
                            <input type="number" class="form-control" name="stok_product" required placeholder="Contoh: 10"
                                   value="<?= $edit_mode ? htmlspecialchars($edit_data['stok_product']) : (isset($_POST['stok_product']) ? htmlspecialchars($_POST['stok_product']) : '') ?>">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Deskripsi Produk</label>
                        <textarea class="form-control" name="deskripsi_product" rows="3" required placeholder="Tuliskan spesifikasi produk secara detail..."><?= $edit_mode ? htmlspecialchars($edit_data['deskripsi_product']) : (isset($_POST['deskripsi_product']) ? htmlspecialchars($_POST['deskripsi_product']) : '') ?></textarea>
                    </div>
                    <div class="p-3 bg-light rounded border mb-2">
                        <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2"><i class="bi bi-images me-2"></i>Upload Foto Produk (Maks. 10MB, JPG/PNG/WEBP)</h6>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Gambar 1 (Utama)</label>
                            <input type="file" class="form-control" name="gambar1_product" accept=".jpg,.jpeg,.png,.webp">
                            <?php if($edit_mode && $edit_data['gambar1_product']): ?>
                                <div class="mt-2"><img src="<?= $folder_upload . $edit_data['gambar1_product'] ?>" class="img-thumbnail" style="height: 80px;"></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Gambar 2</label>
                            <input type="file" class="form-control" name="gambar2_product" accept=".jpg,.jpeg,.png,.webp">
                            <?php if($edit_mode && $edit_data['gambar2_product']): ?>
                                <div class="mt-2"><img src="<?= $folder_upload . $edit_data['gambar2_product'] ?>" class="img-thumbnail" style="height: 80px;"></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Gambar 3</label>
                            <input type="file" class="form-control" name="gambar3_product" accept=".jpg,.jpeg,.png,.webp">
                            <?php if($edit_mode && $edit_data['gambar3_product']): ?>
                                <div class="mt-2"><img src="<?= $folder_upload . $edit_data['gambar3_product'] ?>" class="img-thumbnail" style="height: 80px;"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <a href="?<?= $url_params_first ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" name="simpan" class="btn btn-primary btn-custom text-white px-4">
                        <i class="bi bi-save me-1"></i> Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var openModal = <?= ($edit_mode || (isset($_POST['simpan']) && !empty($errors))) ? 'true' : 'false' ?>;
        if (openModal) {
            var myModal = new bootstrap.Modal(document.getElementById('modalForm'));
            myModal.show();
        }
    });
</script>
</body>
</html>