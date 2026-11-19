<?php
// ==========================================
// FILE: index.php (Etalase Katalog)
// ==========================================
session_start();
require_once 'koneksi.php';

// Buat session_id unik untuk keranjang user ini
if (empty($_SESSION['cart_session'])) {
    $_SESSION['cart_session'] = bin2hex(random_bytes(16));
}
$session_id = $_SESSION['cart_session'];

$pesan = '';

// --- PROSES TAMBAH KE KERANJANG ---
if (isset($_GET['beli']) && !$db_error) {
    $id_produk = (int)$_GET['beli'];
    
    // Cek stok produk
    $stmt = $pdo->prepare("SELECT stok_product, nama_product FROM product WHERE id_product = ?");
    $stmt->execute([$id_produk]);
    $produk = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produk && $produk['stok_product'] > 0) {
        // Cek apakah produk sudah ada di keranjang session ini
        $stmt_cek = $pdo->prepare("SELECT id_cart, quantity FROM cart WHERE id_product = ? AND session_id = ?");
        $stmt_cek->execute([$id_produk, $session_id]);
        $cart_item = $stmt_cek->fetch(PDO::FETCH_ASSOC);

        if ($cart_item) {
            // Jika sudah ada, tambah quantity
            $stmt_update = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id_cart = ?");
            $stmt_update->execute([$cart_item['id_cart']]);
        } else {
            // Jika belum ada, insert baru
            $stmt_insert = $pdo->prepare("INSERT INTO cart (id_product, quantity, session_id) VALUES (?, 1, ?)");
            $stmt_insert->execute([$id_produk, $session_id]);
        }

        // Kurangi stok di tabel product
        $stmt_stok = $pdo->prepare("UPDATE product SET stok_product = stok_product - 1 WHERE id_product = ?");
        $stmt_stok->execute([$id_produk]);

        // Redirect ke halaman cart
        header("Location: cart.php");
        exit;
    } else {
        $pesan = "Maaf, stok produk ini sedang habis!";
    }
}

// ==========================================
// FITUR PENCARIAN & FILTER
// ==========================================
$search = trim($_GET['search'] ?? '');
$filter_kategori = $_GET['kategori'] ?? '';

$sql_produk = "SELECT * FROM product WHERE 1=1";
$params_produk = [];

if (!empty($search)) {
    $sql_produk .= " AND nama_product LIKE ?";
    $params_produk[] = "%" . $search . "%";
}

if (!empty($filter_kategori)) {
    $sql_produk .= " AND kategori_product = ?";
    $params_produk[] = $filter_kategori;
}

$sql_produk .= " ORDER BY id_product DESC";

$stmt_produk = $pdo->prepare($sql_produk);
$stmt_produk->execute($params_produk);
$products = $stmt_produk->fetchAll(PDO::FETCH_ASSOC);

$kategori_map = [1 => 'Pakaian Pria', 2 => 'Pakaian Wanita', 3 => 'Handphone'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - Eduwork Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-custom { background: linear-gradient(135deg, #0d6efd, #0dcaf0); }
        .card-produk { transition: transform 0.3s ease, box-shadow 0.3s ease; border: none; border-radius: 15px; overflow: hidden; }
        .card-produk:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .img-katalog { height: 200px; object-fit: cover; width: 100%; }
        .img-placeholder { height: 200px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; color: #adb5bd; }
        .harga-text { color: #198754; font-weight: 700; font-size: 1.25rem; }
        .btn-beli { background: linear-gradient(135deg, #0d6efd, #0dcaf0); border: none; color: white; font-weight: 600; }
        .btn-beli:hover { background: linear-gradient(135deg, #0b5ed7, #0aa2c0); color: white; }
        .search-card { border-radius: 15px; border: none; }
        
        /* CSS Tambahan untuk Modal Detail */
        .modal-gambar-container { 
            display: flex; 
            gap: 10px; 
            overflow-x: auto; 
            padding-bottom: 10px; 
            justify-content: center;
        }
        .modal-gambar-container img {
            max-height: 300px;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-shop me-2"></i>Eduwork Store</a>
        <div class="d-flex">
            <a href="cart.php" class="btn btn-light text-primary fw-bold shadow-sm position-relative">
                <i class="bi bi-cart3"></i> Keranjang
            </a>
        </div>
    </div>
</nav>

<div class="container mb-5">
    
    <!-- HEADER & FITUR PENCARIAN -->
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Katalog Produk</h2>
                <p class="text-muted">Temukan produk terbaik untuk kebutuhan Anda</p>
            </div>

            <div class="card search-card shadow-sm">
                <div class="card-body p-3">
                    <form action="" method="GET" class="row g-2 align-items-center">
                        <!-- Input Pencarian -->
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama produk..." value="<?= htmlspecialchars($search) ?>">
                            </div>
                        </div>
                        
                        <!-- Dropdown Kategori -->
                        <div class="col-md-3">
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                <option value="1" <?= ($filter_kategori == '1') ? 'selected' : '' ?>>Pakaian Pria</option>
                                <option value="2" <?= ($filter_kategori == '2') ? 'selected' : '' ?>>Pakaian Wanita</option>
                                <option value="3" <?= ($filter_kategori == '3') ? 'selected' : '' ?>>Handphone</option>
                            </select>
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-bold w-100">Terapkan</button>
                            <?php if (!empty($search) || !empty($filter_kategori)): ?>
                                <a href="index.php" class="btn btn-outline-secondary w-100" title="Reset Pencarian"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($pesan): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $pesan ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- HASIL PRODUK -->
    <div class="row g-4">
        <?php if(count($products) == 0): ?>
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-search fs-1 d-block mb-3"></i>
                <h5>Tidak ada produk yang cocok dengan pencarian Anda.</h5>
                <p>Coba gunakan kata kunci lain atau reset filter.</p>
            </div>
        <?php else: ?>
            <?php foreach ($products as $row): 
                $gambar_utama = !empty($row['gambar1_product']) ? $folder_upload . $row['gambar1_product'] : '';
                $is_habis = ($row['stok_product'] <= 0);
            ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card card-produk h-100 shadow-sm">
                    <?php if($gambar_utama && file_exists($gambar_utama)): ?>
                        <img src="<?= $gambar_utama ?>" class="img-katalog" alt="<?= htmlspecialchars($row['nama_product']) ?>">
                    <?php else: ?>
                        <div class="img-placeholder"><i class="bi bi-image fs-1"></i></div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column p-3">
                        <small class="text-primary fw-bold mb-1"><?= $kategori_map[$row['kategori_product']] ?? 'Lainnya' ?></small>
                        <h6 class="fw-bold text-dark text-truncate" title="<?= htmlspecialchars($row['nama_product']) ?>"><?= htmlspecialchars($row['nama_product']) ?></h6>
                        <div class="harga-text mb-2">Rp <?= number_format($row['harga_product'], 0, ',', '.') ?></div>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">Stok: <?= $row['stok_product'] ?></small>
                                <?php if($is_habis): ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <!-- Tombol Detail (Modal) -->
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id_product'] ?>">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </button>
                                
                                <!-- Tombol Beli (Keranjang) -->
                                <?php if($is_habis): ?>
                                    <button class="btn btn-secondary btn-sm" disabled><i class="bi bi-cart-x me-1"></i> Habis</button>
                                <?php else: ?>
                                    <a href="index.php?beli=<?= $row['id_product'] ?>" class="btn btn-beli btn-sm">
                                        <i class="bi bi-cart-plus me-1"></i> Beli
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL DETAIL PRODUK (DIPERBARUI: GAMBAR DI ATAS) -->
            <div class="modal fade" id="modalDetail<?= $row['id_product'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-light border-0">
                            <h5 class="modal-title fw-bold">Detail Produk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            
                            <!-- 1. BAGIAN GAMBAR (PALING ATAS) -->
                            <div class="modal-gambar-container mb-4">
                                <?php 
                                $ada_gambar = false;
                                for($i=1; $i<=3; $i++) {
                                    $img = $row["gambar{$i}_product"];
                                    if(!empty($img) && file_exists($folder_upload . $img)) {
                                        echo '<img src="'.$folder_upload.$img.'" alt="Gambar '.$i.'">';
                                        $ada_gambar = true;
                                    }
                                }
                                if(!$ada_gambar) {
                                    echo '<div class="bg-light rounded d-flex align-items-center justify-content-center w-100" style="height:200px;"><i class="bi bi-image fs-1 text-muted"></i></div>';
                                }
                                ?>
                            </div>

                            <!-- 2. BAGIAN INFORMASI (DI BAWAH GAMBAR) -->
                            <div class="text-center text-md-start">
                                <span class="badge bg-primary mb-2"><?= $kategori_map[$row['kategori_product']] ?? 'Lainnya' ?></span>
                                <h3 class="fw-bold"><?= htmlspecialchars($row['nama_product']) ?></h3>
                                <h3 class="text-success fw-bold mb-4">Rp <?= number_format($row['harga_product'], 0, ',', '.') ?></h3>
                                
                                <h6 class="fw-bold border-bottom pb-2">Deskripsi Produk:</h6>
                                <p class="text-muted mb-4" style="font-size:0.95rem; line-height: 1.6;"><?= nl2br(htmlspecialchars($row['deskripsi_product'])) ?></p>
                                
                                <hr>
                                
                                <!-- Stok dan Tombol Beli -->
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                    <div>
                                        <span class="text-muted fs-5">Sisa Stok: <strong class="<?= $is_habis ? 'text-danger' : 'text-dark' ?>"><?= $row['stok_product'] ?></strong></span>
                                    </div>
                                    <div>
                                        <?php if($is_habis): ?>
                                            <button class="btn btn-secondary btn-lg px-5" disabled>Stok Habis</button>
                                        <?php else: ?>
                                            <a href="index.php?beli=<?= $row['id_product'] ?>" class="btn btn-beli btn-lg px-5 shadow-sm">
                                                <i class="bi bi-cart-plus me-2"></i> Masukkan Keranjang
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>