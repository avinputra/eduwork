<nav class="navbar navbar-expand-lg navbar-custom sticky-top" style="background-color: #fff; box-shadow: 0 2px 15px rgba(0,0,0,0.05); padding: 15px 0;">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand" href="{{ url('/') }}" style="font-weight: 700; font-size: 1.5rem; color: #0d6efd !important; letter-spacing: -0.5px;">
            <i class="bi bi-bag-heart-fill me-1"></i> Eduwork Store
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Search Bar (Hanya tampil di halaman home) -->
            @if(request()->is('/'))
            <form class="d-flex mx-auto my-3 my-lg-0 w-100" style="max-width: 500px;">
                <div class="input-group" style="border-radius: 50px; overflow: hidden; background-color: #f1f3f5; border: 1px solid transparent;">
                    <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Cari produk impianmu di sini...">
                </div>
            </form>
            @endif
            
            <!-- Icon Kanan -->
            <div class="d-flex align-items-center gap-4 ms-auto">
                <a href="#" class="text-decoration-none text-dark fw-medium d-none d-lg-block">Masuk</a>
                <a href="#" class="btn btn-primary rounded-pill px-4 d-none d-lg-block">Daftar</a>
                
                <a href="{{ url('/cart') }}" class="text-decoration-none" style="position: relative; color: #495057;">
                    <i class="bi bi-cart3 fs-4"></i>
                    <!-- Badge statis (Nanti bisa diganti dengan variabel jumlah cart dari Controller) -->
                    <span class="badge bg-danger" style="position: absolute; top: -8px; right: -10px; font-size: 0.65rem; padding: 3px 6px; border-radius: 50%;">2</span>
                </a>
            </div>
        </div>
    </div>
</nav>