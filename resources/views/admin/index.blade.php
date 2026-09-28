@extends('admin.layout')

@section('admin_content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1 class="m-0">Lịch sử Chatbox AI</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item">
                            <a href="{{ URL::to(route('screen_admin_home')) }}">
                                Trang chủ
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Lịch sử Chatbox AI
                        </li>

                    </ol>
                </div>

            </div>

        </div>
    </div>


    <section class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title">
                                Danh sách hội thoại Chatbox AI
                            </h3>
                        </div>


                        <div class="card-body">

                            <table
                                id="example1"
                                class="table table-bordered table-striped"
                            >

                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>ID khách hàng</th>
                                        <th>Câu hỏi</th>
                                        <th>Câu trả lời AI</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($chatHistories as $key => $chat)

                                        <tr>

                                            <td>
                                                {{ $key + 1 }}
                                            </td>

                                            <td>
                                                {{ $chat->user_id ?? 'Khách' }}
                                            </td>

                                            <td>
                                                {{ $chat->question }}
                                            </td>

                                            <td>
                                                {{ $chat->answer }}
                                            </td>

                                            <td>
                                                {{ $chat->created_at }}
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection