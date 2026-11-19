<?php
// ==========================================
// FILE: cart.php (Keranjang Belanja)
// ==========================================
session_start();
require_once 'koneksi.php';

if (empty($_SESSION['cart_session'])) {
    $_SESSION['cart_session'] = bin2hex(random_bytes(16));
}
$session_id = $_SESSION['cart_session'];

// --- PROSES HAPUS DARI KERANJANG (REMOVE) ---
if (isset($_GET['remove']) && !$db_error) {
    $id_cart = (int)$_GET['remove'];
    
    // Ambil info produk dan quantity sebelum dihapus
    $stmt = $pdo->prepare("SELECT id_product, quantity FROM cart WHERE id_cart = ? AND session_id = ?");
    $stmt->execute([$id_cart, $session_id]);
    $item_hapus = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item_hapus) {
        // Kembalikan stok ke tabel product
        $stmt_kembali = $pdo->prepare("UPDATE product SET stok_product = stok_product + ? WHERE id_product = ?");
        $stmt_kembali->execute([$item_hapus['quantity'], $item_hapus['id_product']]);

        // Hapus dari keranjang
        $stmt_del = $pdo->prepare("DELETE FROM cart WHERE id_cart = ?");
        $stmt_del->execute([$id_cart]);
    }
    
    // Refresh halaman
    header("Location: cart.php");
    exit;
}

// --- AMBIL DATA KERANJANG ---
$sql_cart = "SELECT c.id_cart, c.quantity, p.nama_product, p.harga_product, p.gambar1_product 
             FROM cart c 
             JOIN product p ON c.id_product = p.id_product 
             WHERE c.session_id = ?";
$stmt_cart = $pdo->prepare($sql_cart);
$stmt_cart->execute([$session_id]);
$cart_items = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

// Hitung Grand Total
$grand_total = 0;
foreach ($cart_items as $item) {
    $grand_total += ($item['harga_product'] * $item['quantity']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Eduwork Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-custom { background: linear-gradient(135deg, #0d6efd, #0dcaf0); }
        .card-cart { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .img-cart { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; }
        .table-cart td, .table-cart th { vertical-align: middle; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-shop me-2"></i>Eduwork Store</a>
        <div class="d-flex">
            <a href="index.php" class="btn btn-outline-light fw-bold shadow-sm">
                <i class="bi bi-arrow-left"></i> Katalog
            </a>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <h3 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja Anda</h3>

    <div class="card card-cart p-4">
        <?php if (count($cart_items) == 0): ?>
            <div class="text-center py-5">
                <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 fw-bold">Keranjang Anda Kosong</h4>
                <p class="text-muted">Belum ada produk yang Anda tambahkan.</p>
                <a href="index.php" class="btn btn-primary mt-2 px-4 rounded-pill">Mulai Belanja</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-cart table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Nama Produk</th>
                            <th width="15%">Harga</th>
                            <th width="10%" class="text-center">Qty</th>
                            <th width="15%">Total</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($cart_items as $item): 
                            $total_item = $item['harga_product'] * $item['quantity'];
                            $gambar = !empty($item['gambar1_product']) ? $folder_upload . $item['gambar1_product'] : '';
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($gambar && file_exists($gambar)): ?>
                                        <img src="<?= $gambar ?>" class="img-cart me-3">
                                    <?php else: ?>
                                        <div class="img-cart me-3 bg-secondary d-flex align-items-center justify-content-center text-white">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="fw-bold mb-0"><?= htmlspecialchars($item['nama_product']) ?></h6>
                                    </div>
                                </div>
                            </td>
                            <td>Rp <?= number_format($item['harga_product'], 0, ',', '.') ?></td>
                            <td class="text-center fw-bold"><?= $item['quantity'] ?></td>
                            <td class="fw-bold text-success">Rp <?= number_format($total_item, 0, ',', '.') ?></td>
                            <td class="text-center">
                                <a href="cart.php?remove=<?= $item['id_cart'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini dari keranjang?');">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-5">Grand Total :</td>
                            <td colspan="2" class="fw-bold fs-4 text-success">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between mt-4 gap-3">
                <a href="index.php" class="btn btn-outline-primary fw-bold px-4 py-2">
                    <i class="bi bi-arrow-left me-1"></i> Lanjutkan Belanja
                </a>
                <button class="btn btn-success fw-bold px-4 py-2">
                    Checkout <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>