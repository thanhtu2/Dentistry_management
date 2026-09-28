@extends('admin.layout')

@section('admin_content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Thông tin tài khoản</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            @if(session('message'))
                <div class="alert alert-info">
                    {{ session('message') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">

                    <h4>Thông tin quản trị viên</h4>

                    <div class="form-group">
                        <label>Họ tên</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $user->name }}"
                               readonly>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $user->email }}"
                               readonly>
                    </div>

                    <div class="form-group">
                        <label>Tên đăng nhập</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $user->username }}"
                               readonly>
                    </div>

                    <hr>

                    <h4>Đổi mật khẩu</h4>

                    <form action="{{ route('admin_change_password') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Mật khẩu cũ</label>
                            <input type="password"
                                   name="old_password"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Mật khẩu mới</label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Xác nhận mật khẩu mới</label>
                            <input type="password"
                                   name="confirm_password"
                                   class="form-control"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Đổi mật khẩu
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </section>
</div>

@endsection