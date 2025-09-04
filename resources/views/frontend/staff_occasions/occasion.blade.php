<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ $occasion->title }} | {{ app_name() }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="https://www.arikair.com/assets/images/favicon.ico" rel="icon">
{{--    <link href="{{ asset('occasion-assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">--}}

    <!-- Google Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css?family=Barlow:300,300i,400,400i,700,700i|Yeseva+One:300,400,500,700,800" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('occasion-assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('occasion-assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('occasion-assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('occasion-assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('occasion-assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('occasion-assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('occasion-assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <style>
        .form-control {
            line-height: 2.5;
        }

        .btn-primary {
            background-color: #032560;
            border: 2px solid #032589;
        }
    </style>
</head>

<body>

{{--<!-- ======= Top Bar ======= -->--}}
{{--<section id="topbar" class="d-flex align-items-center">--}}
{{--    <div class="container d-flex justify-content-center justify-content-md-between">--}}
{{--        <div class="contact-info d-flex align-items-center">--}}
{{--            <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">contact@arikair.com</a></i>--}}
{{--            <i class="bi bi-phone d-flex align-items-center ms-4"><span>01 279 9999</span></i>--}}
{{--        </div>--}}
{{--        <div class="social-links d-none d-md-flex align-items-center">--}}
{{--            <a href="https://twitter.com/arikairlineng?lang=en" class="twitter"><i class="bi bi-twitter"></i></a>--}}
{{--            <a href="https://www.facebook.com/FlyArikAir/" class="facebook"><i class="bi bi-facebook"></i></a>--}}
{{--            <a href="https://www.instagram.com/flyarikair/?hl=en" class="instagram"><i class="bi bi-instagram"></i></a>--}}
{{--            <a href="https://ng.linkedin.com/company/arik-air-ltd" class="linkedin"><i class="bi bi-linkedin"></i></a>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section><!-- End Top Bar-->--}}

<!-- ======= Header ======= -->
<header id="header" class="d-flex align-items-center">
    <div class="container d-flex justify-content-between">

        <div id="logo">
{{--            <h1><a href="index.html">Reve<span>al</span></a></h1>--}}
            <!-- Uncomment below if you prefer to use an image logo -->
            <a href="{{ route('frontend.index') }}"><img src="{{ asset('img/logo-coloured.png') }}" alt=""></a>
        </div>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto" href="{{ route('frontend.index') }}">Integral</a></li>
                <li><a class="nav-link scrollto" href="#testimonials">Condolences <span class="badge bg-primary" style="margin-left: 5px">{{ $occasion->messages->count() }}</span></a></li>
                <li><a class="nav-link scrollto" href="#portfolio">Gallery</a></li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->
    </div>
</header><!-- End Header -->

<!-- ======= hero Section ======= -->
{{--<section id="hero">--}}

{{--    <div class="hero-content" data-aos="fade-up">--}}
{{--        <h2>{{ $occasion->title }} - <span>{{ $occasion->staff_name }}</span><br></h2>--}}
{{--        <div>--}}
{{--            <a href="#about" class="btn-get-started scrollto">Get Started</a>--}}
{{--            <a href="#portfolio" class="btn-projects scrollto">Our Projects</a>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="hero-slider swiper">--}}
{{--        <div class="swiper-wrapper">--}}
{{--            <div class="swiper-slide" style="background-image: url('https://res.cloudinary.com/ikformula/image/upload/v1675340467/Arik/pexels-aliona-_-pasha-3892172.jpg');"></div>--}}
{{--            <div class="swiper-slide" style="background-image: url('https://res.cloudinary.com/ikformula/image/upload/v1675340425/Arik/pexels-lukas-hartmann-1462011.jpg');"></div>--}}
{{--            <div class="swiper-slide" style="background-image: url('https://res.cloudinary.com/ikformula/image/upload/v1675340933/Arik/pexels-joran-quinten-3775331.jpg');"></div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--</section><!-- End Hero Section -->--}}

<main id="main">

    <!-- ======= About Section ======= -->
    <section id="about">
        <div class="container" data-aos="fade-up">
            <div class="row">
                <div class="col-lg-3 col-md-5 about-img">
                    <img src="https://res.cloudinary.com/ikformula/image/upload/v1675857168/Arik/francis-okafor/franco_4.jpg" alt="">
                </div>

                <div class="col-lg-7 col-md-7 content">
                    <h2>{{ $occasion->staff_name }}</h2>
                    <p>
                        {!!  $occasion->write_up !!}
                    </p>
                </div>
            </div>
        </div>
    </section><!-- End About Section -->


{{--    <!-- ======= Call To Action Section ======= -->--}}
{{--    <section id="call-to-action">--}}
{{--        <div class="container" data-aos="zoom-out">--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-9 text-center text-lg-start">--}}
{{--                    <h3 class="cta-title">Upload your own photo</h3>--}}
{{--                    <p class="cta-text"> Do you have a photo about {{ $occasion->staff_name }} you'd like to share here? Click the button and upload.</p>--}}
{{--                </div>--}}
{{--                <div class="col-lg-3 cta-btn-container text-center">--}}
{{--                    <a class="cta-btn align-middle" href="#">Upload Photo</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section><!-- End Call To Action Section -->--}}

    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials">
        <div class="container" data-aos="fade-up">
            <div class="section-header">
                <h2>{{ \Illuminate\Support\Str::plural($occasion->messages_name, 2) }}</h2>
                <p>You can swipe or drag to the left or to the right to navigate.</p>
            </div>

            <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper-wrapper">
                    @foreach($occasion->messages as $message)
                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <p>
                                <img src="{{ asset('occasion-assets/img/quote-sign-left.png') }}" class="quote-sign-left" alt="">
                                {{ $message->message_body }}
                                <img src="{{ asset('occasion-assets/img/quote-sign-right.png') }}" class="quote-sign-right" alt="">
                            </p>
{{--                            <img src="{{ asset('occasion-assets/img/testimonial-1.jpg') }}" class="testimonial-img" alt="">--}}
                            <h3>{{ $message->displayed_name }}</h3>
                            <h4>{{ $message->writer_title }}</h4>
                        </div>
                    </div><!-- End testimonial item -->
                    @endforeach
                </div>
{{--                <div class="swiper-pagination"></div>--}}
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

        </div>
    </section><!-- End Testimonials S   ection -->



    <!-- ======= Contact Section ======= -->
    <section id="contact">
        <div class="container" data-aos="fade-up">
            <div class="section-header">
                <h2>Add your {{ $occasion->messages_name }}</h2>
                <p>Fill in the form below and click submit.</p>
            </div>
        </div>

        <div class="container">
            <div class="form">
                <form action="{{ route('frontend.occasions.addMessage', $occasion->slug) }}" method="post" role="form" class="php-email-formm">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="text" name="displayed_name" class="form-control" id="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group col-md-6 mt-3 mt-md-0">
                            <input type="text" class="form-control" name="writer_title" id="writer_title" placeholder="Your Title" required>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <textarea class="form-control" name="message_body" rows="5" placeholder="Message" required></textarea>
                    </div>


                    <div class="text-center my-3"><button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button></div>
                </form>
            </div>

        </div>
    </section><!-- End Contact Section -->


    <!-- ======= Gallery Section ======= -->
    <section id="portfolio" class="portfolio">
        <div class="container" data-aos="fade-up">
            <div class="section-header">
                <h2>Gallery</h2>
            </div>

            <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">
                @foreach($occasion->gallery_images() as $gallery_photo)
                    <div class="col-lg-2 col-md-4 portfolio-item filter-app">
                        <img src="{{ $gallery_photo->photo_path }}" class="img-fluid" alt="">
                        <div class="portfolio-info">

{{--                            <p>{{ ucfirst($gallery_photo->category) }} images</p>--}}
                            <a href="{{ $gallery_photo->photo_path }}" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="{{ $occasion->staff_name }} {{ $loop->iteration }}"><i class="bx bx-glasses"></i>  <h4>{{ $gallery_photo->title ?? $occasion->staff_name }}</h4></a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section><!-- End Portfolio Section -->

</main><!-- End #main -->

<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="container">
        <div class="copyright">
            &copy; Copyright <strong>Arik Air</strong>. All Rights Reserved
        </div>
    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('occasion-assets/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('occasion-assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('occasion-assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('occasion-assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('occasion-assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('occasion-assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('occasion-assets/js/main.js') }}"></script>
@include('includes.partials.messages-toastr')
</body>

</html>
