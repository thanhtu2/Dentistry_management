@extends('user.layout')

@section('user_content')

<div class="container py-5" style="margin-top: 120px;">
    <h2 class="mb-4">Lịch khám của tôi</h2>

    @if($reservations->count() > 0)

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Dịch vụ</th>
                        <th>Bác sĩ</th>
                        <th>Ngày khám</th>
                        <th>Giờ khám</th>
                        <th>Lời nhắn</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($reservations as $reservation)
                    <tr>
                        <td>
                            {{ $reservation->service ? $reservation->service->name : 'Không có' }}
                        </td>

                        <td>
                            {{ $reservation->doctor ? $reservation->doctor->name : 'Chưa chọn' }}
                        </td>

                        <td>
                            {{ $reservation->date }}
                        </td>

                        <td>
                            {{ $reservation->time }}
                        </td>

                        <td>
                            {{ $reservation->message }}
                        </td>

                        <td>
                        @if($reservation->status == 0)
                        Chờ xác nhận
                        @elseif($reservation->status == 1)
                        Đã xác nhận
                        @elseif($reservation->status == 2)
                        Đã khám
                        @elseif($reservation->status == 3)
                        Đã hủy
                        @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    @else
        <p>Bạn chưa có lịch khám nào.</p>
    @endif
</div>

@endsection