{{--<!DOCTYPE html>--}}
{{--<html lang="en">--}}

{{--<head>--}}
{{--    <title>Mỹ phẩm chính hãng</title>--}}
{{--    <meta charset="utf-8" />--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1" />--}}

{{--    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />--}}
{{--    <link rel="stylesheet" href="{{ asset('assets/css/templatemo.css') }}" />--}}
{{--    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />--}}

{{--    <!-- Load fonts style after rendering the layout styles -->--}}
{{--    <link rel="stylesheet"--}}
{{--        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap" />--}}
{{--    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}" />--}}

{{--    <!-- Slick -->--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick.min.css') }}" />--}}
{{--    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick-theme.css') }}" />--}}
{{--</head>--}}

{{--<body>--}}
{{--    --}}
{{--    <!-- Header -->--}}
{{--    <nav class="navbar navbar-expand-lg navbar-light shadow">--}}

{{--        <div class="container d-flex justify-content-between align-items-center">--}}
{{--            <a class="navbar-brand text-success logo h1 align-self-center"--}}
{{--                href="{{ URL::to(route('screen_home')) }}">--}}
{{--                Mỹ phẩm MIE--}}
{{--            </a>--}}

{{--            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"--}}
{{--                data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false"--}}
{{--                aria-label="Toggle navigation">--}}
{{--                <span class="navbar-toggler-icon"></span>--}}
{{--            </button>--}}

{{--            <div class="align-self-center collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-between"--}}
{{--                id="templatemo_main_nav">--}}
{{--                <div class="flex-fill">--}}
{{--                </div>--}}
{{--                <div class="navbar align-self-center d-flex">--}}
{{--                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">--}}
{{--                        <div class="input-group">--}}
{{--                            <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ..." />--}}
{{--                            <div class="input-group-text">--}}
{{--                                <i class="fa fa-fw fa-search"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal"--}}
{{--                        data-bs-target="#templatemo_search">--}}
{{--                        <i class="fa fa-fw fa-search text-dark mr-2"></i>--}}
{{--                    </a>--}}
{{--                    <a class="nav-icon d-none d-lg-inline" href="{{ URL::to(route('search_order')) }}">--}}
{{--                        <i class="fa fa-fw fa-file text-dark mr-2"></i>--}}
{{--                    </a>--}}
{{--                    <a class="nav-icon position-relative text-decoration-none" href="{{ URL::to(route('cart')) }}">--}}
{{--                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>--}}
{{--                        @if (Cart::total() > 0)--}}
{{--                            <span--}}
{{--                                class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">{{ Cart::content()->groupBy('id')->count() }}</span>--}}
{{--                        @endif--}}
{{--                    </a>--}}
{{--                    <div class="dropdown">--}}
{{--                        @if (Auth::check() && Auth::user()->role->name === Config::get('auth.roles.user'))--}}
{{--                            <a class="nav-icon position-relative text-decoration-none" type="button" id="dropdownMenu2"--}}
{{--                                data-bs-toggle="dropdown">--}}
{{--                                <i class="fa fa-fw fa-user text-dark mr-3"></i>--}}
{{--                            </a>--}}
{{--                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">--}}
{{--                                <li>--}}
{{--                                    <a href="{{ URL::to(route('screen_info')) }}" class="dropdown-item"--}}
{{--                                        id="filter_menu" type="button">--}}
{{--                                        {{ auth()->user()->name }}--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a href="{{ URL::to(route('history_order')) }}" class="dropdown-item"--}}
{{--                                        id="filter_menu" type="button">--}}
{{--                                        Lịch sử đơn hàng--}}
{{--                                    </a>--}}
                                        <a href="{{ route('reservation.history') }}" 
                                           class="dropdown-item"
                                           id="filter_menu" 
                                           type="button">
                                            Lịch khám của tôi
</a>
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <a class="dropdown-item" href="{{ URL::to(route('logout')) }}" type="button">--}}
{{--                                        Đăng xuất </a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        @else--}}
{{--                            <a class="nav-icon position-relative text-decoration-none"--}}
{{--                                href="{{ URL::to(route('screen_login')) }}">--}}
{{--                                <i class="fa fa-fw fa-sign-in-alt text-dark mr-3"></i>--}}
{{--                            </a>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    --}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </nav>--}}
{{--    <!-- Close Header -->--}}

{{--    <!-- Modal -->--}}
{{--    <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog"--}}
{{--        aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="w-100 pt-1 mb-5 text-right">--}}
{{--                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--            </div>--}}
{{--            <form action="{{ URL::to(route('search_products')) }}" method="get"--}}
{{--                class="modal-content modal-body border-0 p-0">--}}
{{--                <div class="input-group mb-2">--}}
{{--                    <input type="text" class="form-control" id="inputModalSearch" name="product"--}}
{{--                        placeholder="Nhập vào tên sản phẩm ..." />--}}
{{--                    <button type="submit" class="input-group-text bg-success text-light">--}}
{{--                        <i class="fa fa-fw fa-search text-white"></i>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- End Modal -->--}}




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Nha khoa NA</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/lib/animate/animate.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('lib/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('lib/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner"></div>
    </div>
    <!-- Spinner End -->

    <!-- Full Screen Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="background: rgba(9, 30, 62, .7);">
                <div class="modal-header border-0">
                    <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body d-flex align-items-center justify-content-center" action="{{ URL::to(route('search_products')) }}" method="get">
                    <div class="input-group" style="max-width: 600px;">
                        <input type="text" style="background-color: white!important;" name="product" class="form-control bg-transparent border-primary p-3" placeholder="Nhập tên sản phẩm">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Full Screen Search End -->

    <!-- Full Screen Search Start -->
    <div class="modal fade" id="searchOrder" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="background: rgba(9, 30, 62, .7);">
                <div class="modal-header border-0">
                    <button type="button" class="btn bg-white btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body d-flex align-items-center justify-content-center" action="{{ URL::to(route('search_order')) }}" method="get">
                    <div class="input-group" style="max-width: 600px;">
                        <input type="text" style="background-color: white!important;" name="code_invoice" class="form-control bg-transparent border-primary p-3" placeholder="Nhập mã đơn hàng">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Full Screen Search End -->

    <!-- Navbar & Carousel Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="{{ URL::to(route('screen_home')) }}" class="navbar-brand p-0">
                <h1 class="m-0"><i class="fa fa-user-tie me-2"></i>Nha Khoa NA</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="{{ URL::to(route('screen_home')) }}" class="nav-item nav-link">Trang chủ</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Dịch vụ</a>
                        <div class="dropdown-menu m-0" style="width: 450px; min-heigh: 300px;">
                            <div class="row">
                                @if ($services)
                                    @foreach ($services as $key => $service )
                                        <div class="col-6"> <a href="{{ URL::to(route('service_info', ['id' => $service->id])) }}" class="dropdown-item ">{{$service->name}}</a> </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Bác sĩ</a>
                        <div class="dropdown-menu m-0" style="width: 450px;">
                            <div class="row">
                                @if ($doctors)
                                    @foreach ($doctors as $key => $doctor )
                                        <div class="col-6"> <a href="{{ URL::to(route('doctor_info', ['id' => $doctor->id])) }}" class="dropdown-item ">{{$doctor->name}}</a> </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Danh mục sản phẩm</a>
                        <div class="dropdown-menu m-0" style="width: 450px;">
                            <div class="row">
                                @if ($categories)
                                    @foreach ($categories as $key => $category )
                                        <div class="col-6"> <a href="{{ URL::to(route('search_products', ['category' => $category->id])) }}" class="dropdown-item ">{{$category->name}}</a> </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="#" class="nav-item nav-link" data-bs-toggle="modal" data-bs-target="#searchOrder">Kiểm tra đơn hàng</a>
                    <a href="{{ URL::to(route('cart')) }}" class="nav-item nav-link">Giỏ hàng</a>
                    @if (Auth::check() && Auth::user()->role->name === Config::get('auth.roles.user'))
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ auth()->user()->name }}</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{ URL::to(route('screen_info')) }}" class="dropdown-item">Thông tin cá nhân</a>
                                <a href="{{ URL::to(route('history_order')) }}" class="dropdown-item"> Lịch sử đơn hàng  </a>
                                <a href="{{ route('reservation.history') }}" class="dropdown-item">Lịch khám của tôi</a>
                                <a href="{{ URL::to(route('logout')) }}" class="dropdown-item"> Đăng xuất </a>
                            </div>
                        </div>
                    @else
                        <a href="{{ URL::to(route('screen_login')) }}" class="nav-item nav-link">Đăng nhập</a>
                    @endif
                </div>
                <button type="button" class="btn text-primary ms-3" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i></button>
            </div>
        </nav>
    </div>

    <!-- Navbar & Carousel End -->

    @yield('user_content')
    <div class="container-fluid bg-dark text-light mt-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-4">
                        <a href="index.html" class="navbar-brand">
                            <h1 class="m-0 text-white"><i class="fa fa-user-tie me-2"></i>Nha khoa NA</h1>
                        </a>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                        </div>
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <div class="section-title section-title-sm position-relative pb-3 mb-4">
                                <h3 class="text-light mb-0">Liên lạc</h3>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <p class="mb-0">450 Lê Văn Việt Quận 9</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open text-primary me-2"></i>
                                <p class="mb-0">info@example.com</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <p class="mb-0">+012 345 67890</p>
                            </div>
                            <div class="d-flex mt-4">
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                                <a class="btn btn-primary btn-square me-2" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                                <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram fw-normal"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-white" style="background: #061429;">
        <div class="container text-center">
            <div class="row justify-content-end">
                <div class="col-lg-8 col-md-6">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/lib/wow/wow.min.js')}}"></script>
    <script src="{{ asset('lib/lib/easing/easing.min.js')}}"></script>
    <script src="{{ asset('lib/lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{ asset('lib/lib/counterup/counterup.min.js')}}"></script>
    <script src="{{ asset('lib/lib/owlcarousel/owl.carousel.min.js')}}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('lib/js/main.js')}}"></script>

{{--    <!-- Start Footer -->--}}
{{--    <footer class="bg-dark" id="tempaltemo_footer">--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-4 pt-5">--}}
{{--                    <h2 class="h2 text-success border-bottom pb-3 border-light logo">--}}
{{--                        Mỹ phẩm MIE--}}
{{--                    </h2>--}}
{{--                    <ul class="list-unstyled text-light footer-link-list">--}}
{{--                        <li>--}}
{{--                            <i class="fas fa-map-marker-alt fa-fw"></i>--}}
{{--                            450 Lê Văn Việt TP Thủ Đức--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <i class="fa fa-phone fa-fw"></i>--}}
{{--                            <a class="text-decoration-none" href="tel:010-020-0340">0123456789</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <i class="fa fa-envelope fa-fw"></i>--}}
{{--                            <a class="text-decoration-none" href="mailto:info@company.com">info@utc2,edu,vn</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}

{{--                <div class="col-md-4 pt-5 ">--}}
{{--                    <h2 class="h2 text-light border-bottom pb-3 border-light">--}}
{{--                        Danh mục--}}
{{--                    </h2>--}}
{{--                    <ul class="list-unstyled text-light footer-link-list">--}}
{{--                        @foreach ($categories as $key => $category)--}}
{{--                            @if ($key < 5)--}}
{{--                                <li><a class="text-decoration-none">{{ $category->name }}</a>--}}
{{--                                </li>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--                <div class="col-md-4 pt-5">--}}
{{--                    <h2 class="h2 text-light border-bottom pb-3 border-light">--}}
{{--                        Thương hiệu--}}
{{--                    </h2>--}}
{{--                    <ul class="list-unstyled text-light footer-link-list">--}}
{{--                        @foreach ($brands as $key => $brand)--}}
{{--                            @if ($key < 5)--}}
{{--                                <li><a class="text-decoration-none">{{ $brand->name }}</a>--}}
{{--                                </li>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </footer>--}}
{{--    <!-- End Footer -->--}}
{{--    <!-- Start Script -->--}}
{{--    <script src="{{ asset('assets/js/jquery-1.11.0.min.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/js/jquery-migrate-1.2.1.min.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/js/templatemo.js') }}"></script>--}}
{{--    <script src="{{ asset('assets/js/custom.js') }}"></script>--}}
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
{{--    <!-- End Script -->--}}

{{--    <script src="{{ asset('assets/js/slick.min.js') }}"></script>--}}
    <script>
        $('#carousel-related-product').slick({
            infinite: true,
            arrows: false,
            slidesToShow: 4,
            slidesToScroll: 3,
            dots: true,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                    },
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 3,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 3,
                    },
                },
            ],
        });
    </script>
    <script>
        var intoMoney = document.getElementById('total').value
        var total = Math.round((intoMoney / 23000) * 100) / 100
        total = parseFloat(total)

        paypal.Button.render({
            // Configure environment
            env: 'sandbox',
            client: {
                sandbox: 'AS_uK5RVtE8H5aiNaPx_HQD_FFax5tPA0_UnXnZddv7_xzq43lbjaRzzXY6xH2m1Ey8emi5mkowbvzxI',
                production: 'demo_production_client_id'
            },
            // Customize button (optional)

            locale: 'en_US',
            style: {
                size: 'medium',
                color: 'gold',
                shape: 'pill',
            },

            // Enable Pay Now checkout flow (optional)
            commit: true,

            // Set up a payment
            payment: function(data, actions) {
                return actions.payment.create({
                    transactions: [{
                        amount: {
                            total: total,
                            currency: 'USD'
                        }
                    }]
                });
            },
            // Execute the payment
            onAuthorize: function(data, actions) {
                var email = document.getElementById("email").value
                var name = document.getElementById("name").value
                var phone = document.getElementById("phone").value
                var address = document.getElementById("address").value

                if (email && name && phone && address) {
                    return actions.payment.execute().then(function() {
                        // Show a confirmation message to the buyer
                        document.getElementById("is_pay_cod").value = 0;
                        document.getElementById("create_order").submit();
                        window.alert('Thank you for your purchase!');
                    });
                } else {
                    window.alert('Bạn chưa nhập đủ thông tin');
                }
            }
        }, '#paypal-button');
    </script>

        <script
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdu4k2cYIHbds3Y4mTLHHIMURBWS4QiII&callback=initMap&libraries=&v=weekly" async></script>

<!-- AI CHATBOX START -->

<style>
    #ai-chat-button {
        position: fixed;
        right: 25px;
        bottom: 25px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #06A3DA;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 9999;
        font-size: 26px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.25);
    }

    #ai-chat-box {
        display: none;
        position: fixed;
        right: 25px;
        bottom: 100px;
        width: 360px;
        height: 480px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        z-index: 9999;
        overflow: hidden;
    }

    #ai-chat-header {
        background: #06A3DA;
        color: white;
        padding: 15px;
        font-weight: bold;
        font-size: 18px;
    }

    #ai-chat-content {
        height: 350px;
        overflow-y: auto;
        padding: 12px;
        background: #f7f7f7;
    }

    .ai-message {
        background: white;
        padding: 9px 12px;
        margin: 8px 0;
        border-radius: 10px;
        max-width: 85%;
    }

    .user-message {
        background: #06A3DA;
        color: white;
        padding: 9px 12px;
        margin: 8px 0 8px auto;
        border-radius: 10px;
        max-width: 85%;
    }

    #ai-chat-input-area {
        display: flex;
        padding: 10px;
        border-top: 1px solid #ddd;
        background: white;
    }

    #ai-message {
        flex: 1;
        padding: 9px;
        border: 1px solid #ccc;
        border-radius: 6px;
        outline: none;
    }

    #ai-send {
        margin-left: 6px;
        border: none;
        background: #06A3DA;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        cursor: pointer;
    }
    .ai-booking-button {
    display: inline-block;
    background: #007bff;
    color: white !important;
    padding: 10px 18px;
    border-radius: 20px;
    text-decoration: none !important;
    font-weight: 600;
    font-size: 14px;
    transition: 0.2s;
}

    .ai-booking-button:hover {
    opacity: 0.85;
    transform: translateY(-1px);
}
</style>


<div id="ai-chat-button">
    <i class="fas fa-comments"></i>
</div>


<div id="ai-chat-box">

    <div id="ai-chat-header">
        🤖 Trợ lý AI Nha Khoa NA
    </div>

    <div id="ai-chat-content">

        <div class="ai-message">
            Xin chào! Tôi là trợ lý AI của Nha Khoa NA.
            Bạn cần tư vấn gì về răng miệng?
        </div>

    </div>

    <div id="ai-chat-input-area">

        <input
            id="ai-message"
            type="text"
            placeholder="Nhập câu hỏi..."
        >

        <button id="ai-send">
            Gửi
        </button>

    </div>
</div>


<script>
    const chatButton = document.getElementById('ai-chat-button');
    const chatBox = document.getElementById('ai-chat-box');
    const sendButton = document.getElementById('ai-send');
    const input = document.getElementById('ai-message');
    const chatContent = document.getElementById('ai-chat-content');

    chatButton.addEventListener('click', function () {
        if (chatBox.style.display === 'block') {
            chatBox.style.display = 'none';
        } else {
            chatBox.style.display = 'block';
        }
    });

    sendButton.addEventListener('click', sendAIMessage);

    input.addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            sendAIMessage();
        }
    });

    let chatConversationId =
    'CHAT-' + Date.now() + '-' + Math.random().toString(36).substring(2, 8);

    let bookingStep = 0;
    let bookingName = '';
    let bookingPhone = '';

    let bookingServiceId = null;
    let bookingServiceName = '';
    let bookingDoctorId = null;
    let bookingDoctorName = '';
    let bookingDate = '';
    let bookingTime = '';

    let purchaseStep = 0;
    let purchaseProductId = null;
    let purchaseProductName = '';
    let purchaseQuantity = 0;
    let purchaseName = '';
    let purchasePhone = '';
    let purchaseEmail = '';
    let purchaseAddress = '';
    let cancelOrderStep = 0;
    let cancelOrderCode = '';

    function sendAIMessage() {

    const message = input.value.trim();

    if (message === '') {
        return;
    }

    // Hiện tin nhắn của khách
    chatContent.innerHTML += `
        <div class="user-message">
            ${escapeHtml(message)}
        </div>
    `;

    input.value = '';
    chatContent.scrollTop = chatContent.scrollHeight;

// =====================================================
// HỦY ĐƠN - NHẬP MÃ ĐƠN HÀNG
// =====================================================

if (cancelOrderStep === 1) {

    cancelOrderCode = message.trim();

    saveChatHistory(
    cancelOrderCode,
    'Đang kiểm tra mã đơn hàng ' + cancelOrderCode + '.'
);

    chatContent.innerHTML += `
        <div class="ai-message" id="cancel-check-loading">
            ⏳ Đang kiểm tra mã đơn hàng...
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;

    fetch('{{ route("ai_chat_check_order") }}', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            code_invoice: cancelOrderCode
        })

    })

    .then(response => response.json())

    .then(data => {

        const loading =
            document.getElementById('cancel-check-loading');

        if (loading) {
            loading.remove();
        }

        if (data.status === true) {

    cancelOrderStep = 2;

    saveChatHistory(
    'Kiểm tra đơn hàng ' + data.code_invoice,
    'Đã tìm thấy đơn hàng ' + data.code_invoice
    + '. Trạng thái hiện tại: ' + data.status_ship
    + '. Bạn có chắc chắn muốn hủy đơn hàng này không?'
);

    chatContent.innerHTML += `
        <div class="ai-message">
            ✅ Đã tìm thấy đơn hàng
            <strong>${escapeHtml(data.code_invoice)}</strong>.<br><br>

            Trạng thái hiện tại:
            <strong>${escapeHtml(data.status_ship)}</strong><br><br>

            Bạn có chắc chắn muốn hủy đơn hàng này không?<br><br>

            <button type="button"
                    onclick="confirmCancelOrder()"
                    class="ai-booking-button">
                ❌ Xác nhận hủy đơn
            </button>
        </div>
    `;

        } else {

            // Cho phép nhập lại mã
            cancelOrderStep = 1;

            chatContent.innerHTML += `
                <div class="ai-message">
                    ❌ ${escapeHtml(data.message)}<br><br>
                    Bạn vui lòng nhập lại
                    <strong>mã đơn hàng</strong>.
                </div>
            `;
        }

        chatContent.scrollTop = chatContent.scrollHeight;
    })

    .catch(error => {

        const loading =
            document.getElementById('cancel-check-loading');

        if (loading) {
            loading.remove();
        }

        cancelOrderStep = 1;

        chatContent.innerHTML += `
            <div class="ai-message">
                ❌ Không thể kiểm tra đơn hàng.
                Vui lòng thử lại.
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;
    });

    return;
}

    // =====================================================
// MUA HÀNG - NHẬP SỐ LƯỢNG
// =====================================================

if (purchaseStep === 1) {

    const quantity = parseInt(message);

    if (isNaN(quantity) || quantity < 1) {

        chatContent.innerHTML += `
            <div class="ai-message">
                Số lượng chưa hợp lệ.<br>
                Bạn vui lòng nhập số lượng, ví dụ:
                <strong>1</strong> hoặc <strong>2</strong>.
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;
        return;
    }

    purchaseQuantity = quantity;
    purchaseStep = 2;

    saveChatHistory(
    message,
    'Đã chọn ' + purchaseQuantity + ' sản phẩm ' + purchaseProductName
    + '. Vui lòng nhập họ và tên người nhận.'
);

    chatContent.innerHTML += `
        <div class="ai-message">
            Đã chọn <strong>${purchaseQuantity}</strong>
            sản phẩm <strong>${escapeHtml(purchaseProductName)}</strong>.<br><br>

            Bạn vui lòng cho biết
            <strong>họ và tên người nhận</strong>.
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
    return;
}

// =====================================================
// MUA HÀNG - NHẬP TÊN NGƯỜI NHẬN
// =====================================================

if (purchaseStep === 2) {

    purchaseName = message;
    purchaseStep = 3;

    saveChatHistory(
    purchaseName,
    'Cảm ơn ' + purchaseName
    + '. Vui lòng nhập số điện thoại người nhận.'
);

    chatContent.innerHTML += `
        <div class="ai-message">
            Cảm ơn <strong>${escapeHtml(purchaseName)}</strong>.<br><br>
            Bạn vui lòng nhập
            <strong>số điện thoại người nhận</strong>.
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
    return;
}

// =====================================================
// MUA HÀNG - BƯỚC 3: NHẬP SỐ ĐIỆN THOẠI
// =====================================================

if (purchaseStep === 3) {

    const phone = message.replace(/\D/g, '');

    if (!/^0\d{9}$/.test(phone)) {

        chatContent.innerHTML += `
            <div class="ai-message">
                Số điện thoại chưa hợp lệ.<br>
                Vui lòng nhập số điện thoại gồm
                <strong>10 chữ số</strong>.<br>
                Ví dụ: <strong>0901234567</strong>.
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;
        return;
    }

    purchasePhone = phone;
    purchaseStep = 4;

    saveChatHistory(
    purchasePhone,
    'Đã ghi nhận số điện thoại. Vui lòng nhập email nhận thông tin đơn hàng.'
);

    chatContent.innerHTML += `
        <div class="ai-message">
            Đã ghi nhận số điện thoại
            <strong>${escapeHtml(purchasePhone)}</strong>.<br><br>

            Bạn vui lòng nhập
            <strong>email nhận thông tin đơn hàng</strong>.
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
    return;
}

// =====================================================
// MUA HÀNG -  NHẬP EMAIL
// =====================================================

if (purchaseStep === 4) {

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(message)) {

        chatContent.innerHTML += `
            <div class="ai-message">
                Email chưa hợp lệ.<br>
                Bạn vui lòng nhập lại.<br>
                Ví dụ: <strong>abc@gmail.com</strong>
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;
        return;
    }

    purchaseEmail = message;
    purchaseStep = 5;

    saveChatHistory(
    purchaseEmail,
    'Đã ghi nhận email. Vui lòng nhập địa chỉ nhận hàng.'
);

    chatContent.innerHTML += `
        <div class="ai-message">
            Đã ghi nhận email
            <strong>${escapeHtml(purchaseEmail)}</strong>.<br><br>

            Bạn vui lòng nhập
            <strong>địa chỉ nhận hàng</strong>.
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
    return;
}

// =====================================================
// MUA HÀNG - NHẬP ĐỊA CHỈ + XÁC NHẬN
// =====================================================

if (purchaseStep === 5) {

    purchaseAddress = message;
    purchaseStep = 6;

    saveChatHistory(
    purchaseAddress,
    'Đã ghi nhận địa chỉ nhận hàng. Vui lòng kiểm tra thông tin và xác nhận đặt hàng.'
);

    chatContent.innerHTML += `
        <div class="ai-message">

            <strong>📦 THÔNG TIN ĐƠN HÀNG</strong><br><br>

            Sản phẩm:
            <strong>${escapeHtml(purchaseProductName)}</strong><br>

            Số lượng:
            <strong>${purchaseQuantity}</strong><br><br>

            Người nhận:
            <strong>${escapeHtml(purchaseName)}</strong><br>

            SĐT:
            <strong>${escapeHtml(purchasePhone)}</strong><br>

            Email:
            <strong>${escapeHtml(purchaseEmail)}</strong><br>

            Địa chỉ:
            <strong>${escapeHtml(purchaseAddress)}</strong><br><br>

            Thanh toán:
            <strong>COD - Thanh toán khi nhận hàng</strong><br><br>

            <button type="button"
                    onclick="confirmChatOrder()"
                    class="ai-booking-button">
                ✅ Xác nhận đặt hàng
            </button>

        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
    return;
}

// Đang chờ khách bấm nút xác nhận
if (purchaseStep === 6) {

    chatContent.innerHTML += `
        <div class="ai-message">
            Bạn vui lòng nhấn
            <strong>✅ Xác nhận đặt hàng</strong>
            ở phía trên để hoàn tất đơn hàng.
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
    return;
}
    // =========================================
    // BƯỚC 1: ĐANG CHỜ KHÁCH NHẬP HỌ TÊN
    // =========================================
    if (bookingStep === 1) {

        bookingName = message;

        bookingStep = 2;

        saveChatHistory(
        bookingName,
        'Cảm ơn ' + bookingName + '. Bạn vui lòng cho biết số điện thoại để Nha Khoa hỗ trợ đặt lịch.'
    );

        chatContent.innerHTML += `
            <div class="ai-message">
                Cảm ơn <strong>${escapeHtml(bookingName)}</strong>.<br>
                Bạn vui lòng cho biết <strong>số điện thoại</strong> để Nha Khoa hỗ trợ đặt lịch.
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;
        return;
    }


    // =========================================
    // BƯỚC 2: ĐANG CHỜ KHÁCH NHẬP SỐ ĐIỆN THOẠI
    // =========================================
    if (bookingStep === 2) {

    // Xóa khoảng trắng, dấu chấm, dấu gạch...
    const phone = message.replace(/\D/g, '');

    // Kiểm tra SĐT Việt Nam
    if (!/^0\d{9}$/.test(phone)) {

        chatContent.innerHTML += `
            <div class="ai-message">
                Số điện thoại chưa hợp lệ.<br>
                Bạn vui lòng nhập số điện thoại gồm
                <strong>10 chữ số</strong>,
                ví dụ: 0901234567.
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;
        return;
    }

    bookingPhone = phone;

    // Chờ khách bấm xác nhận đặt lịch
    bookingStep = 3;

    saveChatHistory(
    bookingPhone,
    'Đã ghi nhận số điện thoại. Vui lòng kiểm tra thông tin và xác nhận đặt lịch.'
);

    chatContent.innerHTML += `
        <div class="ai-message">

            <strong>🦷 THÔNG TIN LỊCH KHÁM</strong><br><br>

            Người khám:
            <strong>${escapeHtml(bookingName)}</strong><br>

            SĐT:
            <strong>${escapeHtml(bookingPhone)}</strong><br><br>

            Dịch vụ:
            <strong>${escapeHtml(bookingServiceName)}</strong><br>

            Bác sĩ:
            <strong>${escapeHtml(bookingDoctorName)}</strong><br>

            Ngày khám:
            <strong>${escapeHtml(bookingDate)}</strong><br>

            Giờ khám:
            <strong>${escapeHtml(bookingTime)}</strong><br><br>

            <button type="button"
                    onclick="confirmChatReservation()"
                    class="ai-booking-button">
                ✅ Xác nhận đặt lịch
            </button>

        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
    return;
}

if (bookingStep === 3) {

    chatContent.innerHTML += `
        <div class="ai-message">
            Bạn vui lòng nhấn
            <strong>✅ Xác nhận đặt lịch</strong>
            ở phía trên để hoàn tất.
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
    return;
}


    // =========================================
    // CÂU HỎI BÌNH THƯỜNG -> GỬI BACKEND
    // =========================================

    chatContent.innerHTML += `
        <div class="ai-message" id="ai-loading">
            🤖 Nha Khoa đang trả lời...
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;


    fetch('{{ route("ai_chat") }}', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            message: message,
            conversation_id: chatConversationId
        })
    })

    
    .then(response => response.json())

    .then(data => {

        const loading = document.getElementById('ai-loading');

        if (loading) {
            loading.remove();
        }

 if (data.check_reservation === true) {

    chatContent.innerHTML += `
        <div class="ai-message" id="check-reservation-loading">
            ⏳ Đang kiểm tra lịch khám của bạn...
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;

    fetch('{{ route("ai_chat_check_reservation") }}', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({})
    })

    .then(response => response.json())

    .then(res => {

        const loading =
            document.getElementById('check-reservation-loading');

        if (loading) {
            loading.remove();
        }

        if (res.status === true) {
            let historyAnswer = 'Lịch khám sắp tới của bạn:\n';

            res.reservations.forEach(reservation => {

        historyAnswer +=
        'Dịch vụ: ' + (reservation.service ? reservation.service.name : '') + '\n'
        + 'Bác sĩ: ' + (reservation.doctor ? reservation.doctor.name : '') + '\n'
        + 'Ngày: ' + reservation.date + '\n'
        + 'Giờ: ' + reservation.time + '\n'
        + 'Trạng thái: '
        + (reservation.status == 0 ? 'Chờ xác nhận' : 'Đã xác nhận')
        + '\n\n';
});

           saveChatHistory(
           'Xem lịch khám của tôi',
            historyAnswer
            );

            let reservationHtml = `
                <div class="ai-message">
                    📅 <strong>Lịch khám sắp tới của bạn:</strong><br><br>
            `;

            res.reservations.forEach(reservation => {

                reservationHtml += `
                    Dịch vụ:
                    <strong>${escapeHtml(reservation.service ? reservation.service.name : '')}</strong><br>

                    Bác sĩ:
                    <strong>${escapeHtml(reservation.doctor ? reservation.doctor.name : '')}</strong><br>

                    Ngày:
                    <strong>${escapeHtml(reservation.date)}</strong><br>

                    Giờ:
                    <strong>${escapeHtml(reservation.time)}</strong><br>

                    Trạng thái:
                    <strong>
                        ${reservation.status == 0
                            ? 'Chờ xác nhận'
                            : 'Đã xác nhận'}
                    </strong><br><br>
                `;
            });

            reservationHtml += `</div>`;

            chatContent.innerHTML += reservationHtml;

        } else {
            saveChatHistory(
           'Xem lịch khám của tôi',
            res.message
        );

            chatContent.innerHTML += `
                <div class="ai-message">
                    ${escapeHtml(res.message)}
                </div>
            `;
        }

        chatContent.scrollTop = chatContent.scrollHeight;
    });

    return;
}       


if (data.cancel_reservation === true) {

    chatContent.innerHTML += `
        <div class="ai-message" id="cancel-reservation-loading">
            ⏳ Đang kiểm tra lịch khám của bạn...
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;

    fetch('{{ route("ai_chat_check_reservation") }}', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({})
    })

    .then(response => response.json())

    .then(res => {

        const loading =
            document.getElementById('cancel-reservation-loading');

        if (loading) {
            loading.remove();
        }

        if (res.status === true) {

            let reservationHtml = `
                <div class="ai-message">
                    📅 <strong>Các lịch khám có thể hủy:</strong><br><br>
            `;

            res.reservations.forEach(reservation => {

                reservationHtml += `
                    Dịch vụ:
                    <strong>${escapeHtml(reservation.service ? reservation.service.name : '')}</strong><br>

                    Bác sĩ:
                    <strong>${escapeHtml(reservation.doctor ? reservation.doctor.name : '')}</strong><br>

                    Ngày:
                    <strong>${escapeHtml(reservation.date)}</strong><br>

                    Giờ:
                    <strong>${escapeHtml(reservation.time)}</strong><br><br>

                    <button type="button"
                            class="ai-booking-button"
                            style="margin-bottom: 12px;"
                            onclick="selectCancelReservation(${reservation.id})">
                        ❌ Hủy lịch này
                    </button>

                    <br>
                `;
            });

            reservationHtml += `</div>`;

            chatContent.innerHTML += reservationHtml;

        } else {

            chatContent.innerHTML += `
                <div class="ai-message">
                    ${escapeHtml(res.message)}
                </div>
            `;
        }

        chatContent.scrollTop = chatContent.scrollHeight;
    });

    return;
}

if (data.cancel_order === true) {

    cancelOrderStep = 1;

    chatContent.innerHTML += `
        <div class="ai-message">
            ${formatAIText(data.answer)}
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
    return;
}

        // =========================================
        // NẾU BACKEND NHẬN RA KHÁCH MUỐN ĐẶT LỊCH
        // =========================================
        if (data.booking === true) {

    chatContent.innerHTML += `
        <div class="ai-message" id="booking-service-loading">
            ⏳ Đang lấy danh sách dịch vụ...
        </div>
    `;

    fetch('{{ route("ai_chat_create_reservation") }}', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            conversation_id: chatConversationId
        })
  
})

    .then(response => response.json())

    .then(res => {

        const loading =
            document.getElementById('booking-service-loading');

        if (loading) {
            loading.remove();
        }

        if (res.status === true) {

            let serviceHtml = `
                <div class="ai-message">
                    Dạ được ạ! 😊<br><br>
                    Bạn vui lòng chọn
                    <strong>dịch vụ muốn khám</strong>:<br><br>
            `;

            res.services.forEach(service => {

                serviceHtml += `
                    <button type="button"
                            class="ai-booking-button"
                            style="margin: 4px;"
                            onclick="selectBookingService(${service.id}, '${escapeHtml(service.name)}')">
                        ${escapeHtml(service.name)}
                    </button>
                `;
            });

            serviceHtml += `</div>`;

            chatContent.innerHTML += serviceHtml;

        } else {

            chatContent.innerHTML += `
                <div class="ai-message">
                    ${escapeHtml(res.message)}
                </div>
            `;
        }

        chatContent.scrollTop = chatContent.scrollHeight;
    });

    return;
}
        // =========================================
// NẾU KHÁCH MUỐN MUA SẢN PHẨM
// =========================================
if (data.purchase === true) {

    purchaseProductId = data.product_id;
    purchaseProductName = data.product_name;
    purchaseStep = 1;

    chatContent.innerHTML += `
        <div class="ai-message">
            ${formatAIText(data.answer)}
            <br><br>
            Bạn muốn mua
            <strong>${escapeHtml(purchaseProductName)}</strong>
            số lượng bao nhiêu?
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;

    return;
}

        // =========================================
        // CÂU TRẢ LỜI AI BÌNH THƯỜNG
        // =========================================
        chatContent.innerHTML += `
            <div class="ai-message">
                ${formatAIText(data.answer)}
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;

    })

    .catch(error => {

        const loading = document.getElementById('ai-loading');

        if (loading) {
            loading.remove();
        }

        chatContent.innerHTML += `
            <div class="ai-message">
                Trợ lý AI tạm thời không thể kết nối.
                Vui lòng thử lại sau.
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;

    });
}
function saveChatHistory(question, answer) {

    fetch('{{ route("ai_chat_save_history") }}', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            conversation_id: chatConversationId,
            question: question,
            answer: answer
        })
    })
    .catch(error => {
        console.log('Không thể lưu lịch sử Chatbox.');
    });
}
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    function formatAIText(text) {
    let safe = escapeHtml(text);

    safe = safe.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    safe = safe.replace(/\n/g, '<br>');

    return safe;
}
// =====================================================
// XÁC NHẬN VÀ TẠO ĐƠN HÀNG
// =====================================================

function confirmChatOrder() {

    // Không cho bấm xác nhận nhiều lần
    if (purchaseStep !== 6) {
        return;
    }

    chatContent.innerHTML += `
        <div class="ai-message" id="order-loading">
            ⏳ Đang tạo đơn hàng...
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;

    fetch('{{ route("ai_chat_create_order") }}', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            product_id: purchaseProductId,
            quantity: purchaseQuantity,
            name: purchaseName,
            phone: purchasePhone,
            email: purchaseEmail,
            address: purchaseAddress
        })
    })

    .then(response => response.json())

    .then(data => {

        const loading = document.getElementById('order-loading');

        if (loading) {
            loading.remove();
        }

        if (data.status === true) {

        saveChatHistory(
        'Xác nhận đặt hàng',
        'Đặt hàng thành công. Mã đơn hàng: ' + data.code_invoice
         + ', Sản phẩm: ' + data.product_name
         + ', Số lượng: ' + data.quantity
         + ', Tổng thanh toán: ' + Number(data.total_money).toLocaleString('vi-VN') + 'đ'
);

            chatContent.innerHTML += `
                <div class="ai-message">

                    ✅ <strong>Đặt hàng thành công!</strong><br><br>

                    Mã đơn hàng:
                    <strong>${escapeHtml(data.code_invoice)}</strong><br>

                    Sản phẩm:
                    <strong>${escapeHtml(data.product_name)}</strong><br>

                    Số lượng:
                    <strong>${data.quantity}</strong><br>

                    Tiền sản phẩm:
                    <strong>
                        ${Number(data.product_money).toLocaleString('vi-VN')}đ
                    </strong><br>

                    Phí vận chuyển:
                    <strong>
                        ${Number(data.shipping_fee).toLocaleString('vi-VN')}đ
                    </strong><br>

                    Tổng thanh toán:
                    <strong>
                        ${Number(data.total_money).toLocaleString('vi-VN')}đ
                    </strong><br><br>

                    📦 Đơn hàng đã được lưu vào hệ thống.

                </div>
            `;

            // Kết thúc và reset luồng mua hàng
            purchaseStep = 0;
            purchaseProductId = null;
            purchaseProductName = '';
            purchaseQuantity = 0;
            purchaseName = '';
            purchasePhone = '';
            purchaseEmail = '';
            purchaseAddress = '';

        } else {

            chatContent.innerHTML += `
                <div class="ai-message">
                    ❌ ${escapeHtml(data.message)}
                </div>
            `;
        }

        chatContent.scrollTop = chatContent.scrollHeight;
    })

    .catch(error => {

        const loading = document.getElementById('order-loading');

        if (loading) {
            loading.remove();
        }

        chatContent.innerHTML += `
            <div class="ai-message">
                ❌ Không thể tạo đơn hàng.
                Vui lòng thử lại.
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;
    });
}

// =====================================================
// XÁC NHẬN HỦY ĐƠN HÀNG
// =====================================================

function confirmCancelOrder() {

    if (cancelOrderStep !== 2) {
        return;
    }

    chatContent.innerHTML += `
        <div class="ai-message" id="cancel-order-loading">
            ⏳ Đang kiểm tra yêu cầu hủy đơn...
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;

    fetch('{{ route("ai_chat_cancel_order") }}', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            code_invoice: cancelOrderCode
        })

    })

    .then(response => response.json())

    .then(data => {

        const loading =
            document.getElementById('cancel-order-loading');

        if (loading) {
            loading.remove();
        }

        if (data.status === true) {

        saveChatHistory(
        'Xác nhận hủy đơn hàng ' + cancelOrderCode,
        data.message
        );

            chatContent.innerHTML += `
                <div class="ai-message">
                    ✅ ${escapeHtml(data.message)}
                </div>
            `;

        } else {

            saveChatHistory(
            'Xác nhận hủy đơn hàng ' + cancelOrderCode,
            'Hủy đơn hàng không thành công: ' + data.message
        );

            chatContent.innerHTML += `
                <div class="ai-message">
                    ❌ ${escapeHtml(data.message)}
                </div>
            `;
        }

        chatContent.scrollTop = chatContent.scrollHeight;
    })

    .catch(error => {

        const loading =
            document.getElementById('cancel-order-loading');

        if (loading) {
            loading.remove();
        }

        chatContent.innerHTML += `
            <div class="ai-message">
                ❌ Không thể xử lý yêu cầu hủy đơn.
                Vui lòng thử lại.
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;
    });
}

function selectBookingService(id, name) {

    bookingServiceId = id;
    bookingServiceName = name;
    
    saveChatHistory(
    name,
    'Bạn đã chọn dịch vụ ' + name + '. Vui lòng chọn bác sĩ.'
);
    chatContent.innerHTML += `
        <div class="user-message">
            ${escapeHtml(name)}
        </div>

        <div class="ai-message" id="booking-doctor-loading">
            ⏳ Đang lấy danh sách bác sĩ...
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;

    fetch('{{ route("ai_chat_get_doctors") }}', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            service_id: bookingServiceId
        })
    })

    .then(response => response.json())

    .then(res => {

        const loading =
            document.getElementById('booking-doctor-loading');

        if (loading) {
            loading.remove();
        }

        if (res.status === true) {

            let doctorHtml = `
                <div class="ai-message">
                    Bạn đã chọn dịch vụ
                    <strong>${escapeHtml(bookingServiceName)}</strong>.<br><br>

                    Vui lòng chọn <strong>bác sĩ</strong>:<br><br>
            `;

            res.doctors.forEach(doctor => {

                doctorHtml += `
                    <button type="button"
                            class="ai-booking-button"
                            style="margin: 4px;"
                            onclick="selectBookingDoctor(${doctor.id}, '${escapeHtml(doctor.name)}')">
                        👨‍⚕️ ${escapeHtml(doctor.name)}
                    </button>
                `;
            });

            doctorHtml += `</div>`;

            chatContent.innerHTML += doctorHtml;

        } else {

            chatContent.innerHTML += `
                <div class="ai-message">
                    ❌ ${escapeHtml(res.message)}
                </div>
            `;
        }

        chatContent.scrollTop = chatContent.scrollHeight;
    });
}

function selectBookingDoctor(id, name) {

    bookingDoctorId = id;
    bookingDoctorName = name;

    saveChatHistory(
    name,
    'Bạn đã chọn bác sĩ ' + name + '. Vui lòng chọn ngày khám.'
);

    const today = new Date();
    const minDate = today.toISOString().split('T')[0];

    chatContent.innerHTML += `
        <div class="user-message">
            ${escapeHtml(name)}
        </div>

        <div class="ai-message">
            Bạn đã chọn bác sĩ
            <strong>${escapeHtml(name)}</strong>.<br><br>

            📅 Vui lòng chọn ngày khám:<br><br>

            <input type="date"
                   id="booking-date-input"
                   min="${minDate}"
                   style="padding: 7px; margin-bottom: 8px;">

            <br>

            <button type="button"
                    class="ai-booking-button"
                    onclick="confirmBookingDate()">
                Tiếp tục
            </button>
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
}

function confirmBookingDate() {

    const dateInput = document.getElementById('booking-date-input');

    if (!dateInput || !dateInput.value) {
        alert('Vui lòng chọn ngày khám.');
        return;
    }

    bookingDate = dateInput.value;
    saveChatHistory(
    bookingDate,
    'Bạn đã chọn ngày khám ' + bookingDate + '. Đang kiểm tra giờ trống của bác sĩ.'
);

    chatContent.innerHTML += `
        <div class="user-message">
            📅 ${escapeHtml(bookingDate)}
        </div>

        <div class="ai-message" id="booking-time-loading">
            ⏳ Đang kiểm tra giờ trống của bác sĩ...
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;

    fetch('{{ route("ai_chat_get_free_times") }}', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            doctor_id: bookingDoctorId,
            service_id: bookingServiceId,
            date: bookingDate
        })
    })

    .then(response => response.json())

    .then(res => {

        const loading =
            document.getElementById('booking-time-loading');

        if (loading) {
            loading.remove();
        }

        if (res.status === true) {

            if (res.free_times.length === 0) {

                chatContent.innerHTML += `
                    <div class="ai-message">
                        😥 Bác sĩ không còn giờ trống trong ngày này.<br><br>
                        Vui lòng chọn ngày khác.
                    </div>
                `;

                chatContent.scrollTop = chatContent.scrollHeight;
                return;
            }

            let timeHtml = `
                <div class="ai-message">
                    ⏰ Các giờ còn trống:<br><br>
            `;

            res.free_times.forEach(time => {

                timeHtml += `
                    <button type="button"
                            class="ai-booking-button"
                            style="margin: 4px;"
                            onclick="selectBookingTime('${escapeHtml(time)}')">
                        ${escapeHtml(time)}
                    </button>
                `;
            });

            timeHtml += `</div>`;

            chatContent.innerHTML += timeHtml;

        } else {

            chatContent.innerHTML += `
                <div class="ai-message">
                    ❌ Không thể lấy giờ khám.
                </div>
            `;
        }

        chatContent.scrollTop = chatContent.scrollHeight;
    });
}

function selectBookingTime(time) {

    bookingTime = time;
    bookingStep = 1;
    saveChatHistory(
    time,
    'Bạn đã chọn giờ khám ' + time + '. Vui lòng nhập họ và tên người khám.'
);

    chatContent.innerHTML += `
        <div class="user-message">
            ⏰ ${escapeHtml(time)}
        </div>

        <div class="ai-message">
            Bạn đã chọn giờ khám:
            <strong>${escapeHtml(time)}</strong>.<br><br>

            Dịch vụ: <strong>${escapeHtml(bookingServiceName)}</strong><br>
            Bác sĩ: <strong>${escapeHtml(bookingDoctorName)}</strong><br>
            Ngày: <strong>${escapeHtml(bookingDate)}</strong><br>
            Giờ: <strong>${escapeHtml(bookingTime)}</strong><br><br>

            👤 Vui lòng cho biết <strong>họ và tên</strong> của người khám.
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
}

function confirmChatReservation() {

    if (bookingStep !== 3) {
        return;
    }

    chatContent.innerHTML += `
        <div class="ai-message" id="booking-confirm-loading">
            ⏳ Đang tạo lịch khám...
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;

    fetch('{{ route("ai_chat_confirm_reservation") }}', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            service_id: bookingServiceId,
            doctor_id: bookingDoctorId,
            name: bookingName,
            phone: bookingPhone,
            date: bookingDate,
            time: bookingTime,
            message: 'Đặt lịch qua Chatbox AI'
        })
    })

    .then(response => response.json())

    .then(data => {

        const loading =
            document.getElementById('booking-confirm-loading');

        if (loading) {
            loading.remove();
        }

        if (data.status === true) {

        saveChatHistory(
        'Xác nhận đặt lịch',
        'Đặt lịch khám thành công. Dịch vụ: ' + bookingServiceName
        + ', Bác sĩ: ' + bookingDoctorName
        + ', Ngày: ' + bookingDate
        + ', Giờ: ' + bookingTime
        );

            chatContent.innerHTML += `
                <div class="ai-message">
                    ✅ <strong>Đặt lịch khám thành công!</strong><br><br>

                    Dịch vụ:
                    <strong>${escapeHtml(bookingServiceName)}</strong><br>

                    Bác sĩ:
                    <strong>${escapeHtml(bookingDoctorName)}</strong><br>

                    Ngày:
                    <strong>${escapeHtml(bookingDate)}</strong><br>

                    Giờ:
                    <strong>${escapeHtml(bookingTime)}</strong>
                </div>
            `;

            bookingStep = 0;

        } else {

            chatContent.innerHTML += `
                <div class="ai-message">
                    ❌ ${escapeHtml(data.message)}
                </div>
            `;
        }

        chatContent.scrollTop = chatContent.scrollHeight;
    })

    .catch(error => {

        const loading =
            document.getElementById('booking-confirm-loading');

        if (loading) {
            loading.remove();
        }

        chatContent.innerHTML += `
            <div class="ai-message">
                ❌ Không thể tạo lịch khám.
                Vui lòng thử lại.
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;
    });
}

function selectCancelReservation(id) {

    saveChatHistory(
    'Chọn lịch khám cần hủy',
    'Bạn có chắc chắn muốn hủy lịch khám này không?'
);

    chatContent.innerHTML += `
        <div class="ai-message">
            ⚠️ Bạn có chắc chắn muốn hủy lịch khám này không?<br><br>

            <button type="button"
                    class="ai-booking-button"
                    onclick="confirmCancelReservation(${id})">
                ✅ Xác nhận hủy lịch
            </button>
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;
}
function confirmCancelReservation(id) {

    chatContent.innerHTML += `
        <div class="ai-message" id="cancel-reservation-confirm-loading">
            ⏳ Đang xử lý yêu cầu hủy lịch...
        </div>
    `;

    chatContent.scrollTop = chatContent.scrollHeight;

    fetch('{{ route("ai_chat_cancel_reservation") }}', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            reservation_id: id
        })
    })

    .then(response => response.json())

    .then(data => {

        const loading =
            document.getElementById('cancel-reservation-confirm-loading');

        if (loading) {
            loading.remove();
        }

        if (data.status === true) {

        saveChatHistory(
        'Xác nhận hủy lịch',
        data.message
        );

            chatContent.innerHTML += `
                <div class="ai-message">
                    ✅ <strong>${escapeHtml(data.message)}</strong>
                </div>
            `;

        } else {

            saveChatHistory(
            'Xác nhận hủy lịch',
            'Hủy lịch không thành công: ' + data.message
        );

            chatContent.innerHTML += `
                <div class="ai-message">
                    ❌ ${escapeHtml(data.message)}
                </div>
            `;
        }

        chatContent.scrollTop = chatContent.scrollHeight;
    })

    .catch(error => {

        const loading =
            document.getElementById('cancel-reservation-confirm-loading');

        if (loading) {
            loading.remove();
        }

        chatContent.innerHTML += `
            <div class="ai-message">
                ❌ Không thể xử lý yêu cầu hủy lịch.
                Vui lòng thử lại.
            </div>
        `;

        chatContent.scrollTop = chatContent.scrollHeight;
    });
}
</script>

<!-- AI CHATBOX END -->
</body>

</html>
