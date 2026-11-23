<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Eduwork Store')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* --- Global Styling --- */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1; /* Memastikan footer selalu di bawah jika konten pendek */
        }
        
        /* --- Footer Global --- */
        .footer {
            background-color: #ffffff;
            padding: 40px 0 20px 0;
            margin-top: 50px;
            border-top: 1px solid #e9ecef;
        }
    </style>
    
    <!-- Stack untuk CSS tambahan per halaman -->
    @stack('styles')
</head>
<body>

    <!-- Include Navbar -->
    @include('navbar')

    <!-- Konten Utama (Akan diisi oleh home / cart) -->
    <main>
        @yield('content')
    </main>

    <!-- Include Footer -->
    @include('footer')

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Stack untuk JS tambahan per halaman -->
    @stack('scripts')
</body>
</html>