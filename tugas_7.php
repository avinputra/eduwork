<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Barang</title>
    
    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS untuk sentuhan tambahan -->
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
            text-align: center;
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
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="card card-custom">
                <div class="card-header card-header-custom">
                    <h4 class="mb-0">Input Data Barang</h4>
                </div>
                
                <div class="card-body p-4">
                    
                    <?php
                    // Array untuk menampung error
                    $errors = [];
                    $success = false;

                    // Cek apakah form disubmit
                    if (isset($_POST['submit_data'])) {
                        
                        // Ambil data dan bersihkan spasi berlebih
                        $nama = trim($_POST['nama'] ?? '');
                        $harga = trim($_POST['harga'] ?? '');
                        $deskripsi = trim($_POST['deskripsi'] ?? '');

                        // --- VALIDASI ---
                        if (empty($nama)) {
                            $errors[] = "Nama barang tidak boleh kosong.";
                        }
                        
                        if (empty($harga)) {
                            $errors[] = "Harga barang tidak boleh kosong.";
                        } else if (!is_numeric($harga)) {
                            $errors[] = "Inputan harga barang harus berupa angka.";
                        }
                        
                        if (empty($deskripsi)) {
                            $errors[] = "Deskripsi barang tidak boleh kosong.";
                        }

                        // Jika tidak ada error, set success = true
                        if (empty($errors)) {
                            $success = true;
                        }
                    }
                    ?>

                    <!-- TAMPILAN PESAN ERROR -->
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

                    <!-- TAMPILAN PESAN SUKSES -->
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading"><strong>Berhasil!</strong></h5>
                            <hr>
                            <p class="mb-1"><strong>Nama Barang:</strong> <?= htmlspecialchars($nama) ?></p>
                            <p class="mb-1"><strong>Harga Barang:</strong> Rp <?= number_format((float)$harga, 0, ',', '.') ?></p>
                            <p class="mb-0"><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($deskripsi)) ?></p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- FORM INPUT -->
                    <form action="" method="post">
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Barang</label>
                            <!-- Value diisi otomatis dengan data sebelumnya jika ada error -->
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   placeholder="Contoh: Laptop ASUS" 
                                   value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label fw-bold">Harga Barang (Angka)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control" id="harga" name="harga" 
                                       placeholder="Contoh: 15000000" 
                                       value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi Barang</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" 
                                      placeholder="Tuliskan spesifikasi atau deskripsi barang di sini..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="submit_data" class="btn btn-primary btn-custom text-white">
                                Simpan Data Barang
                            </button>
                        </div>

                    </form>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Bootstrap JS  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>