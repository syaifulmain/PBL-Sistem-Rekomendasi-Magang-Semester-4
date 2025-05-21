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
                    <li><a href="#hero" class="active">Home</a></li>
                    <li><a href="#about">More</a></li>
                    <li><a href="#services">Mitra</a></li>
                    <li><a href="#contact">Tentang kami</a></li>
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
                <h2 style="text-align: left;">MORE ABOUT</h2>
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
                            <a href="/login" class="read-more"><span><strong>Get Started</strong></span></a>
                        </div>

                    </div>
                    <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
                        <img src="{{ asset('arsha-1.0.0/assets/img/Untitled design.png')}}" class="img-fluid animated"
                            alt="">
                    </div>
                </div>

        </section><!-- /About Section -->

        <!-- Services Section -->
        <section id="services" class="services section light-background">

            <!-- Section Title -->
            <div class="container" data-aos="fade-up">
                <h2 style="text-align: left;margin-bottom: 50px;">PERUSAHAAN MITRA</h2>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item position-relative">
                            <!-- Ganti ikon dengan gambar -->
                            <div class="icon">
                                <img src="arsha-1.0.0/assets/img/building.png" alt="Icon"
                                    style="width: 40px; height: 40px;">
                                <strong>PASURUAN</strong>
                            </div>
                            <h4><a href="" class="stretched-link">PT. Amerta Indah Otsuka</a></h4>

                            <br>Jl. Raya Pasuruan No.KM11, Tromo Barat, Pacar Keling, Kec. Kejayan, Pasuruan, Jawa Timur
                            67172

                            <p><i class="bi bi-clock"
                                    style="font-size: 20px; margin-right: 10px;"></i><strong>01-04-2025 S/D
                                    03-05-2025</strong></p>

                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Usaha
                            Perjalanan Wisata
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Bahasa Inggris
                            Untuk Komunikasi Bisnis dan Profesional
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Bahasa Inggris
                            Untuk Industri Pariwisata
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Teknik
                            Elektronika
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Teknik
                            Informatika
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Sistem
                            Informasi
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item position-relative">
                            <!-- Ganti ikon dengan gambar -->
                            <div class="icon">
                                <img src="arsha-1.0.0/assets/img/building.png" alt="Icon"
                                    style="width: 40px; height: 40px;">
                                <strong>PASURUAN</strong>
                            </div>
                            <h4><a href="" class="stretched-link">PT. Amerta Indah Otsuka</a></h4>

                            <br>Jl. Raya Pasuruan No.KM11, Tromo Barat, Pacar Keling, Kec. Kejayan, Pasuruan, Jawa Timur
                            67172

                            <p><i class="bi bi-clock"
                                    style="font-size: 20px; margin-right: 10px;"></i><strong>01-04-2025 S/D
                                    03-05-2025</strong></p>

                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Usaha
                            Perjalanan Wisata
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Bahasa Inggris
                            Untuk Komunikasi Bisnis dan Profesional
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Bahasa Inggris
                            Untuk Industri Pariwisata
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Teknik
                            Elektronika
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Teknik
                            Informatika
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Sistem
                            Informasi
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item position-relative">
                            <!-- Ganti ikon dengan gambar -->
                            <div class="icon">
                                <img src="arsha-1.0.0/assets/img/building.png" alt="Icon"
                                    style="width: 40px; height: 40px;">
                                <strong>PASURUAN</strong>
                            </div>
                            <h4><a href="" class="stretched-link">PT. Amerta Indah Otsuka</a></h4>

                            <br>Jl. Raya Pasuruan No.KM11, Tromo Barat, Pacar Keling, Kec. Kejayan, Pasuruan, Jawa Timur
                            67172

                            <p><i class="bi bi-clock"
                                    style="font-size: 20px; margin-right: 10px;"></i><strong>01-04-2025 S/D
                                    03-05-2025</strong></p>

                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Usaha
                            Perjalanan Wisata
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Bahasa Inggris
                            Untuk Komunikasi Bisnis dan Profesional
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Bahasa Inggris
                            Untuk Industri Pariwisata
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Teknik
                            Elektronika
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Teknik
                            Informatika
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Sistem
                            Informasi
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item position-relative">
                            <!-- Ganti ikon dengan gambar -->
                            <div class="icon">
                                <img src="arsha-1.0.0/assets/img/building.png" alt="Icon"
                                    style="width: 40px; height: 40px;">
                                <strong>PASURUAN</strong>
                            </div>
                            <h4><a href="" class="stretched-link">PT. Amerta Indah Otsuka</a></h4>

                            <br>Jl. Raya Pasuruan No.KM11, Tromo Barat, Pacar Keling, Kec. Kejayan, Pasuruan, Jawa
                            Timur 67172

                            <p><i class="bi bi-clock"
                                    style="font-size: 20px; margin-right: 10px;"></i><strong>01-04-2025 S/D
                                    03-05-2025</strong></p>


                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Usaha
                            Perjalanan Wisata
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Bahasa Inggris
                            Untuk Komunikasi Bisnis dan Profesional
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Bahasa Inggris
                            Untuk Industri Pariwisata
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Teknik
                            Elektronika
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Teknik
                            Informatika
                            <br><i class="bi bi-circle-fill" style="font-size: 8px !important;"></i> D-IV Sistem
                            Informasi
                            </p>
                        </div>
                    </div><!-- End Service Item -->
                </div>

            </div>

            <section id="contact" class="contact section light-background">
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
                &copy; Copyright 2023 Pusat Komputer Politeknik Negeri Malang
                <!-- <div class="credits"> -->
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you've purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
                <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by <a
                        href="https://themewagon.com" target="_blank">ThemeWagon</a> -->
                <!-- </div> -->
            </div>

            </footer>

            <!-- Scroll Top -->
            <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
                    class="bi bi-arrow-up-short"></i></a>

            <!-- Preloader -->
            <div id="preloader"></div>

            <!-- Vendor JS Files -->
            <script src="{{ asset('arsha-1.0.0/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}" </script>
<script src="{{ asset('arsha-1.0.0/assets/vendor/php-email-form/validate.js')}}"
            </script>
            <script src="{{ asset('arsha-1.0.0/assets/vendor/aos/aos.js')}}" </script>
<script src="{{ asset('arsha-1.0.0/assets/vendor/glightbox/js/glightbox.min.js')}}"
            </script>
            <script src="{{ asset('arsha-1.0.0/assets/vendor/swiper/swiper-bundle.min.js')}}" </script>
<script src="{{ asset('arsha-1.0.0/assets/vendor/waypoints/noframework.waypoints.js')}}"
            </script>
            <script src="{{ asset('arsha-1.0.0/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js')}}" </script>
<script src="{{ asset('arsha-1.0.0/assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"
            </script>

            <!-- Main JS File -->
            <script src="{{ asset('arsha-1.0.0/assets/js/main.js')}}"></script>

</body>

</html>