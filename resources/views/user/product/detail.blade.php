@extends('user.layout')
@section('user_content')

    <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Chi tiết sản phẩm</h1>
            </div>
        </div>
    </div>

    <!-- Vendor End -->
    <!-- Open Content -->
    <section class="bg-light" style="background-color: white!important" id="article">
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-5 mt-5">
                    <div class="container">
                        <div class="mySlides">
                            <div class="numbertext"></div>
                            <img src=" @if (isset($product->image)){{ asset('' . $product->image) }} @else {{ asset('' . Config::get('app.image.default')) }} @endif" style="width:100%">
                        </div>
                        @if ($product->detailProduct->count() > 0)
                        @if (isset($product->detailProduct))
                        @foreach ($product->detailProduct as $key => $detail)
                            <div class="mySlides">
                                <div class="numbertext"></div>
                                <img src=" @if (isset($detail->image)){{ asset('' . $detail->image) }} @else {{ asset('' . Config::get('app.image.default')) }} @endif" style="width:100%">
                            </div>
                        @endforeach
                        @endif
                        @endif

                        <a class="prev" style="color:blue" onclick="plusSlides(-1)">❮</a>
                        <a class="next" style="margin-right: 24px; color:blue" onclick="plusSlides(1)">❯</a>
                        <div class="row">
                            <div class="column">
                                <img class="demo cursor" src="@if (isset($product->image)){{ asset('' . $product->image) }} @else {{ asset('' . Config::get('app.image.default')) }} @endif" style="width:100%" onclick="currentSlide(1)">
                            </div>
                            @if ($product->detailProduct->count() > 0)
                            @if (isset($product->detailProduct))
                            @foreach ($product->detailProduct as $k => $detail)
                                <div class="column">
                                    <img class="demo cursor" src="@if (isset($detail->image)){{ asset('' . $detail->image) }} @else {{ asset('' . Config::get('app.image.default')) }} @endif" style="width:100%" onclick="currentSlide({{$k+2}})">
                                </div>
                            @endforeach
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="h2">{{ $product->name }}</h1>
                            <ul class="list-unstyled d-flex justify-content-between">
                                <?php $now = Carbon\Carbon::now()->toDateTimeString(); ?>
                                @if ($now <= $product->end_promotion && $now >= $product->start_promotion)
                                    <li class="text-right text-dark"
                                        style="font-weight: bold!important; font-size: 25px!important;">
                                        {{ Lang::get('message.before_unit_money') . number_format($product->price_down, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                    </li>
                                    <li class="text-right text-dark"
                                        style="text-decoration: line-through; font-size: 25px!important;">
                                        {{ Lang::get('message.before_unit_money') . number_format($product->price, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                    </li>
                                @else
                                    <li class="text-right text-dark"
                                        style="font-weight: bold!important; font-size: 25px!important;">
                                        {{ Lang::get('message.before_unit_money') . number_format($product->price, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                    </li>
                                @endif
                            </ul>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Thương hiệu:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p><strong>{{ $product->brand->name }}</strong></p>
                                </li>
                            </ul>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Danh mục:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p><strong>{{ $product->category->name }}</strong></p>
                                </li>
                            </ul>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Còn lại:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p><strong>{{ $product->quantity }}</strong></p>
                                </li>
                            </ul>
                            <h6>Chi tiết:</h6>
                            <p>
                                {{ $product->short_description }}
                            </p>
                            @if ($product->active == 0 || $product->is_deleted == 1)
                                <p class="noti">{{ Lang::get('message.no_long_in_business') }}</p>
                            @elseif ($product->quantity <= 0)
                                <p class="noti">{{ Lang::get('message.out_of_stock') }}</p>
                            @else
                                <form action="{{ URL::to(route('add_cart', ['id' => $product->id])) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product-title" value="Activewear" />
                                    <div class="row">
                                        <div class="col-auto">
                                            <ul class="list-inline pb-3">
                                                <li class="list-inline-item text-right">
                                                    Số lượng
                                                    <input type="number" name="quanity" min="1" required
                                                        id="product_quantity" value="1" />
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row pb-3">
                                        <div class="col d-grid">
                                            <button type="button" style="background-color: blue"
                                                class="btn btn-success btn-lg" id="buy">
                                                Mua ngay
                                            </button>
                                        </div>
                                        <div class="col d-grid">
                                            <button type="submit" style="background-color: blue"
                                                class="btn btn-success btn-lg" name="submit" value="addtocard">
                                                Thêm vào giỏ hàng
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                            @if (session('message'))
                                <p class="noti">{{ session('message') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- Close Content -->
    <div class="m-5">
        <div class="container">
            @if (Auth::check() && Auth::user()->role->name === Config::get('auth.roles.user'))
                <form action="{{ URL::to(route('comment', ['id' => $product->id])) }}" method="POST">
                    @csrf
                    <div class="text-area d-flex">
                        <textarea class="form-control" cols="1" placeholder="Nhận xét" name="description" required></textarea>
                        <button type="submit" class="btn btn-primary">Gửi</button>
                    </div>
                </form>
            @endif
            <div class="py-2">
                <div class="list-comment">
                    @if (isset($comments))
                        @foreach ($comments as $key => $comment)
                            <div class="d-flex comment-item m-3 align-items-center">
                                <i class="fas fa-user px-2 ico-user"></i>
                                <div>
                                    @if (Auth::check() &&
                                            $comment->user->id === Auth::user()->id &&
                                            Auth::user()->role->name === Config::get('auth.roles.user'))
                                        <p class="m-0 txt-name">Bạn</p>
                                    @else
                                        <p class="m-0 txt-name">{{ $comment->user->name }}</p>
                                    @endif
                                    <p class="m-0">{{ $comment->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <form style="display: none" action="{{ URL::to(route('buy_product', ['id' => $product->id])) }}" method="post"
        id="buy_now">
        @csrf
        <input type="number" name="quanity" min="1" required id="product_qty" />
    </form>



    <style>
        body {
            font-family: Arial;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        img {
            vertical-align: middle;
        }

        /* Position the image container (needed to position the left and right arrows) */
        .container {
            position: relative;
        }

        /* Hide the images by default */
        .mySlides {
            display: none;
            border:black
        }

        /* Add a pointer when hovering over the thumbnail images */
        .cursor {
            cursor: pointer;
        }

        /* Next & previous buttons */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 40%;
            width: auto;
            padding: 16px;
            margin-top: -50px;
            color: white;
            font-weight: bold;
            font-size: 20px;
            border-radius: 0 3px 3px 0;
            user-select: none;
            -webkit-user-select: none;
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */
        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        /* Container for image text */
        .caption-container {
            text-align: center;
            background-color: #222;
            padding: 2px 16px;
            color: white;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Six columns side by side */
        .column {
            float: left;
            width: 16.66%;
        }

        /* Add a transparency effect for thumnbail images */
        .demo {
            opacity: 0.6;
        }

        .active,
        .demo:hover {
            opacity: 1;
        }
    </style>
    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("demo");
            let captionText = document.getElementById("caption");
            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
            captionText.innerHTML = dots[slideIndex - 1].alt;
        }
    </script>

    <script type="text/javascript">
        const button = document.getElementById('buy');
        const form = document.getElementById('buy_now');
        const qty = document.getElementById('product_qty');
        const pro_qty = document.getElementById('product_quantity');
        button.addEventListener('click', event => {
            qty.value = pro_qty.value
            form.submit()
        });
    </script>
@endsection
