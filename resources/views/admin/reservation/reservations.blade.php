@extends('admin.layout')
@section('admin_content')
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4" style=" background-color: black; ">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a class="d-block">{{ auth()->user()->name }}</a>
                </div>
            </div>
            <!-- SidebarSearch Form -->
{{--            <div class="form-inline">--}}
{{--                <div class="input-group" data-widget="sidebar-search">--}}
{{--                    <input class="form-control form-control-sidebar" type="search" placeholder="Tìm kiếm"--}}
{{--                        aria-label="Search">--}}
{{--                    <div class="input-group-append">--}}
{{--                        <button class="btn btn-sidebar">--}}
{{--                            <i class="fas fa-search fa-fw"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ URL::to(route('screen_admin_home')) }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Trang chủ</p>
                        </a>
                    </li>
                    @if(auth()->user()->role->name !== Config::get('auth.roles.doctor'))
                        <li class="nav-header">Thông tin</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-bookmark"></i>
                                <p>
                                    Thương hiệu
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.brand.index')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách thương hiệu</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.brand.create')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm thương hiệu</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Danh mục
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.category.index')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách danh mục</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.category.create')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm danh mục</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-bars"></i>
                                <p>
                                    Sản phẩm
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.product.index')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách sản phẩm</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.product.create')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm sản phẩm</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-md"></i>
                                <p>
                                    Bác sĩ
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.doctor.index')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách bác sĩ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.doctor.create')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm bác sĩ</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-server"></i>
                                <p>
                                    Dịch vụ
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.service.index')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách dịch vụ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.service.create')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm dịch vụ</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to(route('admin.reservation.index')) }}" class="nav-link  active">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>Quản lý lịch hẹn</p>
                            </a>
                        </li>

                        <li class="nav-item">
                          <a href="{{ url('/admin/chat-history') }}" class="nav-link">
                          <i class="nav-icon fas fa-comments"></i>
                          <p>Lịch sử Chatbox AI</p>
                          </a>
                        </li>
                        <li class="nav-header">Hóa đơn</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-download"></i>
                                <p>
                                    Hóa đơn nhập
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.invoice_import.index')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách hóa đơn</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::to(route('admin.invoice_import.create')) }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Nhập hàng</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to(route('admin.invoice_export.order')) }}" class="nav-link">
                                <i class="nav-icon fas fa-paste"></i>
                                <p>Đơn đặt hàng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to(route('admin.invoice_export.invoice')) }}" class="nav-link">
                                <i class="nav-icon fas fa-file-export"></i>
                                <p>Hóa đơn bán</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to(route('admin.invoice_export.close_orders')) }}" class="nav-link">
                                <i class="nav-icon fas fa-times-circle"></i>
                                <p>Đơn đã hủy</p>
                            </a>
                        </li>
                        <li class="nav-header">Thống kê</li>
                        <li class="nav-item">
                            <a href="{{ URL::to(route('admin.statistical.products')) }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Thống kê sản phẩm</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to(route('admin.statistical.invoices')) }}" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>Thống kê hóa đơn</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to(route('admin.statistical.users')) }}" class="nav-link">
                                <i class="nav-icon fas fa-id-card-alt"></i>
                                <p>Thống kê khách hàng</p>
                            </a>
                        </li>
                        @if (auth()->user()->role->name === Config::get('auth.roles.manager'))
                            <li class="nav-header">Tài khoản</li>
                            <li class="nav-item">
                                <a href="{{ URL::to(route('admin.account.index')) }}" class="nav-link">
                                    <i class="nav-icon fas fa-address-book"></i>
                                    <p>Danh sách tài khoản</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ URL::to(route('admin.account.create')) }}" class="nav-link">
                                    <i class="nav-icon fas fa-user-plus"></i>
                                    <p>Cấp tài khoản mới</p>
                                </a>
                            </li>
                            {{--                        <li class="nav-item">--}}
    {{--                            <a href="#" class="nav-link">--}}
    {{--                                <i class="nav-icon fas fa-sliders-h"></i>--}}
    {{--                                <p>--}}
    {{--                                    Slidebar--}}
    {{--                                    <i class="right fas fa-angle-left"></i>--}}
    {{--                                </p>--}}
    {{--                            </a>--}}
    {{--                            <ul class="nav nav-treeview">--}}
    {{--                                <li class="nav-item">--}}
    {{--                                    <a href="{{ URL::to(route('admin.sidebar.index')) }}" class="nav-link">--}}
    {{--                                        <i class="far fa-circle nav-icon"></i>--}}
    {{--                                        <p>Danh sách slider</p>--}}
    {{--                                    </a>--}}
    {{--                                </li>--}}
    {{--                                <li class="nav-item">--}}
    {{--                                    <a href="{{ URL::to(route('admin.sidebar.create')) }}" class="nav-link">--}}
    {{--                                        <i class="far fa-circle nav-icon"></i>--}}
    {{--                                        <p>Thêm Slider</p>--}}
    {{--                                    </a>--}}
    {{--                                </li>--}}
    {{--                            </ul>--}}
    {{--                        </li>--}}
                        @endif
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Danh sách lịch hẹn</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ URL::to(route('screen_admin_home')) }}">Trang
                                    chủ</a></li>
                            <li class="breadcrumb-item active">Danh sách lịch hẹn</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            @if (session('message'))
                                <div class="card-header">
                                    <p class="noti">{{ session('message') }}</p>
                                </div>
                            @endif
                            <!-- /.card-header -->
                            <div class="card-body">
                                <form action="{{ URL::to(route('admin.reservation.index')) }}" method="GET">
                                    <div class="form-group row">
                                        <label>Chọn thời gian:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="date" class="form-control float-right" id="reservation">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">Xác nhận</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Số thứ tự</th>
                                            <th>Ngày</th>
                                            <th>Tên bác sĩ</th>
                                            <th>Tên người đặt</th>
                                            <th>Dịch vụ</th>
                                            <th>Số điện thoại</th>
                                            <th>Thời gian</th>
                                            <th>Trạng thái</th>
                                            <th>Thời gian tái khám</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($reservations as $key => $reservation)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $reservation->date }}</td>
                                                <td>{{ $reservation->doctor->name  ?? ""}}</td>
                                                <td>{{ $reservation->name }}</td>
                                                <td>{{ $reservation->service->name ?? null}}</td>
                                                <td>{{ $reservation->phone }}</td>
                                                <td>{{ $reservation->time }}</td>
                                                <td>
                                                    @if ($reservation->status == 0) Chờ xác nhận
                                                    @elseif ($reservation->status == 1) Đã xác nhận
                                                    @elseif ($reservation->status == 2) Đã khám
                                                    @elseif ($reservation->status == 3) Đã hủy
                                                    @endif
                                                </td>
                                                <td>{{ $reservation->date_recheck }}</td>
                                                <td class="act">
                                                    <div class="row pd-12">
                                                        <a
                                                            href="{{ URL::to(route('admin.reservation.edit', ['reservation' => $reservation->id])) }}">
                                                            <i class="fas fa-edit ico"></i>
                                                        </a>
                                                        <form action="{{ route('admin.reservation.destroy', ['reservation' => $reservation->id]) }}"
                                                           method="POST"
                                                       style="display:inline-block; margin-left:15px;"
                                                         onsubmit="return confirm('Bạn có chắc muốn xóa lịch hẹn này không?');">

                                                            @csrf
                                                         @method('DELETE')

                                                       <button type="submit"
                                                        style="border:none; background:none; padding:0; cursor:pointer;">
                                                        <i class="fas fa-trash" style="color:red;"></i>
                                                    </button>
                                                  </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
