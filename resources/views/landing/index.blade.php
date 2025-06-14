<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('arsha-1.0.0/assets/img/favicon.png')}}" rel="icon">
    <link href="{{ asset('arsha-1.0.0/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('arsha-1.0.0/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('arsha-1.0.0/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{ asset('arsha-1.0.0/assets/vendor/aos/aos.css')}}" rel="stylesheet">
    <link href="{{ asset('arsha-1.0.0/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
    <link href="{{ asset('arsha-1.0.0/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('arsha-1.0.0/assets/css/main.css')}}" rel="stylesheet">

    <!-- =======================================================
    * Template Name: Arsha
    * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
    * Updated: Feb 22 2025 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
    <style>
        :root {
            --primary-color: #0e2b5c;
            --secondary-color: #17366d;
            --accent-color: #3498db;
            --light-bg: #f8f9fa;
            --shadow-light: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 8px 25px rgba(0, 0, 0, 0.15);
            --gradient-primary: linear-gradient(135deg, #0e2b5c 0%, #17366d 100%);
        }

        .services.section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .services.section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(14, 43, 92, 0.05) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
        }

        .services .section-title {
            position: relative;
            z-index: 2;
            margin-bottom: 60px;
        }

        .services .section-title h2 {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--primary-color);
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .services .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .services .section-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
            font-weight: 400;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Carousel Styles */
        .partners-carousel-container {
            /*position: relative;*/
            /*overflow: hidden;*/
            width: 100%;
            margin: 40px 0;
        }

        .partners-carousel {
            display: flex;
            width: max-content;
            animation: scroll-left 30s linear infinite;
            margin: 20px 0;
        }

        .partners-carousel:hover {
            animation-play-state: paused;
            margin: 20px 0;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .partner-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            min-width: 280px;
            height: 320px;
            box-shadow: var(--shadow-light);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            margin-right: 30px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .partner-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            transition: left 0.6s;
        }

        .partner-card:hover::before {
            left: 100%;
        }

        .partner-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-medium);
            border-color: var(--accent-color);
        }

        .partner-logo {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 15px;
            margin: 0 auto 20px;
            display: block;
            border: 3px solid #f8f9fa;
            transition: all 0.3s ease;
        }

        .partner-card:hover .partner-logo {
            border-color: var(--accent-color);
            transform: scale(1.05);
        }

        .partner-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 12px;
            text-align: center;
            transition: color 0.3s ease;
            text-decoration: none;
        }

        .partner-card:hover .partner-name {
            color: var(--accent-color);
        }

        .partner-address {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
            text-align: center;
            margin-bottom: 20px;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .partner-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-primary);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .partner-link:hover {
            background: linear-gradient(135deg, #17366d 0%, #0e2b5c 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(14, 43, 92, 0.3);
            text-decoration: none;
        }

        .partner-link::after {
            content: '→';
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .partner-link:hover::after {
            transform: translateX(3px);
        }

        /* Gradient overlays for seamless scroll effect */
        .partners-carousel-container::before,
        .partners-carousel-container::after {
            content: '';
            position: absolute;
            top: 0;
            width: 100px;
            height: 100%;
            z-index: 10;
            pointer-events: none;
        }

        .partners-carousel-container::before {
            left: 0;
            background: linear-gradient(to right, #f8f9fa, transparent);
        }

        .partners-carousel-container::after {
            right: 0;
            background: linear-gradient(to left, #f8f9fa, transparent);
        }

        /* Header Styles */
        .header {
            background: var(--gradient-primary);
            padding: 15px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .logo {
            text-decoration: none;
        }

        .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .sitename {
            font-size: 24px;
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
            color: white;
        }

        .navmenu ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navmenu li {
            margin: 0 20px;
        }

        .navmenu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navmenu a:hover,
        .navmenu a.active {
            color: var(--accent-color);
        }

        .btn-getstarted {
            background: white;
            color: var(--primary-color);
            padding: 10px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-getstarted:hover {
            background: var(--accent-color);
            color: white;
        }

        /* Hero Section */
        .hero {
            background: var(--gradient-primary);
            padding: 120px 0 80px;
            color: white;
            text-align: center;
        }

        .hero h2 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background: var(--primary-color);
            color: white;
            padding: 60px 0 30px;
        }

        .footer-about {
            margin-bottom: 30px;
        }

        .sitename {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer-contact p {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .footer-contact i {
            margin-right: 10px;
            color: var(--accent-color);
        }

        .copyright {
            background-color: #17366d;
            color: white;
            text-align: center;
            padding: 15px 0;
            font-family: 'Arial', sans-serif;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .services .section-title h2 {
                font-size: 2.2rem;
            }

            .partner-card {
                min-width: 250px;
                padding: 25px 20px;
            }

            .services.section {
                padding: 60px 0;
            }

            .hero h2 {
                font-size: 2.5rem;
            }

            .partners-carousel {
                animation-duration: 40s;
            }
        }
    </style>
</head>

<body class="index-page">

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="#" class="logo d-flex align-items-center me-auto">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="{{ asset('arsha-1.0.0/assets/img/logo_jti.png')}}" alt="">
            <span class="sitename"
                  style="font-size:24px; font-weight:bold; font-family:'Poppins', sans-serif; color:white;">SIMagang</span>

        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="#hero" class="active">Beranda</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#services">Mitra</a></li>
                <li><a href="#contact">Kontak Kami</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="btn-getstarted" href="/login">Login</a>

    </div>
    <style>
        .hero {
            position: relative;
            width: 100%;
            height: 100vh;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.4), #0e2b5c 70%),
            url("{{ URL::asset('arsha-1.0.0/assets/img/JTI Polinema.png') }}") no-repeat center center;
            background-size: cover;
            display: flex;
            align-items: center;
            color: white;
            padding-left: 50px;
            box-sizing: border-box;
        }

        .hero-text {
            max-width: 600px;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 0.5em;
        }

        p {
            font-size: 1.2em;
            line-height: 1.5;
        }

        .copyright {
            background-color: #17366d;
            /* Warna biru tua sesuai gambar */
            color: white;
            text-align: center;
            padding: 15px 0;
            font-family: 'Arial', sans-serif;
            font-size: 14px;
        }
    </style>
</header>

<main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
        <div class="container">
            <div class="row align-items-center"> <!-- satu row untuk sejajarkan -->

                <!-- Gambar di kiri -->
                <div class="col-lg-6" data-aos="zoom-out" data-aos-delay="200">
                    <!-- <img src="{{ asset('arsha-1.0.0/assets/img/JTI Polinema.png')}}" class="img-fluid animated"
                            alt=""> -->
                </div>

                <!-- Teks di kanan -->
                <div class="col-lg-6" data-aos="fade-up">
                    <h2><strong>SELAMAT DATANG <br> DI SIMagang</strong></h2>
                    <p>
                        Solusi Praktis dan Terintegrasi untuk<br>
                        Informasi dan Pengajuan Magang<br>
                        Mahasiswa Jurusan Teknologi Informasi
                    </p>
                </div>
            </div>
        </div>

    </section><!-- /Hero Section -->
    <!-- About Section -->
    <section id="about" class="about section">

        <!-- Section Title -->
        <div class="container" data-aos="fade-up">
            <h2 style="text-align: left;">LEBIH LANJUT</h2>
            <p style="text-align: left;margin-bottom: 50px;">SIMagang</p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
                    <p>
                        SiMagang adalah platform resmi Jurusan Teknologi Informasi<br>
                        POLINEMA yang dirancang untuk memudahkan mahasiswa<br>
                        dalam mencari informasi seputar tempat magang, serta<br>
                        menjembatani komunikasi dan proses administrasi antara<br>
                        mahasiswa dan perusahaan mitra.<br>

                        Temukan peluang magang terbaik, ajukan permohonan secara<br>
                        online, dan pantau perkembangan magang Anda dengan lebih<br>
                        mudah dan terorganisir.
                    </p>
                    <!-- <ul>
                    <li><i class="bi bi-check2-circle"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span>
                    </li>
                    <li><i class="bi bi-check2-circle"></i> <span>Duis aute irure dolor in reprehenderit in voluptate velit.</span>
                    </li>
                    <li><i class="bi bi-check2-circle"></i>
                        <span>Ullamco laboris nisi ut aliquip ex ea commodo</span></li>
                </ul> -->
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <a href="#" class="read-more"><span>Mulai</span><i class="bi bi-arrow-right"></i></a>
                    </div>

                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
                    <img src="{{ asset('arsha-1.0.0/assets/img/Untitled design.png')}}" class="img-fluid animated"
                         alt="">
                </div>
            </div>
        </div>
    </section><!-- /About Section -->
    <!-- Partners Section -->
    <section id="services" class="services section light-background">
        <div class="container">
            <!-- Section Title -->
            <div class="section-title text-center">
                <h2>PERUSAHAAN MITRA</h2>
                <p class="section-subtitle">
                    Bergabunglah dengan jaringan perusahaan terpercaya yang telah bermitra dengan kami untuk memberikan
                    pengalaman magang terbaik bagi mahasiswa.
                </p>
            </div>

            <!-- Partners Carousel -->
            <div class="partners-carousel-container">
                <div class="partners-carousel">
                    @foreach($data as $index => $item)

                        <div class="partner-card">
                            <img src="{{ $item->getFotoProfilPath() }}" alt="{{ $item->nama }} Logo"
                                 class="partner-logo">
                            <h4 class="partner-name">{{ $item->nama }}</h4>
                            <p class="partner-address">
                                {{ $item->alamat }}
                            </p>
                            <a href="{{ $item->website }}" class="partner-link">
                                Kunjungi Website
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</main>

<section id="contact" class="contact section light-background" style="padding-bottom: 0;">
    <footer id="footer" class="footer">
        <div class="container footer-top">
            <!-- Baris Utama -->
            <div class="row gy-4 align-items-start">

                <!-- Kolom Kiri: Info & Kontak -->
                <div class="col-lg-8 col-md-6 footer-about">
                    <a href="index.html" class="d-flex align-items-center">
                        <span class="sitename">Tentang kami</span>
                    </a>

                    <!-- Alamat -->
                    <div class="footer-contact pt-3">
                        <p>Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141
                        </p>
                    </div>

                    <!-- Kontak & Logo dalam 1 baris -->
                    <div class="row">
                        <!-- Kolom Telepon -->
                        <div class="col-md-4">
                            <p class="bi bi-telephone"><span>+0341 - 404424/404425</span></p>
                            <p class="bi bi-envelope"
                               style="font-family: Arial, sans-serif; text-decoration: underline; font-size: 18px;">
                                <span>humas@polinema.ac.id</span>
                            </p>

                        </div>

                        <!-- Kolom Email -->
                        <div class="col-md-4">
                            <p class="bi bi-instagram"><span> polinema.campus</span></p>
                            <p class="bi bi-instagram"><span> jtipolinema</span></p>
                        </div>

                        <!-- Kolom Logo -->
                    </div>

                </div>
                <div class="col-md-6 col-lg-4 d-flex center align-items-start">
                    <img src="{{ asset('arsha-1.0.0/assets/img/logo_jti_baru.png') }}" alt="Logo JTI"
                         class="img-fluid" style="max-height: 400px;">
                </div>

            </div>
        </div>
    </footer>
</section>

<div class="copyright">
    Copyright © 2025. Sistem Rekomendasi Magang
</div>


<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="{{ asset('arsha-1.0.0/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}">
</script>
<script src="{{ asset('arsha-1.0.0/assets/vendor/php-email-form/validate.js')}}">
</script>
<script src="{{ asset('arsha-1.0.0/assets/vendor/aos/aos.js')}}">
</script>
<script src="{{ asset('arsha-1.0.0/assets/vendor/glightbox/js/glightbox.min.js')}}">
</script>
<script src="{{ asset('arsha-1.0.0/assets/vendor/swiper/swiper-bundle.min.js')}}">
</script>
<script src="{{ asset('arsha-1.0.0/assets/vendor/waypoints/noframework.waypoints.js')}}">
</script>
<script src="{{ asset('arsha-1.0.0/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js')}}">
</script>
<script src="{{ asset('arsha-1.0.0/assets/vendor/isotope-layout/isotope.pkgd.min.js')}}">
</script>

<!-- Main JS File -->
<script src="{{ asset('arsha-1.0.0/assets/js/main.js')}}"></script>

</body>

</html>
