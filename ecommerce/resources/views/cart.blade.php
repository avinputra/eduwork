@extends('layout')

@section('title', 'Keranjang Belanja - Eduwork Store')

@push('styles')
<style>
    .page-title {
        font-weight: 700; color: #212529; margin-bottom: 25px;
        padding-bottom: 15px; border-bottom: 2px solid #e9ecef;
    }

    /* Card Item Produk */
    .cart-item-card {
        border: none; border-radius: 12px; background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 15px; transition: all 0.3s ease;
    }
    .cart-item-card:hover { box-shadow: 0 5px 20px rgba(0,0,0,0.08); }
    .cart-img { width: 100px; height: 100px; object-fit: cover; border-radius: 10px; }
    
    /* Input Quantity */
    .quantity-input { max-width: 120px; }
    .quantity-input .btn { background-color: #f8f9fa; border: 1px solid #dee2e6; color: #495057; }
    .quantity-input .btn:hover { background-color: #e9ecef; }
    .quantity-input input {
        text-align: center; border-left: none; border-right: none; font-weight: 600;
    }
    .quantity-input input:focus { box-shadow: none; border-color: #dee2e6; }

    .price-text { color: #198754; font-weight: 700; }

    /* Ringkasan Belanja */
    .summary-card {
        border: none; border-radius: 15px; background: #fff;
        box-shadow: 0 5px 25px rgba(0,0,0,0.06); position: sticky; top: 100px;
    }
    .summary-title { font-weight: 700; border-bottom: 1px solid #e9ecef; padding-bottom: 15px; margin-bottom: 20px; }
    .total-price { font-size: 1.5rem; font-weight: 700; color: #0d6efd; }
    
    .btn-checkout {
        background: linear-gradient(135deg, #0d6efd, #0dcaf0); border: none; border-radius: 10px;
        color: white; font-weight: 600; font-size: 1.1rem; padding: 12px; transition: all 0.3s ease;
    }
    .btn-checkout:hover {
        background: linear-gradient(135deg, #0b5ed7, #0aa2c0); color: white;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4); transform: translateY(-2px);
    }
    .btn-remove {
        color: #dc3545; background: #f8d7da; border: none; border-radius: 8px; padding: 8px 12px; transition: all 0.2s;
    }
    .btn-remove:hover { background: #dc3545; color: white; }

    /* Responsif untuk Mobile */
    @media (max-width: 767.98px) {
        .cart-item-card .row { flex-direction: column; align-items: center; text-align: center; }
        .cart-img { width: 120px; height: 120px; margin-bottom: 15px; }
        .quantity-input { margin: 0 auto; }
    }
</style>
@endpush

@section('content')
<div class="container my-5">
    
    <h3 class="page-title"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h3>

    <div class="row g-4">
        
        <!-- KOLOM KIRI: DAFTAR PRODUK (5 ITEM) -->
        <div class="col-lg-8">
            
            <!-- ITEM 1 -->
            <div class="card cart-item-card p-3">
                <div class="row align-items-center">
                    <div class="col-md-2 col-4 mb-3 mb-md-0">
                        <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=500&auto=format&fit=crop" class="cart-img shadow-sm" alt="Produk">
                    </div>
                    <div class="col-md-4 col-8 mb-3 mb-md-0">
                        <span class="badge bg-light text-primary mb-1 border">Handphone</span>
                        <h6 class="fw-bold mb-1">Samsung Galaxy S23 Ultra 5G 512GB</h6>
                        <small class="text-muted">Stok tersedia</small>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-center gap-3">
                            <div class="price-text">Rp 18.999.000</div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group quantity-input">
                                    <button class="btn btn-sm" type="button"><i class="bi bi-dash"></i></button>
                                    <input type="text" class="form-control form-control-sm" value="1" readonly>
                                    <button class="btn btn-sm" type="button"><i class="bi bi-plus"></i></button>
                                </div>
                                <button class="btn-remove" title="Hapus Produk"><i class="bi bi-trash3"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ITEM 2 -->
            <div class="card cart-item-card p-3">
                <div class="row align-items-center">
                    <div class="col-md-2 col-4 mb-3 mb-md-0">
                        <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?q=80&w=500&auto=format&fit=crop" class="cart-img shadow-sm" alt="Produk">
                    </div>
                    <div class="col-md-4 col-8 mb-3 mb-md-0">
                        <span class="badge bg-light text-primary mb-1 border">Pakaian Pria</span>
                        <h6 class="fw-bold mb-1">Kemeja Flanel Casual Premium Pria</h6>
                        <small class="text-muted">Ukuran: L</small>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-center gap-3">
                            <div class="price-text">Rp 185.000</div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group quantity-input">
                                    <button class="btn btn-sm" type="button"><i class="bi bi-dash"></i></button>
                                    <input type="text" class="form-control form-control-sm" value="2" readonly>
                                    <button class="btn btn-sm" type="button"><i class="bi bi-plus"></i></button>
                                </div>
                                <button class="btn-remove" title="Hapus Produk"><i class="bi bi-trash3"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ITEM 3 -->
            <div class="card cart-item-card p-3">
                <div class="row align-items-center">
                    <div class="col-md-2 col-4 mb-3 mb-md-0">
                        <img src="https://images.unsplash.com/photo-1576566588028-4147f3842f27?q=80&w=500&auto=format&fit=crop" class="cart-img shadow-sm" alt="Produk">
                    </div>
                    <div class="col-md-4 col-8 mb-3 mb-md-0">
                        <span class="badge bg-light text-primary mb-1 border">Pakaian Wanita</span>
                        <h6 class="fw-bold mb-1">Kaos Oversize Wanita Cotton Combed 30s</h6>
                        <small class="text-muted">Warna: Putih</small>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-center gap-3">
                            <div class="price-text">Rp 89.000</div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group quantity-input">
                                    <button class="btn btn-sm" type="button"><i class="bi bi-dash"></i></button>
                                    <input type="text" class="form-control form-control-sm" value="1" readonly>
                                    <button class="btn btn-sm" type="button"><i class="bi bi-plus"></i></button>
                                </div>
                                <button class="btn-remove" title="Hapus Produk"><i class="bi bi-trash3"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ITEM 4 -->
            <div class="card cart-item-card p-3">
                <div class="row align-items-center">
                    <div class="col-md-2 col-4 mb-3 mb-md-0">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=500&auto=format&fit=crop" class="cart-img shadow-sm" alt="Produk">
                    </div>
                    <div class="col-md-4 col-8 mb-3 mb-md-0">
                        <span class="badge bg-light text-primary mb-1 border">Elektronik</span>
                        <h6 class="fw-bold mb-1">Sony WH-1000XM4 Wireless Headphones</h6>
                        <small class="text-muted">Warna: Hitam</small>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-center gap-3">
                            <div class="price-text">Rp 4.299.000</div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group quantity-input">
                                    <button class="btn btn-sm" type="button"><i class="bi bi-dash"></i></button>
                                    <input type="text" class="form-control form-control-sm" value="1" readonly>
                                    <button class="btn btn-sm" type="button"><i class="bi bi-plus"></i></button>
                                </div>
                                <button class="btn-remove" title="Hapus Produk"><i class="bi bi-trash3"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ITEM 5 -->
            <div class="card cart-item-card p-3">
                <div class="row align-items-center">
                    <div class="col-md-2 col-4 mb-3 mb-md-0">
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=500&auto=format&fit=crop" class="cart-img shadow-sm" alt="Produk">
                    </div>
                    <div class="col-md-4 col-8 mb-3 mb-md-0">
                        <span class="badge bg-light text-primary mb-1 border">Aksesoris</span>
                        <h6 class="fw-bold mb-1">Jam Tangan Pria Kulit Original</h6>
                        <small class="text-muted">Warna: Coklat</small>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-center gap-3">
                            <div class="price-text">Rp 450.000</div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group quantity-input">
                                    <button class="btn btn-sm" type="button"><i class="bi bi-dash"></i></button>
                                    <input type="text" class="form-control form-control-sm" value="3" readonly>
                                    <button class="btn btn-sm" type="button"><i class="bi bi-plus"></i></button>
                                </div>
                                <button class="btn-remove" title="Hapus Produk"><i class="bi bi-trash3"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Lanjut Belanja -->
            <div class="mt-4">
                <a href="{{ url('/') }}" class="text-decoration-none fw-semibold text-primary">
                    <i class="bi bi-arrow-left me-1"></i> Lanjutkan Belanja
                </a>
            </div>

        </div>

        <!-- KOLOM KANAN: RINGKASAN BELANJA -->
        <div class="col-lg-4">
            <div class="card summary-card p-4">
                <h5 class="summary-title"><i class="bi bi-receipt me-2"></i>Ringkasan Belanja</h5>
                
                <!-- Perhitungan:
                     Total Barang: 1+2+1+1+3 = 8 Barang
                     Subtotal: 18.999.000 + 370.000 + 89.000 + 4.299.000 + 1.350.000 = 25.107.000
                -->
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Harga (8 Barang)</span>
                    <span class="fw-semibold">Rp 25.107.000</span>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ongkos Kirim</span>
                    <span class="fw-semibold">Rp 20.000</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Diskon (Promo)</span>
                    <span class="fw-semibold text-danger">- Rp 50.000</span>
                </div>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold fs-5">Total Bayar</span>
                    <span class="total-price">Rp 25.077.000</span>
                </div>

                <button class="btn btn-checkout w-100 mb-3">
                    Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-1"></i>
                </button>

                <div class="mt-3">
                    <label class="form-label text-muted small mb-1">Punya Kode Promo?</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" placeholder="Masukkan kode promo">
                        <button class="btn btn-outline-primary btn-sm" type="button">Gunakan</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection