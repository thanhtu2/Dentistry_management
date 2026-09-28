@extends('user.layout')
@section('user_content')
    <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Giỏ hàng</h1>
            </div>
        </div>
    </div>
    <section class="bg-light" id="card_container">
        @if (session('message'))
            <p style=" vertical-align: middle;
                text-align: center;
                font-size: 30px;
                color: red;">
                {{ session('message') }}
            </p>
        @endif
        <div class="container d-lg-flex">
            <div class="box-1 py-3 col-9">
                <form action="{{ URL::to(route('update_cart')) }}" method="POST" >
                    @csrf
                    <?php $total = 0; ?>
                    @foreach (Cart::content()->groupBy('id')->toArray() as $productCart)
                        @foreach ($products as $keyProduct => $product)
                            @if ($productCart[0]['id'] == $product->id)
                            <div class="dis list-product info d-flex" style="padding: 20px 0;">
                                <div class="product-image col-2">
                                    <img src="{{ asset('' . $product->image) }}" style="height: 100%; width:80%"/>
                                </div>
                                <div class="product-name d-flex col-7">
                                    <h5 class="col-4">{{ $product->name }}</h5>
                                    <p class="col-4" style="margin: 0; padding-right: 30px;
                                    padding-left: 30px;">Đơn giá:
                                        <br>
                                        <span style="font-weight:bold!important">
                                            {{ Lang::get('message.before_unit_money') . number_format($productCart[0]['price'], 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                        </span>
                                    </p>
                                    <p class="col-4" style="margin: 0; font-weigh:bold!important;">Thành tiền:
                                        <br>
                                        <span style="font-weight:bold!important">
                                            {{ Lang::get('message.before_unit_money') . number_format($productCart[0]['qty'] * $productCart[0]['price'], 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                        </span>
                                    </p>
                                    <?php $total = (int) $total + (int) $productCart[0]['qty'] * (int) $productCart[0]['price']; ?>
                                </div>
                                <div class="product-event d-flex col-3">
                                    <div class="d-flex action">
                                        <p style="margin-right: 10px"> Số lượng: </p>
                                        <br>
                                        <input name= "{{$productCart[0]['rowId']}}" value="{{ $productCart[0]['qty'] }}" min="1" style="height: min-content; width: 70px;" required type="number" id="quantity">
                                        <a type="button"
                                            style="margin-left: 10px"
                                            href="{{ URL::to(route('delete_cart', ['id' => $productCart[0]['rowId']])) }}">
                                            <i id="btnMinus" class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @endforeach
                    <style>
                        #map {
                          height: 500px;
                          width: 100%;
                        }
                        #pac-input {
                          margin-top: 10px;
                          width: 300px;
                          height: 30px;
                          padding: 0 12px;
                          font-size: 16px;
                          border: 1px solid #d9d9d9;
                          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                          z-index: 5;
                          position: absolute;
                          left: 50%;
                          transform: translateX(-50%);
                        }
                    </style>
                <button type="submit" class="btn btn-primary mt-2 d-flex justify-content-center">
                    Xác nhận
                </button>
                    <div id="map" style="height: 600px; width: 90%;"></div>
            </form>
            </div>
            @if (isset($product))
                <div class="box-2 col-3">
                    <div class="box-inner-2">
                        <div>
                            <h3 class="fw-bold">Thông tin thanh toán</h3>
                        </div>
                        <form action="{{ URL::to(route('create_order')) }}" id="create_order" method="POST">
                            @csrf
                            <div class="mb-3">
                                <p class="dis fw-bold mb-2">Email</p>
                                <input class="form-control" type="email" id="email" name="email_user" required
                                    @if ($user !== null) value="{{ $user->email }}" @endif />
                            </div>
                            <div class="mb-3">
                                <p class="dis fw-bold mb-2">Tên người nhận</p>
                                <input class="form-control" type="text" id="name" name="name_user" required
                                    @if ($user !== null) value="{{ $user->name }}" @endif />
                            </div>
                            <div class="mb-3">
                                <p class="dis fw-bold mb-2">Số điện thoại</p>
                                <input class="form-control" type="number" id="phone" name="phone_user" required
                                    @if ($user !== null) value="{{ $user->phone }}" @endif />
                            </div>
                            <div class="mb-3">
                                <p class="dis fw-bold mb-2">Địa chỉ</p>
                                <input class="form-control" type="text" id="address" name="address" required
                                    @if ($user !== null) value="{{ $user->address }}" @endif />
                            </div>
                            <div class="mb-3">
                                <p class="dis fw-bold mb-2">Lời nhắn</p>
                                <textarea class="form-control" type="text" name="message"></textarea>
                            </div>
                            <input class="form-control" type="hidden" name="into_money" id="total"
                                value="{{ $total }}" />
                            <input class="form-control" type="hidden" name="is_pay_cod" id="is_pay_cod" value="1" />
                            <div>
                                <div class="address">
                                    <div class="d-flex flex-column dis">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <p class="fw-bold">Thành tiền</p>
                                            <p class="fw-bold">
                                                {{ Lang::get('message.before_unit_money') . number_format($total, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                            </p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <p class="fw-bold">Phí giao hàng</p>
                                            <p class="fw-bold" id="shipping-fee">
                                                {{ Lang::get('message.before_unit_money') . number_format(30000, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                            </p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <p class="fw-bold">Tổng cộng</p>
                                            <p class="fw-bold" id="total-amount">
                                                {{ Lang::get('message.before_unit_money') . number_format($total + 30000, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                            </p>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-2">
                                            Thanh toán COD
                                        </button>
                                        <button class="btn ">
                                            <div id="paypal-button"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
            <script>
        let map;
        let destinationMarker;
        let directionsService;
        let directionsRenderer;

    function initMap() {
        const uluru = {
            lat: 10.84571529688809,
            lng: 106.79417640174958
        };

        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 18,
            center: uluru,
        });

        destinationMarker = new google.maps.Marker({
            map: map,
            animation: google.maps.Animation.DROP,
            position: uluru
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer();
        directionsRenderer.setMap(map);

        destinationMarker.addListener('dragend', function() {
            calculateAndDisplayRoute();
        });

        map.addListener('click', function(event) {
            placeMarker(event.latLng);
        });
    }

    function placeMarker(location) {
        if (destinationMarker) {
            destinationMarker.setPosition(location);
        } else {
            destinationMarker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP
            });
        }
        calculateAndDisplayRoute();
    }

    function calculateAndDisplayRoute() {
        const start = map.getCenter();
        const end = destinationMarker.getPosition();

        const request = {
            origin: start,
            destination: end,
            travelMode: 'DRIVING'
    };

    directionsService.route(request, function(result, status) {
        if (status == 'OK') {
            directionsRenderer.setDirections(result);
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });

}

    </script>
    <script>
        document.getElementById('address').addEventListener('input', function() {
            const address = this.value.toLowerCase();
            const shippingFeeElement = document.getElementById('shipping-fee');
            const totalAmountElement = document.getElementById('total-amount');
            const totalAmount = document.getElementById('total');

            let shippingFee = 50000;

            if (address.includes('hcm') || address.includes('hồ chí minh') || address.includes('ho chi minh')) {
                shippingFee = 30000;
            }

            shippingFeeElement.textContent = '{{ Lang::get('message.before_unit_money') }}' + shippingFee.toLocaleString() + '{{ Lang::get('message.after_unit_money') }}';
            const total = {{ $total }} + shippingFee;
            totalAmountElement.textContent = '{{ Lang::get('message.before_unit_money') }}' + total.toLocaleString() + '{{ Lang::get('message.after_unit_money') }}';
        });
    </script>
        <script type="module" src="https://unpkg.com/@googlemaps/extended-component-library@0.6"></script>
    </section>
@endsection
