@extends('user.layout')
@section('user_content')
    <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Chi tiết đơn hàng</h1>
            </div>
        </div>
    </div>
    <!-- Open Content -->
    <section id="user_detail">
        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
                <h4 class="text-center">Thông tin đơn hàng {{ $order->code_invoice }}</h4>
            </div>
        </div>
        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
                <div class="col-md-1 border-right"></div>
                <div class="col-md-5 border-right">
                    <div class="p-3 py-5" style="padding-left: 0px!important; padding-bottom: 0px!important;">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="text-right">Mã đơn hàng</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 border-right">
                    <div class="p-3 py-5"
                        style="padding-left: 0px!important; padding-bottom: 0px!important;">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="text-right">{{ $order->code_invoice }}</h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 border-right"></div>
                    <div class="col-md-5 border-right">
                        <h5 class="">Trạng thái</h5>
                    </div>
                    <div class="col-md-5 border-right">
                        <h5 class="text-right">{{ $order->status_ship }}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 border-right"></div>
                    <div class="col-md-5 border-right">
                        <h5 class="">Phí giao hàng</h5>
                    </div>
                    <div class="col-md-5 border-right">
                        <h5 class="text-right">30.000 VNĐ</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 border-right"></div>
                    <div class="col-md-5 border-right">
                        <h5 class="">Số tiền cần thanh toán</h5>
                    </div>
                    <div class="col-md-5 border-right">
                        <h5 class="text-right">
                            {{ Lang::get('message.before_unit_money') . number_format($order->need_pay   , 0, ',', '.') . Lang::get('message.after_unit_money') }}
                        </h5>
                    </div>
                </div>
            </div>
            @if (isset($details))
            <table width="100%" style="border: 1px solid;">
                <tr>
                    <th width="33%" class="text-center">Sản phẩm</th>
                    <th width="33%" class="text-center">Số lượng</th>
                    <th width="33%" class="text-center">Thành tiền</th>
                  </tr>
            @foreach ($details as $key => $detail)
                <tr>
                    <td width="33%" class="text-center">{{$detail->product->name}}</td>
                    <td width="33%" class="text-center">{{$detail->quantity}}</td>
                    <td width="33%" class="text-center">
                        {{ Lang::get('message.before_unit_money') . number_format($detail->into_money, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                    </td>
                </tr>
            @endforeach
        </table>
        @endif
        </div>
    </section>
@endsection
