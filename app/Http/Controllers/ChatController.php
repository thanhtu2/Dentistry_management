<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Service;
use App\Models\Doctor;
use App\Models\Product;
use App\Models\ChatHistory;
use Illuminate\Support\Str;

use App\Models\InvoiceExport;
use App\Models\DetailInvoiceExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use App\Models\Reservation;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA KHÁCH CÓ MUỐN ĐẶT LỊCH KHÔNG
        |--------------------------------------------------------------------------
        */

        $message = mb_strtolower(trim($request->message), 'UTF-8');
        /*
|--------------------------------------------------------------------------
| KIỂM TRA KHÁCH CÓ MUỐN HỦY ĐƠN HÀNG KHÔNG
|--------------------------------------------------------------------------
*/
$checkReservationMessage = Str::lower(Str::ascii($request->message));

$checkReservationKeywords = [
    'xem lich',
    'xem lich kham',
    'xem lich kham cua toi',
    'lich kham cua toi',
    'kiem tra lich kham',
    'toi co lich kham nao',
    'lich kham sap toi',
    'xem lich hen',
    'lich hen cua toi'
];

foreach ($checkReservationKeywords as $keyword) {
    if (str_contains($checkReservationMessage, $keyword)) {
        return response()->json([
            'status' => true,
            'answer' => 'Dạ được ạ. Em sẽ kiểm tra lịch khám của bạn.',
            'booking' => false,
            'purchase' => false,
            'cancel_order' => false,
            'cancel_reservation' => false,
            'check_reservation' => true
        ]);
    }
}
$cancelReservationMessage = Str::lower(Str::ascii($request->message));

$cancelReservationKeywords = [
    'huy lich',
    'huy lich kham',
    'muon huy lich',
    'muon huy lich kham',
    'toi muon huy lich',
    'toi muon huy lich kham'
];

foreach ($cancelReservationKeywords as $keyword) {
    if (str_contains($cancelReservationMessage, $keyword)) {

        return response()->json([
            'status' => true,
            'answer' => 'Dạ được ạ. Em sẽ kiểm tra các lịch khám bạn có thể hủy.',
            'booking' => false,
            'purchase' => false,
            'cancel_order' => false,
            'cancel_reservation' => true
        ]);
    }
}
$cancelMessage = Str::lower(Str::ascii($request->message));

$cancelKeywords = [
    'huy don',
    'huy don hang',
    'muon huy don',
    'muon huy don hang',
    'toi muon huy don',
    'toi muon huy don hang'
];

foreach ($cancelKeywords as $keyword) {

    if (str_contains($cancelMessage, $keyword)) {

        return response()->json([
            'status' => true,
            'answer' => 'Dạ được ạ. Bạn vui lòng cho biết mã đơn hàng cần hủy.',
            'booking' => false,
            'purchase' => false,
            'cancel_order' => true
        ]);
    }
}

        $bookingKeywords = [
            'đặt lịch',
            'dat lich',

            'muốn đặt lịch',
            'muon dat lich',
            'mun dat lich',

            'tôi muốn khám',
            'toi muon kham',

            'muốn đi khám',
            'muon di kham',

            'đăng ký khám',
            'dang ky kham',

            'đặt lịch khám',
            'dat lich kham'
        ];

        foreach ($bookingKeywords as $keyword) {
            if (mb_strpos($message, $keyword) !== false) {
            
                ChatHistory::create([
                'user_id' => Auth::id(),
                'conversation_id' => $request->conversation_id,
                'question' => $request->message,
                'answer' => 'Dạ được ạ! Bạn có thể đặt lịch khám tại Nha Khoa NA. Nhấn nút "Đặt lịch ngay" bên dưới để chọn bác sĩ, ngày và giờ khám.',
        ]);
                return response()->json([
                    'status' => true,
                    'answer' => 'Dạ được ạ! Bạn có thể đặt lịch khám tại Nha Khoa NA. Nhấn nút "Đặt lịch ngay" bên dưới để chọn bác sĩ, ngày và giờ khám.',
                    'booking' => true
                ]);
            }
        }

        /*
|--------------------------------------------------------------------------
| KIỂM TRA KHÁCH CÓ MUỐN MUA SẢN PHẨM KHÔNG
|--------------------------------------------------------------------------
*/

$normalMessage = Str::lower(Str::ascii($request->message));

$buyKeywords = [
    'muon mua',
    'mun mua',
    'mua ngay',
    'dat mua',
    'mua san pham'
];

$wantToBuy = false;

foreach ($buyKeywords as $keyword) {
    if (str_contains($normalMessage, $keyword)) {
        $wantToBuy = true;
        break;
    }
}

if ($wantToBuy) {

    $productsForBuy = Product::where('active', 1)
        ->where('is_deleted', 0)
        ->where('quantity', '>', 0)
        ->get();

    // ==========================================
    // KIỂM TRA TÊN SẢN PHẨM CÓ TRONG DATABASE
    // ==========================================
    foreach ($productsForBuy as $product) {

        $productName = Str::lower(Str::ascii($product->name));

        // Nếu khách nhập đúng tên sản phẩm đang bán
        if (str_contains($normalMessage, $productName)) {

            return response()->json([
                'status' => true,

                'answer' =>
                    'Dạ, sản phẩm "' . $product->name .
                    '" hiện đang có trên hệ thống. Bạn có thể xem chi tiết và tiến hành mua sản phẩm ngay bên dưới.',

                'booking' => false,

                'purchase' => true,
                'product_id' => $product->id,

                'product_name' => $product->name,

                'product_url' => route('detail_product', [
                    'id' => $product->id
                ])
            ]);
        }
    }


    // ==========================================
    // KHÁCH CHỈ NÓI CHUNG CHUNG MUỐN MUA
    // ==========================================
    $generalBuyMessages = [
        'toi muon mua san pham',
        'muon mua san pham',
        'mun mua san pham',
        'mua san pham',
        'toi muon mua',
        'muon mua',
        'mun mua'
    ];

    if (in_array(trim($normalMessage), $generalBuyMessages)) {

        return response()->json([
            'status' => true,

            'answer' =>
                'Dạ, bạn muốn mua sản phẩm nào ạ? Bạn có thể hỏi em danh sách sản phẩm Nha Khoa NA đang bán hoặc nhập tên sản phẩm cụ thể.',

            'booking' => false,

            'purchase' => false
        ]);
    }


    // ==========================================
    // KHÁCH NÓI SẢN PHẨM NHƯNG KHÔNG TÌM THẤY
    // ==========================================
    return response()->json([
        'status' => true,

        'answer' =>
            'Dạ, em chưa tìm thấy sản phẩm bạn yêu cầu trong hệ thống. Bạn có thể hỏi em danh sách sản phẩm Nha Khoa NA đang bán để lựa chọn nhé.',

        'booking' => false,

        'purchase' => false
    ]);
}


        /*
        |--------------------------------------------------------------------------
        | LẤY DỮ LIỆU DỊCH VỤ THẬT TỪ DATABASE
        |--------------------------------------------------------------------------
        */

        $services = Service::where('active', 1)
            ->select(
                'name',
                'introduce',
                'work_time',
                'number_recheck',
                'unit_recheck'
            )
            ->get();

        $serviceContext =
            "DỮ LIỆU DỊCH VỤ HIỆN CÓ CỦA NHA KHOA NA:\n";

        if ($services->count() > 0) {

            foreach ($services as $service) {

                $serviceContext .=
                    "- Tên dịch vụ: " . $service->name;

                if (!empty($service->introduce)) {
                    $serviceContext .=
                        " | Giới thiệu: "
                        . strip_tags($service->introduce);
                }

                if (!empty($service->work_time)) {
                    $serviceContext .=
                        " | Thời gian thực hiện: "
                        . $service->work_time;
                }

                if (!empty($service->number_recheck)) {

                    $serviceContext .=
                        " | Tái khám sau: "
                        . $service->number_recheck;

                    if (!empty($service->unit_recheck)) {
                        $serviceContext .=
                            " " . $service->unit_recheck;
                    }
                }

                $serviceContext .= "\n";
            }

        } else {

            $serviceContext .=
                "- Hiện chưa có dữ liệu dịch vụ.\n";
        }


        /*
        |--------------------------------------------------------------------------
        | LẤY DỮ LIỆU BÁC SĨ THẬT TỪ DATABASE
        |--------------------------------------------------------------------------
        */

        $doctors = Doctor::with('levelDoctor')
            ->where('active', 1)
            ->select(
                'name',
                'level_id',
                'description',
                'introduce'
            )
            ->get();

        $doctorContext =
            "DỮ LIỆU BÁC SĨ HIỆN CÓ CỦA NHA KHOA NA:\n";

        if ($doctors->count() > 0) {

            foreach ($doctors as $doctor) {

                $doctorContext .=
                    "- Bác sĩ: " . $doctor->name;

                if ($doctor->levelDoctor) {
                    $doctorContext .=
                        " | Trình độ/chức danh: "
                        . $doctor->levelDoctor->name;
                }

                if (!empty($doctor->description)) {
                    $doctorContext .=
                        " | Mô tả: "
                        . strip_tags($doctor->description);
                }

                if (!empty($doctor->introduce)) {
                    $doctorContext .=
                        " | Giới thiệu: "
                        . strip_tags($doctor->introduce);
                }

                $doctorContext .= "\n";
            }

        } else {

            $doctorContext .=
                "- Hiện chưa có dữ liệu bác sĩ.\n";
        }
// ==========================================
// LẤY DỮ LIỆU SẢN PHẨM THỰC TẾ
// ==========================================

$products = Product::where('active', 1)
    ->where('is_deleted', 0)
    ->where('quantity', '>', 0)
    ->select(
        'name',
        'price',
        'price_down',
        'start_promotion',
        'end_promotion',
        'quantity',
        'short_description'
    )
    ->get();

$productContext = "DỮ LIỆU SẢN PHẨM ĐANG BÁN CỦA NHA KHOA NA:\n";

if ($products->count() > 0) {

    foreach ($products as $product) {

        $productContext .= "- Sản phẩm: " . $product->name;

        $productContext .=
            " | Giá: " .
            number_format($product->price, 0, ',', '.') .
            " VNĐ";

        if (!empty($product->price_down)) {
            $productContext .=
                " | Giá khuyến mãi: " .
                number_format($product->price_down, 0, ',', '.') .
                " VNĐ";
        }

        $productContext .=
            " | Số lượng còn: " .
            $product->quantity;

        if (!empty($product->short_description)) {
            $productContext .=
                " | Mô tả: " .
                strip_tags($product->short_description);
        }

        $productContext .= "\n";
    }

} else {

    $productContext .= "- Hiện chưa có sản phẩm đang bán.\n";
}

        /*
        |--------------------------------------------------------------------------
        | GỬI CÂU HỎI CHO GEMINI
        |--------------------------------------------------------------------------
        */

        try {

            $models = [
                'gemini-3.5-flash',
                'gemini-3.5-flash-lite',
            ];

            foreach ($models as $model) {

                // Mỗi model thử tối đa 2 lần
                for ($attempt = 1; $attempt <= 2; $attempt++) {

                    $response = Http::withHeaders([
                        'x-goog-api-key' => env('GEMINI_API_KEY'),
                        'Content-Type' => 'application/json',
                    ])
                    ->timeout(30)
                    ->post(
                        'https://generativelanguage.googleapis.com/v1beta/models/'
                        . $model
                        . ':generateContent',

                        [
                            'systemInstruction' => [
                                'parts' => [
                                    [
                                        'text' =>
                                        'Bạn là trợ lý AI của Nha Khoa NA.

                                        Trả lời bằng tiếng Việt, ngắn gọn, thân thiện và dễ hiểu.
                                        Người dùng có thể nhập tiếng Việt có dấu hoặc không dấu.

                                        Bạn chỉ hỗ trợ:
                                        - Kiến thức chăm sóc răng miệng cơ bản.
                                        - Dịch vụ của Nha Khoa NA.
                                        - Bác sĩ của Nha Khoa NA.
                                        - Hướng dẫn đặt lịch khám.

                                        QUY TẮC QUAN TRỌNG:

                                        - Khi khách hỏi Nha Khoa NA có dịch vụ gì,
                                          chỉ sử dụng dữ liệu dịch vụ được cung cấp bên dưới.

                                        - Không tự bịa tên dịch vụ mà hệ thống không có.

                                        - Không tự bịa giá dịch vụ vì hệ thống
                                          hiện không cung cấp dữ liệu giá.

                                        - Khi khách hỏi về bác sĩ của Nha Khoa NA,
                                          chỉ sử dụng dữ liệu bác sĩ được cung cấp bên dưới.

                                        - Không tự bịa tên bác sĩ,
                                          trình độ hoặc thông tin bác sĩ.

                                        - Nếu dữ liệu không có thông tin khách hỏi,
                                          hãy nói rằng hiện chưa có thông tin đó.
                                        - Không chẩn đoán bệnh chắc chắn.
                                        - Không kê đơn thuốc.
                                        - Nếu có triệu chứng nghiêm trọng, khuyên khách đến bác sĩ kiểm tra.
                                        - Khi khách hỏi Nha Khoa NA đang bán sản phẩm gì, chỉ sử dụng dữ liệu sản phẩm được cung cấp bên dưới.
                                        - Không tự bịa tên sản phẩm, giá hoặc số lượng.
                                        - Nếu khách hỏi giá sản phẩm, trả lời theo dữ liệu sản phẩm của hệ thống.
                                        - Nếu sản phẩm không có trong dữ liệu, hãy nói hiện hệ thống chưa có thông tin về sản phẩm đó.

                                        '
                                        . $serviceContext
                                        . "\n"
                                        . $doctorContext
                                        . "\n"
                                        . $productContext
                                    ]
                                ]
                            ],

                            'contents' => [
                                [
                                    'role' => 'user',

                                    'parts' => [
                                        [
                                            'text' => $request->message
                                        ]
                                    ]
                                ]
                            ],

                            'generationConfig' => [
                                'maxOutputTokens' => 1000,
                                'temperature' => 0.5
                            ]
                        ]
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | GEMINI TRẢ LỜI THÀNH CÔNG
                    |--------------------------------------------------------------------------
                    */

                    if ($response->successful()) {

                        $data = $response->json();

                        $answer =
                            $data['candidates'][0]['content']['parts'][0]['text']
                            ?? 'Xin lỗi, tôi chưa thể trả lời câu hỏi này.';

                            ChatHistory::create([
                            'user_id' => Auth::id(),
                            'question' => $request->message,
                            'answer' => $answer,
                         ]);

                        return response()->json([
                            'status' => true,
                            'answer' => $answer,
                            'booking' => false
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | GEMINI QUÁ TẢI 503
                    |--------------------------------------------------------------------------
                    */

                    if ($response->status() == 503) {

                        if ($attempt < 2) {

                            sleep(2);

                            continue;
                        }

                        // Chuyển sang model dự phòng
                        break;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | LỖI KHÁC 503
                    |--------------------------------------------------------------------------
                    */

                    break;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | CẢ 2 MODEL ĐỀU KHÔNG TRẢ LỜI ĐƯỢC
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'status' => false,
                'answer' =>
                    'Trợ lý AI hiện đang bận. Vui lòng thử lại sau ít phút.',
                'booking' => false
            ]);


        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'answer' =>
                    'Trợ lý AI tạm thời không thể kết nối. Vui lòng thử lại sau.',
                'booking' => false
            ]);
        }
    }
    public function createChatOrder(Request $request)
{
    $request->validate([
        'product_id' => 'required|integer',
        'quantity'   => 'required|integer|min:1',
        'name'       => 'required|string|max:255',
        'phone'      => 'required|string|max:20',
        'email'      => 'required|email|max:255',
        'address'    => 'required|string|max:500',
    ]);

    if (!Auth::check()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn cần đăng nhập trước khi đặt hàng.'
        ], 401);
    }

    $product = Product::where('id', $request->product_id)
        ->where('active', 1)
        ->where('is_deleted', 0)
        ->first();

    if (!$product) {
        return response()->json([
            'status' => false,
            'message' => 'Sản phẩm không tồn tại hoặc đã ngừng bán.'
        ]);
    }

    if ($product->quantity < $request->quantity) {
        return response()->json([
            'status' => false,
            'message' => 'Số lượng sản phẩm trong kho không đủ.'
        ]);
    }

try {

    return DB::transaction(function () use ($request, $product) {

        // Giá mặc định
        $price = $product->price;

        // Kiểm tra khuyến mãi
        if (
            !empty($product->price_down) &&
            !empty($product->start_promotion) &&
            !empty($product->end_promotion)
        ) {
            $now = Carbon::now();

            if ($now->between(
                Carbon::parse($product->start_promotion),
                Carbon::parse($product->end_promotion)
            )) {
                $price = $product->price_down;
            }
        }

        // Tiền sản phẩm
        $productMoney = $price * $request->quantity;

        // Phí vận chuyển
        $shippingFee = 50000;

        $addressCheck = Str::lower(
            Str::ascii($request->address)
        );

        // TP.HCM: 30.000đ
        if (
            str_contains($addressCheck, 'hcm') ||
            str_contains($addressCheck, 'ho chi minh')
        ) {
            $shippingFee = 30000;
        }

        // =========================
        // TẠO ĐƠN HÀNG
        // =========================
        $invoice = new InvoiceExport();

        $invoice->code_invoice = Str::random(10);
        $invoice->into_money = $productMoney;
        $invoice->need_pay = $productMoney + $shippingFee;

        $invoice->user_id = Auth::id();

        // Thanh toán COD
        $invoice->is_pay_cod = 1;
        $invoice->is_payment = 0;

        $invoice->email_user = $request->email;
        $invoice->name_user = $request->name;
        $invoice->phone_user = $request->phone;
        $invoice->address = $request->address;

        $invoice->status_ship = Lang::get('message.received');

        $invoice->message =
            'Đơn hàng được đặt qua Chatbox AI';

        $invoice->save();


        // =========================
        // CHI TIẾT ĐƠN HÀNG
        // =========================
        $detail = new DetailInvoiceExport();

        $detail->invoice_export_id = $invoice->id;
        $detail->product_id = $product->id;
        $detail->quantity = $request->quantity;
        $detail->into_money = $productMoney;

        $detail->save();


        // =========================
        // TRẢ KẾT QUẢ CHO CHATBOX
        // =========================
        return response()->json([
            'status' => true,

            'message' =>
                'Đặt hàng thành công! Mã đơn hàng: '
                . $invoice->code_invoice,

            'code_invoice' => $invoice->code_invoice,
            'product_name' => $product->name,
            'quantity' => (int) $request->quantity,

            'product_money' => $productMoney,
            'shipping_fee' => $shippingFee,
            'total_money' => $invoice->need_pay
        ]);
    });

} catch (\Exception $e) {

    return response()->json([
        'status' => false,
        'message' => 'Không thể tạo đơn hàng. Vui lòng thử lại.'
    ], 500);
}
}
public function createChatReservation(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn cần đăng nhập để đặt lịch khám.'
        ], 401);
    }

    $services = Service::all();
    ChatHistory::create([
    'user_id' => Auth::id(),
    'conversation_id' => $request->conversation_id,
    'question' => 'Mở chức năng đặt lịch',
    'answer' => 'Vui lòng chọn dịch vụ bạn muốn đặt lịch khám.',
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Đã lấy danh sách dịch vụ.',
        'services' => $services
    ]);
}

public function confirmChatReservation(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn cần đăng nhập để đặt lịch khám.'
        ], 401);
    }

    $request->validate([
        'service_id' => 'required|integer',
        'doctor_id' => 'required|integer',
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'date' => 'required|date',
        'time' => 'required',
    ]);

    $reservationModel = new Reservation();

    $response = $reservationModel->createReservation($request);

    return response()->json([
        'status' => $response['status'],
        'message' => $response['message']
    ]);
}

public function getChatDoctors(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn cần đăng nhập để đặt lịch khám.'
        ], 401);
    }

    $doctors = Doctor::where('active', 1)->get();

    return response()->json([
        'status' => true,
        'message' => 'Đã lấy danh sách bác sĩ.',
        'doctors' => $doctors
    ]);
}

public function getChatFreeTimes(Request $request)
{
    $request->validate([
        'doctor_id' => 'required|integer',
        'service_id' => 'required|integer',
        'date' => 'required|date',
    ]);

    if (!Auth::check()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn cần đăng nhập để đặt lịch khám.'
        ], 401);
    }

    $reservationModel = new Reservation();

    $freeTimes = $reservationModel->getFreeTimeDoctor(
        $request->doctor_id,
        $request->date,
        $request->service_id
    );

    return response()->json([
        'status' => true,
        'free_times' => $freeTimes
    ]);
}
public function checkChatOrder(Request $request)
{
    $request->validate([
        'code_invoice' => 'required|string|max:255',
    ]);

    if (!Auth::check()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn cần đăng nhập để kiểm tra đơn hàng.'
        ], 401);
    }

    $invoice = InvoiceExport::where('code_invoice', $request->code_invoice)
        ->where('user_id', Auth::id())
        ->first();

    if (!$invoice) {
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy đơn hàng này trong tài khoản của bạn.'
        ]);
    }

    return response()->json([
        'status' => true,
        'message' => 'Đã tìm thấy đơn hàng.',
        'code_invoice' => $invoice->code_invoice,
        'status_ship' => $invoice->status_ship
    ]);
}

public function cancelChatOrder(Request $request)
{
    $request->validate([
        'code_invoice' => 'required|string|max:255',
    ]);

    if (!Auth::check()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn cần đăng nhập để hủy đơn hàng.'
        ], 401);
    }

    $invoice = InvoiceExport::where('code_invoice', $request->code_invoice)
        ->where('user_id', Auth::id())
        ->first();

    if (!$invoice) {
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy đơn hàng này trong tài khoản của bạn.'
        ]);
    }
// ==========================================
// KIỂM TRA TRẠNG THÁI ĐƠN HÀNG
// ==========================================

if ($invoice->status_ship !== Lang::get('message.received')) {

    return response()->json([
        'status' => false,
        'message' => 'Đơn hàng đã được xử lý nên không thể hủy.'
    ]);
}
$invoice->status_ship = 'Đã hủy';
$invoice->save();

return response()->json([
    'status' => true,
    'message' => 'Hủy đơn hàng thành công.',
    'code_invoice' => $invoice->code_invoice,
    'status_ship' => $invoice->status_ship
]);  
} 

public function checkChatReservation(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn cần đăng nhập để kiểm tra lịch khám.'
        ], 401);
    }

    $reservations = Reservation::with(['doctor', 'service'])
        ->where('user_id', Auth::id())
        ->whereIn('status', [0, 1])
        ->whereDate('date', '>=', Carbon::today())
        ->orderBy('date', 'ASC')
        ->orderBy('time', 'ASC')
        ->get();

    if ($reservations->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn hiện không có lịch khám nào có thể hủy.'
        ]);
    }

    return response()->json([
        'status' => true,
        'message' => 'Đã tìm thấy lịch khám.',
        'reservations' => $reservations
    ]);
}

public function cancelChatReservation(Request $request)
{
    $request->validate([
        'reservation_id' => 'required|integer',
    ]);

    if (!Auth::check()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn cần đăng nhập để hủy lịch khám.'
        ], 401);
    }

    // Chỉ tìm lịch thuộc đúng tài khoản đang đăng nhập
    $reservation = Reservation::where('id', $request->reservation_id)
        ->where('user_id', Auth::id())
        ->first();

    if (!$reservation) {
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy lịch khám này trong tài khoản của bạn.'
        ]);
    }

    // status 2 = Đã khám
    if ((int) $reservation->status === 2) {
        return response()->json([
            'status' => false,
            'message' => 'Lịch khám này đã hoàn thành nên không thể hủy.'
        ]);
    }

    // status 3 = Đã hủy
    if ((int) $reservation->status === 3) {
        return response()->json([
            'status' => false,
            'message' => 'Lịch khám này đã được hủy trước đó.'
        ]);
    }

    // Không cho hủy lịch đã qua thời gian khám
    $reservationDateTime = Carbon::parse(
        $reservation->date . ' ' . $reservation->time
    );

    if ($reservationDateTime->isPast()) {
        return response()->json([
            'status' => false,
            'message' => 'Lịch khám đã qua thời gian nên không thể hủy.'
        ]);
    }

    // Hủy lịch
    $reservation->status = 3;
    $reservation->save();

    return response()->json([
        'status' => true,
        'message' => 'Hủy lịch khám thành công.'
    ]);
}
public function saveChatHistory(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn cần đăng nhập.'
        ], 401);
    }

    $request->validate([
        'conversation_id' => 'required|string|max:100',
        'question' => 'required|string|max:1000',
        'answer' => 'required|string',
    ]);

    ChatHistory::create([
        'user_id' => Auth::id(),
        'conversation_id' => $request->conversation_id,
        'question' => $request->question,
        'answer' => $request->answer,
    ]);

    return response()->json([
        'status' => true
    ]);
}
}