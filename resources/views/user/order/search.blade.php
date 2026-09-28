@extends('user.layout')
@section('user_content')
    <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">Kiểm tra đơn hàng</h1>
            </div>
        </div>
    </div>
    <!-- Open Content -->
    <section class="bg-light">
        <div class="container">
            <div class="row">
                <div class="mt-5">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="GET" class="form-inline">
                                <div class="pb-2">
                                    <h5 class="card-title">
                                        Nhập vào mã đơn hàng của bạn
                                    </h5>
                                    <div class="form-group mr-2">
                                        <input type="text" class="form-control" name="code_invoice" id="inputOrderCode" required
                                            placeholder="Nhập vào mã đơn hàng" />
                                    </div>
                                </div>
                                <div class="row pb-3">
                                    <div class="col d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            Tìm kiếm
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @if (isset($order))
                            <div class="form-group mr-2 pt-4" >
                                <h3>Đơn hàng: {{$order->code_invoice}}</h3>
                                <p>Trạng thái: {{$order->status_ship}}</p>
                                <p>Số tiền cần thanh toán: {{ Lang::get('message.before_unit_money') . number_format($order->need_pay, 0, ',', '.') . Lang::get('message.after_unit_money') }}</p>
                            </div>
                            @else
                            @if (session('message'))
                            <div class="card-header">
                                <p class="noti">{{ session('message') }}</p>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container my-4">
          <div class="row text-center py-3">
            <div class="col-lg-6 m-auto">

            </div>
            <div class="col-lg-9 m-auto tempaltemo-carousel">
              <div class="row d-flex flex-row">
                <!--Controls-->
                <div class="col-1 align-self-center">

                </div>
                <!--End Controls-->

                <!--Carousel Wrapper-->
                <div class="col">

                </div>
                <!--End Carousel Wrapper-->

                <!--Controls-->
                <div class="col-1 align-self-center">

                </div>
                <!--End Controls-->
              </div>
            </div>
          </div>
        </div>
        <div class="container my-4">
            <div class="row text-center py-3">
              <div class="col-lg-6 m-auto">

              </div>
              <div class="col-lg-9 m-auto tempaltemo-carousel">
                <div class="row d-flex flex-row">
                  <!--Controls-->
                  <div class="col-1 align-self-center">

                  </div>
                  <!--End Controls-->

                  <!--Carousel Wrapper-->
                  <div class="col">

                  </div>
                  <!--End Carousel Wrapper-->

                  <!--Controls-->
                  <div class="col-1 align-self-center">

                  </div>
                  <!--End Controls-->
                </div>
              </div>
            </div>
          </div>
          <div class="container my-4">
            <div class="row text-center py-3">
              <div class="col-lg-6 m-auto">

              </div>
              <div class="col-lg-9 m-auto tempaltemo-carousel">
                <div class="row d-flex flex-row">
                  <!--Controls-->
                  <div class="col-1 align-self-center">

                  </div>
                  <!--End Controls-->

                  <!--Carousel Wrapper-->
                  <div class="col">

                  </div>
                  <!--End Carousel Wrapper-->

                  <!--Controls-->
                  <div class="col-1 align-self-center">

                  </div>
                  <!--End Controls-->
                </div>
              </div>
            </div>
          </div>
      </section>

    <!-- Close Content -->
@endsection
