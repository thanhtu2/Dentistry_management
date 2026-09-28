@extends('user.layout')
@section('user_content')

    <div class="container-fluid bg-primary py-1 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Sản phẩm</h1>
            </div>
        </div>
    </div>
    <!-- Blog Start -->
    <div class="container-fluid wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <!-- Blog list Start -->
                <div class="col-lg-8">
                    @if (session('message'))
                        <p>{{ session('message') }}</p>
                    @endif
                    <div class="row g-5">
                        @foreach ($products as $key => $product)
                            <div class="col-md-6 wow slideInUp" data-wow-delay="0.1s">
                                <a href="{{ URL::to(route('detail_product', ['id' => $product->id])) }}" class="blog-item bg-light rounded overflow-hidden">
                                    <div class="blog-img position-relative overflow-hidden">
                                        <img class="img-fluid" src="{{ asset('' . $product->image) }}" alt="">
                                    </div>
                                    <div class="p-4">
                                        <div class="d-flex mb-3">
                                            <small class="me-3"><i class="far fa-user text-primary me-2"></i>Còn {{$product->quantity}} sản phẩm</small>
                                        </div>
                                        <h4 class="mb-3">{{$product->name}}</h4>
                                        <p>{{$product->short_description}}</p>
                                        <h4 class="mb-3">
                                            <?php $now = Carbon\Carbon::now()->toDateTimeString() ?>
                                            @if ($now <= $product->end_promotion && $now >= $product->start_promotion)
                                            {{ Lang::get('message.before_unit_money') . number_format($product->price_down, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                            @else
                                            {{ Lang::get('message.before_unit_money') . number_format($product->price, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                            @endif
                                        </h4>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Blog list End -->

                <!-- Sidebar Start -->
                <div class="col-lg-4">
                    <!-- Search Form Start -->
                    <form class="mb-5 wow slideInUp" data-wow-delay="0.1s" method="GET" action="{{ URL::to(route('search_products')) }}">
                        <div class="input-group">
                            <select class="form-control p-3" style="background-color: white" name="category">
                                <option selected value="">Chọn danh mục</option>
                                @foreach ($categories as $key => $category)
                                    <option value="{{ $category->id }}" @if ($request->category == $category->id) selected @endif>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <select class="form-control p-3" style="background-color: white" name="brand">
                                <option selected value="">Chọn thương hiệu</option>
                                @foreach ($brands as $key => $brand)
                                    <option value="{{ $brand->id }}" @if ($request->brand == $brand->id) selected @endif>
                                        {{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <input type="text" name="product" class="form-control p-3" placeholder="Nhập tên sản phẩm">
                        </div>
                        <button type="submit" class="btn btn-primary px-4">Tìm kiếm</button>
                    </form>
                        <h4>Các sản phẩm có thể bạn quan tâm</h4>
                       <div class="col-md-6 wow slideInUp" data-wow-delay="0.1s" style="width: 100%; padding-top: 30px">
                            @foreach ($productsRelate as $key => $product)
                            <a href="{{ URL::to(route('detail_product', ['id' => $product->id])) }}" class="blog-item bg-light rounded overflow-hidden row" style="margin-top: 30px; background: transparent!important;">
                                <div class="blog-img position-relative overflow-hidden d-flex col-5"> <!-- Thêm class d-flex và justify-content-center, align-items-center -->
                                    <img class="img-fluid" src="{{ asset('' . $product->image) }}" alt="" style="max-width: 100%;"> <!-- Thêm style max-width để hình ảnh không quá to -->
                                </div>
                                <div class="col-7">
                                    <h4 class="mb-3">{{$product->name}}</h4>
                                    <h4 class="mb-3">
                                        <?php $now = Carbon\Carbon::now()->toDateTimeString() ?>
                                        @if ($now <= $product->end_promotion && $now >= $product->start_promotion)
                                        {{ Lang::get('message.before_unit_money') . number_format($product->price_down, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                        @else
                                        {{ Lang::get('message.before_unit_money') . number_format($product->price, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                        @endif
                                    </h4>
                                </div>
                            </a>
                            @endforeach
                       </div>
                </div>
            </div>
        </div>
    </a>
</div>

                </div>
                <!-- Sidebar End -->
            </div>
        </div>
    </div>
    <!-- Blog End -->


{{--    <!-- Start Content -->--}}
{{--    <div class="container py-5" id="shop">--}}
{{--        <div class="row">--}}
{{--            <div class="col-lg-3">--}}
{{--                <h1 class="h2 pb-4">Lọc theo</h1>--}}
{{--                <form method="GET" action="{{ URL::to(route('search_products')) }}">--}}
{{--                    <input type="text" class="form-control" placeholder="Tìm kiếm" name="product"--}}
{{--                        @if ($request->product) value = "{{ $request->product }}" @endif>--}}
{{--                    <select class="filter-select py-2 my-2" name="category">--}}
{{--                        <option selected value="">Chọn danh mục</option>--}}
{{--                        @foreach ($categories as $key => $category)--}}
{{--                            <option value="{{ $category->id }}" @if ($request->category == $category->id) selected @endif>--}}
{{--                                {{ $category->name }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    <select class="filter-select py-2 my-2" name="brand">--}}
{{--                        <option selected value="">Chọn thương hiệu</option>--}}
{{--                        @foreach ($brands as $key => $brand)--}}
{{--                            <option value="{{ $brand->id }}" @if ($request->brand == $brand->id) selected @endif>--}}
{{--                                {{ $brand->name }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>--}}
{{--                </form>--}}
{{--            </div>--}}

{{--            <div class="col-lg-9">--}}
{{--                @if (session('message'))--}}
{{--                    <p class="noti">{{ session('message') }}</p>--}}
{{--                @endif--}}
{{--                <div class="row">--}}
{{--                    @foreach ($products as $key => $product)--}}
{{--                        <div class="col-md-4 pb-3">--}}
{{--                            <div class="card mb-4 product-wap rounded-0">--}}
{{--                                <div class="card rounded-0">--}}
{{--                                    <div class="shop-image">--}}
{{--                                        <img class="card-img rounded-0 img-fluid"--}}
{{--                                            src="{{ asset('' . $product->image) }}" />--}}
{{--                                    </div>--}}
{{--                                    <div--}}
{{--                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">--}}
{{--                                        <ul class="list-unstyled">--}}
{{--                                            <li>--}}
{{--                                                <a class="btn btn-success text-white mt-2"--}}
{{--                                                    href="{{ URL::to(route('detail_product', ['id' => $product->id])) }}"><i--}}
{{--                                                        class="far fa-eye"></i></a>--}}
{{--                                            </li>--}}
{{--                                            <li>--}}
{{--                                                <form action="{{ URL::to(route('add_cart', ['id' => $product->id])) }}"--}}
{{--                                                    method="POST">--}}
{{--                                                    @csrf--}}
{{--                                                    <input type="hidden" name="quanity" id="product_quantity" value="1" />--}}
{{--                                                    <button class="btn btn-success text-white mt-2" type="submit"><i--}}
{{--                                                            class="fas fa-cart-plus"></i></button>--}}
{{--                                                </form>--}}
{{--                                            </li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card-body">--}}
{{--                                    <p class="text-center" style="height: 54px;">--}}
{{--                                        <a href="{{ URL::to(route('detail_product', ['id' => $product->id])) }}" style="font-weight: bold!important"--}}
{{--                                            class="h3 text-decoration-none">{{ $product->name }}--}}
{{--                                        </a>--}}
{{--                                    </p>--}}
{{--                                    <ul class="list-unstyled d-flex justify-content-between">--}}
{{--                                        @if ($now <= $product->end_promotion && $now >= $product->start_promotion)--}}
{{--                                            <li class="text-right text-dark" style="font-weight: bold!important">--}}
{{--                                                {{ Lang::get('message.before_unit_money') . number_format($product->price_down, 0, ',', '.') . Lang::get('message.after_unit_money') }}--}}
{{--                                            </li>--}}
{{--                                            <li class="text-right text-dark" style="text-decoration: line-through">--}}
{{--                                                {{ Lang::get('message.before_unit_money') . number_format($product->price, 0, ',', '.') . Lang::get('message.after_unit_money') }}--}}
{{--                                            </li>--}}
{{--                                        @else--}}
{{--                                            <li class="text-right text-dark" style="font-weight: bold!important">--}}
{{--                                                {{ Lang::get('message.before_unit_money') . number_format($product->price, 0, ',', '.') . Lang::get('message.after_unit_money') }}--}}
{{--                                            </li>--}}
{{--                                        @endif--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- End Content -->--}}

{{--    <section class="py-5" style="margin-top:24px">--}}
{{--        <div class="container my-4">--}}
{{--            <div class="row text-center py-3">--}}
{{--                <div class="col-lg-6 m-auto">--}}

{{--                </div>--}}
{{--                <div class="col-lg-9 m-auto tempaltemo-carousel">--}}
{{--                    <div class="row d-flex flex-row">--}}
{{--                        <!--Controls-->--}}
{{--                        <div class="col-1 align-self-center">--}}

{{--                        </div>--}}
{{--                        <!--End Controls-->--}}

{{--                        <!--Carousel Wrapper-->--}}
{{--                        <div class="col">--}}

{{--                        </div>--}}
{{--                        <!--End Carousel Wrapper-->--}}

{{--                        <!--Controls-->--}}
{{--                        <div class="col-1 align-self-center">--}}

{{--                        </div>--}}
{{--                        <!--End Controls-->--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="container my-4">--}}
{{--            <div class="row text-center py-3">--}}
{{--              <div class="col-lg-6 m-auto">--}}
{{--               --}}
{{--              </div>--}}
{{--              <div class="col-lg-9 m-auto tempaltemo-carousel">--}}
{{--                <div class="row d-flex flex-row">--}}
{{--                  <!--Controls-->--}}
{{--                  <div class="col-1 align-self-center">--}}
{{--                    --}}
{{--                  </div>--}}
{{--                  <!--End Controls-->--}}
{{--    --}}
{{--                  <!--Carousel Wrapper-->--}}
{{--                  <div class="col">--}}
{{--                   --}}
{{--                  </div>--}}
{{--                  <!--End Carousel Wrapper-->--}}
{{--    --}}
{{--                  <!--Controls-->--}}
{{--                  <div class="col-1 align-self-center">--}}
{{--                    --}}
{{--                  </div>--}}
{{--                  <!--End Controls-->--}}
{{--                </div>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--          </div>--}}
{{--    </section>--}}
@endsection
