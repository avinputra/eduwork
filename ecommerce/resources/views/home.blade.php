@extends('layout')

@section('title', 'Home - Eduwork Store')

@push('styles')
<style>
    /* --- Hero Section --- */
    .hero-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        border-radius: 20px;
        color: white;
        padding: 60px 40px;
        margin-top: 20px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .hero-title { font-weight: 700; font-size: 2.5rem; line-height: 1.2; }

    /* --- Product Card --- */
    .product-card {
        border: none; border-radius: 15px; background: #fff;
        transition: all 0.3s ease; overflow: hidden; height: 100%;
    }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.08); }
    .product-img-wrapper { position: relative; height: 200px; overflow: hidden; background-color: #f8f9fa; }
    .product-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .product-card:hover .product-img { transform: scale(1.08); }
    .category-badge {
        position: absolute; top: 10px; left: 10px;
        background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(5px);
        color: #0d6efd; font-weight: 600; font-size: 0.75rem; padding: 5px 10px; border-radius: 50px;
    }
    .product-title {
        font-weight: 600; font-size: 1.1rem; color: #212529;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.8rem;
    }
    .product-price { font-weight: 700; font-size: 1.25rem; color: #198754; }
    .rating-stars { color: #ffc107; font-size: 0.85rem; }
    .btn-add-to-cart {
        background: linear-gradient(135deg, #0d6efd, #0dcaf0); border: none; border-radius: 10px;
        color: white; font-weight: 600; transition: all 0.3s ease;
    }
    .btn-add-to-cart:hover { background: linear-gradient(135deg, #0b5ed7, #0aa2c0); color: white; box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3); }
</style>
@endpush

@section('content')
<div class="container">
    
    <!-- Hero Banner -->
    <div class="hero-section shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title mb-3">Temukan Produk Terbaik<br>Hanya di Eduwork Store</h1>
                <p class="mb-4 fs-5 opacity-75">Diskon hingga 50% untuk produk pilihan. Belanja sekarang dan nikmati gratis ongkir ke seluruh Indonesia!</p>
                <a href="#produk" class="btn btn-light btn-lg text-primary fw-bold rounded-pill px-4 shadow">Mulai Belanja <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
        </div>
    </div>

    <!-- Section Judul -->
    <div class="d-flex justify-content-between align-items-end mb-4" id="produk">
        <div>
            <h3 class="fw-bold mb-1">Produk Terbaru</h3>
            <p class="text-muted mb-0">Pilihan terbaik untuk kamu</p>
        </div>
        <a href="#" class="text-decoration-none fw-semibold">Lihat Semua <i class="bi bi-chevron-right"></i></a>
    </div>

    <!-- PRODUCT GRID (10 PRODUK) -->
    <div class="row g-4">
        
        <!-- Item 1 -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card product-card">
                <div class="product-img-wrapper">
                    <span class="category-badge">Handphone</span>
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=500&auto=format&fit=crop" class="product-img" alt="Smartphone">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                    </div>
                    <h6 class="product-title mb-2">Samsung Galaxy S23 Ultra 5G 512GB</h6>
                    <div class="product-price mb-3 mt-auto">Rp 18.999.000</div>
                    <button class="btn btn-add-to-cart w-100">
                        <i class="bi bi-cart-plus me-2"></i> Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Item 2 -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card product-card">
                <div class="product-img-wrapper">
                    <span class="category-badge">Pakaian Pria</span>
                    <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?q=80&w=500&auto=format&fit=crop" class="product-img" alt="Kemeja">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <h6 class="product-title mb-2">Kemeja Flanel Casual Premium Pria</h6>
                    <div class="product-price mb-3 mt-auto">Rp 185.000</div>
                    <button class="btn btn-add-to-cart w-100">
                        <i class="bi bi-cart-plus me-2"></i> Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Item 3 -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card product-card">
                <div class="product-img-wrapper">
                    <span class="category-badge">Pakaian Wanita</span>
                    <img src="https://images.unsplash.com/photo-1576566588028-4147f3842f27?q=80&w=500&auto=format&fit=crop" class="product-img" alt="Kaos Wanita">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star"></i>
                    </div>
                    <h6 class="product-title mb-2">Kaos Oversize Wanita Cotton Combed 30s</h6>
                    <div class="product-price mb-3 mt-auto">Rp 89.000</div>
                    <button class="btn btn-add-to-cart w-100">
                        <i class="bi bi-cart-plus me-2"></i> Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Item 4 -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card product-card">
                <div class="product-img-wrapper">
                    <span class="category-badge">Elektronik</span>
                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=500&auto=format&fit=crop" class="product-img" alt="Headphone">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                    </div>
                    <h6 class="product-title mb-2">Sony WH-1000XM4 Wireless Headphones</h6>
                    <div class="product-price mb-3 mt-auto">Rp 4.299.000</div>
                    <button class="btn btn-add-to-cart w-100">
                        <i class="bi bi-cart-plus me-2"></i> Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Item 5 -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card product-card">
                <div class="product-img-wrapper">
                    <span class="category-badge">Aksesoris</span>
                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=500&auto=format&fit=crop" class="product-img" alt="Jam Tangan">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <h6 class="product-title mb-2">Jam Tangan Pria Kulit Original</h6>
                    <div class="product-price mb-3 mt-auto">Rp 450.000</div>
                    <button class="btn btn-add-to-cart w-100">
                        <i class="bi bi-cart-plus me-2"></i> Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Item 6 -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card product-card">
                <div class="product-img-wrapper">
                    <span class="category-badge">Sepatu</span>
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=500&auto=format&fit=crop" class="product-img" alt="Sepatu">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star"></i>
                    </div>
                    <h6 class="product-title mb-2">Nike Air Max Sneakers Pria</h6>
                    <div class="product-price mb-3 mt-auto">Rp 1.899.000</div>
                    <button class="btn btn-add-to-cart w-100">
                        <i class="bi bi-cart-plus me-2"></i> Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Item 7 -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card product-card">
                <div class="product-img-wrapper">
                    <span class="category-badge">Tas</span>
                    <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=500&auto=format&fit=crop" class="product-img" alt="Tas">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                    </div>
                    <h6 class="product-title mb-2">Tas Ransel Kanvas Anti Air Premium</h6>
                    <div class="product-price mb-3 mt-auto">Rp 250.000</div>
                    <button class="btn btn-add-to-cart w-100">
                        <i class="bi bi-cart-plus me-2"></i> Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Item 8 -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card product-card">
                <div class="product-img-wrapper">
                    <span class="category-badge">Elektronik</span>
                    <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?q=80&w=500&auto=format&fit=crop" class="product-img" alt="Laptop">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <h6 class="product-title mb-2">MacBook Air M2 2023 13 Inch 256GB</h6>
                    <div class="product-price mb-3 mt-auto">Rp 16.499.000</div>
                    <button class="btn btn-add-to-cart w-100">
                        <i class="bi bi-cart-plus me-2"></i> Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Item 9 -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card product-card">
                <div class="product-img-wrapper">
                    <span class="category-badge">Kamera</span>
                    <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=500&auto=format&fit=crop" class="product-img" alt="Kamera">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <h6 class="product-title mb-2">Fujifilm X-T4 Mirrorless Camera</h6>
                    <div class="product-price mb-3 mt-auto">Rp 22.500.000</div>
                    <button class="btn btn-add-to-cart w-100">
                        <i class="bi bi-cart-plus me-2"></i> Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Item 10 -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="card product-card">
                <div class="product-img-wrapper">
                    <span class="category-badge">Gaming</span>
                    <img src="https://images.unsplash.com/photo-1600080972464-8e5f35f63d08?q=80&w=500&auto=format&fit=crop" class="product-img" alt="Mouse Gaming">
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="rating-stars mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                    </div>
                    <h6 class="product-title mb-2">Logitech G Pro X Superlight</h6>
                    <div class="product-price mb-3 mt-auto">Rp 1.799.000</div>
                    <button class="btn btn-add-to-cart w-100">
                        <i class="bi bi-cart-plus me-2"></i> Keranjang
                    </button>
                </div>
            </div>
        </div>

    </div>
    
    <!-- Pagination -->
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled"><a class="page-link rounded-start-pill px-3" href="#">Previous</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link rounded-end-pill px-3" href="#">Next</a></li>
        </ul>
    </nav>

</div>
@endsection