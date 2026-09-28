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
                                    Danh mục sản phẩm
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
                                <i class="nav-icon fas fa-list-ul"></i>
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
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
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
                                    <a href="{{ URL::to(route('admin.service.create')) }}" class="nav-link active">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Thêm dịch vụ</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to(route('admin.reservation.index')) }}" class="nav-link">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>Quản lý lịch hẹn</p>
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
                        <h1 class="m-0">Thêm dịch vụ</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ URL::to(route('screen_admin_home')) }}">Trang
                                    chủ</a></li>
                            <li class="breadcrumb-item active">Dịch vụ</li>
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
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                        <div class="card">
                            @if (session('message'))
                                <div class="card-header">
                                    <p class="noti">{{ session('message') }}</p>
                                </div>
                            @endif
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="quickForm" action="{{ URL::to(route('admin.service.store')) }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1" class="required">Tên dịch vụ</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-address-card"></i></span>
                                            </div>
                                            <input type="text" name="name" class="form-control" id="exampleInputEmail1"
                                                placeholder="Nhập vào tên dịch vụ">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">Thời gian thực hiện</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                            </div>
                                            <select class="form-control select2bs4" name="work_time">
                                                <option selected="selected" disabled>Chọn thời gian thực hiện</option>
                                                @foreach ($workTimes as $key => $workTime)
                                                    <option value="{{ $key }}">{{ $workTime }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Thời gian tái khám</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <select class="form-control select2bs4 col-5" name="number_recheck">
                                                <option selected="selected" disabled>Chọn thời gian tái khám</option>
                                                @for ($i = 1; $i <= 30; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <select class="form-control select2bs4 col-5" name="unit_recheck">
                                                <option selected="selected" disabled>Chọn đơn vị</option>
                                                <option value="day">Ngày</option>
                                                <option value="month">Tháng</option>
                                                <option value="year">Năm</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Giới thiệu</label>
                                        <textarea id="summernote" name="introduce" placeholder="Nhập vào thông tin giới thiệu dịch vụ"></textarea>
                                    </div>
                                    <div class="form-group row pt-3 mt-3">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" name="active" class="custom-control-input"
                                                    id="customSwitch1">
                                                <label class="custom-control-label" for="customSwitch1">Hoạt
                                                    động</label>
                                            </div>
                                        </div>
                                        <div class="text-right col-md-6">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                    <!-- right column -->
                    <div class="col-md-6"></div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
