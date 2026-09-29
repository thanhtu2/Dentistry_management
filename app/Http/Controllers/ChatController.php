<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Service;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
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
use App\Rules\RealEmail;
use Illuminate\Support\Facades\Validator;

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

// Các lượt hỏi - đáp gần nhất của cuộc trò chuyện này (để hiểu "có", "ok"... đang trả lời cho câu nào)
$chatHistory = $request->conversation_id
    ? ChatHistory::where('conversation_id', $request->conversation_id)
        ->orderBy('id', 'DESC')
        ->limit(6)
        ->get()
        ->reverse()
        ->values()
    : collect();

// ==========================================
// LỜI CHÀO / CẢM ƠN -> TRẢ LỜI NGAY, KHÔNG GỌI GEMINI (nhanh và đỡ tốn quota)
// ==========================================
$quickText = trim(preg_replace('/\s+/', ' ', preg_replace('/[^a-z0-9 ]/', ' ', $checkReservationMessage)));

$greetings = [
    'xin chao', 'chao', 'chao ban', 'xin chao ban', 'chao em', 'chao anh', 'chao chi',
    'chao bac si', 'hello', 'helo', 'hi', 'hi ban', 'hey', 'alo', 'alo alo',
];
$thanks = [
    'cam on', 'cam on ban', 'cam on nhe', 'cam on nhieu', 'cam on em', 'cam on ad',
    'thanks', 'thank you', 'thank', 'ok cam on', 'ok thanks',
];

$quickAnswer = null;
// Gộp chữ lặp để "chàooo", "hii" vẫn khớp lời chào
$collapse = function ($s) {
    return preg_replace('/(.)\1+/', '$1', $s);
};
$quickCollapsed = $collapse($quickText);
$greetingsCollapsed = array_map($collapse, $greetings);
$thanksCollapsed = array_map($collapse, $thanks);

if (in_array($quickCollapsed, $greetingsCollapsed, true)) {
    $quickAnswer = "Xin chào bạn! 👋 Mình là trợ lý AI của Nha Khoa NA.\n\n"
        . "Mình có thể giúp bạn xem dịch vụ và bảng giá, thông tin bác sĩ, "
        . "đặt lịch khám (nhắn \"đặt lịch\") hoặc mua sản phẩm (nhắn \"mua\" + tên sản phẩm).\n\n"
        . "Bạn cần hỗ trợ gì ạ?";
} elseif (in_array($quickCollapsed, $thanksCollapsed, true)) {
    $quickAnswer = "Không có gì ạ! 😊 Cần hỗ trợ thêm bạn cứ nhắn mình nhé.";
}

if ($quickAnswer !== null) {
    ChatHistory::create([
        'user_id' => Auth::id(),
        'conversation_id' => $request->conversation_id,
        'question' => $request->message,
        'answer' => $quickAnswer,
    ]);

    return response()->json([
        'status' => true,
        'answer' => $quickAnswer,
        'booking' => false
    ]);
}

// ==========================================
// KHÁCH TRẢ LỜI "CÓ / OK / ĐỒNG Ý" CHO CÂU HỎI TRƯỚC
// ==========================================
$affirmativeReplies = [
    'co', 'co a', 'co nhe', 'co chu', 'co muon', 'co em', 'co ban',
    'ok', 'oke', 'okay', 'ok a', 'yes', 'u', 'uh', 'um', 'vang', 'vang a', 'da', 'da co', 'da vang',
    'dong y', 'duoc', 'duoc a', 'muon', 'toi muon', 'minh muon', 'dat luon', 'lam luon'
];
$shortReply = trim(preg_replace('/[^a-z0-9 ]/', '', $checkReservationMessage));

if (in_array($shortReply, $affirmativeReplies, true) && $chatHistory->isNotEmpty()) {

    $lastAnswer = Str::lower(Str::ascii((string) $chatHistory->last()->answer));

    // Chỉ tự chuyển khi câu trước gợi ý đúng MỘT việc; nhiều lựa chọn thì để AI hỏi lại
    $suggested = array_keys(array_filter([
        'booking' => str_contains($lastAnswer, 'dat lich'),
        'check_reservation' => str_contains($lastAnswer, 'xem lich') || str_contains($lastAnswer, 'kiem tra lich') || str_contains($lastAnswer, 'lich hen da dat'),
        'cancel_reservation' => str_contains($lastAnswer, 'huy lich'),
        'check_order' => str_contains($lastAnswer, 'xem don') || str_contains($lastAnswer, 'kiem tra don'),
    ]));

    if (count($suggested) === 1) {

        $action = $suggested[0];

        if ($action === 'booking') {
            ChatHistory::create([
                'user_id' => Auth::id(),
                'conversation_id' => $request->conversation_id,
                'question' => $request->message,
                'answer' => 'Mở chức năng đặt lịch khám.',
            ]);
        }

        return response()->json([
            'status' => true,
            'answer' => 'Dạ được ạ.',
            'booking' => $action === 'booking',
            'purchase' => false,
            'cancel_order' => false,
            'check_reservation' => $action === 'check_reservation',
            'cancel_reservation' => $action === 'cancel_reservation',
            'check_order' => $action === 'check_order'
        ]);
    }
}

// Khách chỉ nhắn số điện thoại -> tra cứu lịch khám theo số đó
$phoneOnly = $this->normalizePhone($request->message);
if ($phoneOnly) {
    return response()->json([
        'status' => true,
        'answer' => 'Dạ, em kiểm tra lịch khám theo số điện thoại ' . $phoneOnly . '.',
        'booking' => false,
        'purchase' => false,
        'cancel_order' => false,
        'cancel_reservation' => false,
        'check_reservation' => true,
        'phone' => $phoneOnly
    ]);
}

// Câu hỏi về đặt lịch mới / lịch làm việc bác sĩ không phải là xem lịch đã đặt
$isAboutReservationLookup = !$this->looseContains($checkReservationMessage, 'dat lich')
    && !str_contains($checkReservationMessage, 'lam viec');

$checkReservationKeywords = [
    'xem lich',
    'lich kham cua toi',
    'kiem tra lich',
    'tra cuu lich',
    'tra lich',
    'toi co lich kham nao',
    'lich kham sap toi',
    'lich hen',
    'lich da dat',
    'lich dat truoc'
];

$cancelReservationKeywords = [
    'huy lich',
    'huy hen',
    'huy lich kham',
    'huy lich hen'
];

// Hủy lịch kiểm tra trước (vì "hủy lịch hẹn" cũng chứa "lịch hẹn")
foreach ($cancelReservationKeywords as $keyword) {
    if ($this->looseContains($checkReservationMessage, $keyword)) {

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

foreach ($checkReservationKeywords as $keyword) {
    if ($isAboutReservationLookup && $this->looseContains($checkReservationMessage, $keyword)) {
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

    if ($this->looseContains($cancelMessage, $keyword)) {

        return response()->json([
            'status' => true,
            'answer' => 'Dạ được ạ. Bạn vui lòng cho biết mã đơn hàng cần hủy.',
            'booking' => false,
            'purchase' => false,
            'cancel_order' => true
        ]);
    }
}

// ==========================================
// KHÁCH MUỐN XEM ĐƠN HÀNG ĐÃ ĐẶT
// ==========================================
$checkOrderKeywords = [
    'xem don',
    'don hang cua toi',
    'don cua toi',
    'kiem tra don',
    'tra cuu don',
    'don da dat',
    'don hang da dat',
    'da dat hang',
    'lich su don',
    'lich su mua',
    'tinh trang don',
    'trang thai don'
];

foreach ($checkOrderKeywords as $keyword) {
    if ($this->looseContains($cancelMessage, $keyword)) {
        return response()->json([
            'status' => true,
            'answer' => 'Dạ được ạ. Em sẽ kiểm tra đơn hàng của bạn.',
            'booking' => false,
            'purchase' => false,
            'cancel_order' => false,
            'check_order' => true
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
            // So khớp không dấu + cho phép gõ sai nhẹ (đăt lich, datj lichj, đặt lịh...)
            if ($this->looseContains($request->message, $keyword)) {
            
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
    'mua san pham',
    'toi mua',
    'minh mua',
    'em mua',
    'can mua',
    'cho mua',
    'dat hang',
    'mua hang'
];

$wantToBuy = false;

foreach ($buyKeywords as $keyword) {
    // Cụm ngắn (vd "toi mua", "em mua") phải khớp đúng để tránh nhận nhầm ("mưa" -> "mua");
    // cụm dài từ 8 ký tự trở lên cho phép gõ sai nhẹ (muaa hang, datt hang...)
    $matched = strlen($keyword) >= 8
        ? $this->looseContains($normalMessage, $keyword)
        : str_contains($normalMessage, $keyword);

    if ($matched) {
        $wantToBuy = true;
        break;
    }
}

// Có chữ "mua" kèm đúng tên sản phẩm đang bán (vd: "mua kem đánh răng", "lấy 2 kem đánh răng mua") -> mua hàng
if (!$wantToBuy && preg_match('/\bmua\b/', $normalMessage)) {
    $wantToBuy = Product::where('active', 1)
        ->where('is_deleted', 0)
        ->where('quantity', '>', 0)
        ->pluck('name')
        ->contains(function ($name) use ($normalMessage) {
            return str_contains($normalMessage, Str::lower(Str::ascii($name)));
        });
}

if ($wantToBuy) {

    $productsForBuy = Product::where('active', 1)
        ->where('is_deleted', 0)
        ->where('quantity', '>', 0)
        ->get();

    // ==========================================
    // CHƯA ĐĂNG NHẬP: YÊU CẦU ĐĂNG NHẬP NGAY,
    // KHÔNG ĐỂ KHÁCH ĐIỀN HẾT THÔNG TIN MỚI BÁO
    // ==========================================
    if (!Auth::check()) {

        $matchedProduct = $productsForBuy->first(function ($product) use ($normalMessage) {
            return str_contains($normalMessage, Str::lower(Str::ascii($product->name)));
        });

        return response()->json([
            'status' => true,
            'answer' => $matchedProduct
                ? 'Dạ, sản phẩm "' . $matchedProduct->name . '" hiện đang có trên hệ thống. Bạn vui lòng đăng nhập để đặt mua sản phẩm nhé.'
                : 'Dạ, bạn vui lòng đăng nhập để đặt mua sản phẩm nhé. Bạn vẫn có thể hỏi em thông tin, giá sản phẩm mà không cần đăng nhập.',
            'booking' => false,
            'purchase' => false,
            'login_required' => true,
            'login_url' => route('screen_login'),
            'product_url' => $matchedProduct
                ? route('detail_product', ['id' => $matchedProduct->id])
                : null
        ]);
    }

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

    // Gợi ý vài sản phẩm đang bán để khách chọn / gõ lại đúng tên
    $productNames = $productsForBuy->pluck('name')->take(5)->implode(', ');
    $productHint = $productNames !== ''
        ? "\n\nHiện Nha Khoa NA đang bán: " . $productNames . '.'
        : '';

    // Bỏ các từ "chung chung" (anh, muốn, mua, hàng...) - còn lại rỗng nghĩa là khách chưa nêu sản phẩm nào
    $fillerWords = [
        'anh', 'chi', 'em', 'toi', 'minh', 'ban', 'muon', 'mun', 'mua', 'hang', 'san', 'pham',
        'dat', 'can', 'cho', 'ngay', 'nhe', 'a', 'oi', 'xin', 'vui', 'long', 'di', 'duoc',
        'khong', 'voi', 'cai', 'mot', 'nha', 'khoa', 'na',
    ];
    $leftoverWords = array_filter(
        explode(' ', $this->normalizeForMatch($request->message)),
        function ($word) use ($fillerWords) {
            foreach ($fillerWords as $filler) {
                $allowed = strlen($filler) >= 3 ? 1 : 0;
                if ($this->wordDistance($word, $filler) <= $allowed) {
                    return false;
                }
            }
            return true;
        }
    );

    if (in_array(trim($normalMessage), $generalBuyMessages) || empty($leftoverWords)) {

        return response()->json([
            'status' => true,

            'answer' =>
                'Dạ, bạn muốn mua sản phẩm nào ạ? Bạn có thể hỏi em danh sách sản phẩm Nha Khoa NA đang bán hoặc nhập tên sản phẩm cụ thể.'
                . $productHint,

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
            'Dạ, em chưa tìm thấy sản phẩm bạn yêu cầu trong hệ thống. Bạn có thể hỏi em danh sách sản phẩm Nha Khoa NA đang bán để lựa chọn nhé.'
            . $productHint,

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
                'price',
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

                $serviceContext .= $service->price !== null
                    ? " | Giá: " . number_format($service->price, 0, ',', '.') . " VNĐ"
                    : " | Giá: chưa cập nhật (liên hệ nha khoa)";

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

        $doctors = Doctor::with(['levelDoctor', 'schedules'])
            ->where('active', 1)
            ->select(
                'id',
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

                if ($doctor->schedules->isNotEmpty()) {
                    $doctorContext .= " | Lịch làm việc: " . $doctor->schedules->map(function ($schedule) {
                        return DoctorSchedule::DAYS[$schedule->day_of_week] . ' '
                            . substr($schedule->start_time, 0, 5) . '-'
                            . substr($schedule->end_time, 0, 5);
                    })->implode(', ');
                } else {
                    $doctorContext .= " | Lịch làm việc: 08:00-20:00 tất cả các ngày";
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

        // Hội thoại nhiều lượt: gửi kèm các lượt hỏi - đáp trước để AI hiểu ngữ cảnh ("có", "cái đó"...)
        $contents = [];
        foreach ($chatHistory as $turn) {
            if (empty($turn->question) || empty($turn->answer)) {
                continue;
            }
            $contents[] = ['role' => 'user', 'parts' => [['text' => Str::limit($turn->question, 1000)]]];
            $contents[] = ['role' => 'model', 'parts' => [['text' => Str::limit(strip_tags($turn->answer), 2000)]]];
        }
        $contents[] = ['role' => 'user', 'parts' => [['text' => $request->message]]];

        $systemPrompt = 'Bạn là trợ lý AI của Nha Khoa NA.

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

                                        - Khi khách hỏi giá hoặc bảng giá dịch vụ,
                                          chỉ dùng giá trong dữ liệu dịch vụ bên dưới
                                          (có thể trình bày dạng danh sách).
                                          Dịch vụ ghi "chưa cập nhật" thì nói khách liên hệ nha khoa,
                                          không tự bịa giá.

                                        - Khách chưa đăng nhập vẫn có thể đặt, xem và hủy lịch khám ngay trong chatbox
                                          (xem/hủy lịch bằng số điện thoại đã dùng khi đặt).
                                          Khi khách muốn đặt lịch, hướng dẫn họ nhắn "đặt lịch".
                                          Khi khách muốn xem hoặc hủy lịch đã đặt, hướng dẫn họ nhắn "xem lịch khám"
                                          hoặc "hủy lịch", hoặc nhắn trực tiếp số điện thoại đã dùng khi đặt.
                                          Bạn không tự tra cứu được lịch, không tự hỏi số điện thoại
                                          và không được nói đã nhận số điện thoại.

                                        - Khi khách muốn MUA sản phẩm, hướng dẫn họ nhắn "mua" kèm tên sản phẩm
                                          (vd: "mua Kem đánh răng"), chatbox sẽ hỏi số lượng và thông tin giao hàng
                                          (cần đăng nhập). Tuyệt đối không bảo khách nhắn "xem đơn hàng" để mua.

                                        - Khi khách muốn xem đơn hàng ĐÃ đặt, hướng dẫn họ nhắn "xem đơn hàng"
                                          (cần đăng nhập). Muốn hủy đơn thì nhắn "hủy đơn hàng".
                                          Bạn không tự tra cứu được đơn hàng.

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
                                        . $productContext;

        $payload = [
            'systemInstruction' => ['parts' => [['text' => $systemPrompt]]],
            'contents' => $contents,
            'generationConfig' => [
                'maxOutputTokens' => 2048,
                // Chatbox cần trả lời nhanh: Gemini 3.5 Flash mặc định suy nghĩ mức "medium" nên rất chậm.
                // Đổi trong .env: GEMINI_THINKING=MINIMAL | LOW | MEDIUM (để trống = dùng mặc định của Google)
                'thinkingConfig' => ['thinkingLevel' => strtoupper(env('GEMINI_THINKING', 'MINIMAL'))],
            ],
        ];

        // Danh sách model, có thể đổi trong .env: GEMINI_MODELS=gemini-3.5-flash,gemini-3.5-flash-lite
        $models = array_values(array_filter(array_map(
            'trim',
            explode(',', env('GEMINI_MODELS', 'gemini-3.5-flash,gemini-3.5-flash-lite'))
        )));

        $apiKey = trim((string) env('GEMINI_API_KEY'));

        foreach ($models as $model) {

            // Mỗi model thử tối đa 2 lần (chỉ thử lại với lỗi tạm thời)
            for ($attempt = 1; $attempt <= 2; $attempt++) {

                try {
                    $response = Http::withHeaders([
                        'x-goog-api-key' => $apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->timeout(12)
                    ->post(
                        'https://generativelanguage.googleapis.com/v1beta/models/'
                        . $model . ':generateContent',
                        $payload
                    );
                } catch (\Exception $e) {
                    // Timeout / mất kết nối: model đang chậm nên KHÔNG thử lại cùng model, sang model khác ngay
                    \Log::warning('Gemini connection error', [
                        'model' => $model,
                        'attempt' => $attempt,
                        'error' => $e->getMessage(),
                    ]);
                    break;
                }

                // ---- Thành công ----
                if ($response->successful()) {

                    $data = $response->json();

                    // Ghép tất cả phần text (bỏ phần "thought" nếu có)
                    $answer = '';
                    foreach (($data['candidates'][0]['content']['parts'] ?? []) as $part) {
                        if (!empty($part['text']) && empty($part['thought'])) {
                            $answer .= $part['text'];
                        }
                    }
                    $answer = trim($answer);

                    if ($answer === '') {
                        // Model trả về rỗng (bị chặn / hết token) -> thử model khác
                        \Log::warning('Gemini empty answer', [
                            'model' => $model,
                            'finishReason' => $data['candidates'][0]['finishReason'] ?? null,
                            'promptFeedback' => $data['promptFeedback'] ?? null,
                        ]);
                        break;
                    }

                    ChatHistory::create([
                        'user_id' => Auth::id(),
                        'conversation_id' => $request->conversation_id,
                        'question' => $request->message,
                        'answer' => $answer,
                    ]);

                    return response()->json([
                        'status' => true,
                        'answer' => $answer,
                        'booking' => false
                    ]);
                }

                $status = $response->status();

                // Luôn ghi log MỌI lỗi (trước đây 503 không được ghi nên không biết nguyên nhân)
                \Log::error('Gemini API error', [
                    'model' => $model,
                    'attempt' => $attempt,
                    'status' => $status,
                    'body' => Str::limit($response->body(), 800),
                ]);

                // 429 = hết quota (thường là hạn mức NGÀY): thử lại vô ích -> sang model khác ngay
                // 400/401/403/404 = lỗi cấu hình (key sai, tên model sai...) -> sang model khác
                if ($status == 400 && isset($payload['generationConfig']['thinkingConfig'])) {
                    // Model không nhận thinkingConfig -> bỏ đi và thử lại chính model này
                    unset($payload['generationConfig']['thinkingConfig']);
                    continue;
                }

                if ($status == 429 || ($status >= 400 && $status < 500)) {
                    break;
                }

                // 500/502/503/504 = quá tải tạm thời -> thử lại 1 lần rồi sang model khác
                if ($attempt < 2) {
                    usleep(800000);
                    continue;
                }
            }
        }

        // Tất cả model đều thất bại
        return response()->json([
            'status' => false,
            'answer' => 'Trợ lý AI hiện đang bận. Vui lòng thử lại sau ít phút, '
                . 'hoặc nhắn "đặt lịch" để đặt lịch khám ngay.',
            'booking' => false
        ]);
    }

    /**
     * Chuẩn hóa để so khớp: không dấu, chữ thường, chỉ còn chữ/số cách nhau 1 dấu cách.
     */
    private function normalizeForMatch($text)
    {
        $text = Str::lower(Str::ascii((string) $text));

        return trim(preg_replace('/[^a-z0-9]+/', ' ', $text));
    }

    /**
     * Khoảng cách giữa 2 từ; gõ đảo 2 chữ cạnh nhau (lcih thay vì lich) chỉ tính là 1 lỗi.
     */
    private function wordDistance($a, $b)
    {
        $distance = levenshtein($a, $b);

        if ($distance === 2 && strlen($a) === strlen($b)) {
            $len = strlen($a);
            for ($i = 0; $i < $len - 1; $i++) {
                if ($a[$i] !== $b[$i]) {
                    if ($a[$i] === $b[$i + 1] && $a[$i + 1] === $b[$i] && substr($a, $i + 2) === substr($b, $i + 2)) {
                        return 1;
                    }
                    break;
                }
            }
        }

        return $distance;
    }

    /**
     * Tin nhắn có chứa cụm $phrase không? Cho phép gõ sai nhẹ:
     *  - bỏ dấu, hoa/thường, dấu câu không ảnh hưởng
     *  - mỗi từ (từ 3 ký tự trở lên) được sai tối đa 1 ký tự, cả cụm sai tối đa 2 ký tự
     *    (vd: "dat lich" khớp "datj lichj", "đặt lịh", "dat lichh"; nhưng "huy don" KHÔNG khớp "huy hen")
     *  - gõ dính liền: "datlich"
     */
    private function looseContains($text, $phrase)
    {
        $text = $this->normalizeForMatch($text);
        $phrase = $this->normalizeForMatch($phrase);

        if ($text === '' || $phrase === '') {
            return false;
        }

        if (str_contains($text, $phrase)) {
            return true;
        }

        $words = array_values(array_filter(
            explode(' ', $text),
            function ($w) {
                return strlen($w) <= 40; // bỏ qua chuỗi dài bất thường
            }
        ));
        $phraseWords = explode(' ', $phrase);
        $n = count($phraseWords);

        // Gõ dính liền: "datlich"
        if ($n > 1) {
            $joined = str_replace(' ', '', $phrase);
            if (strlen($joined) >= 6) {
                foreach ($words as $w) {
                    if (abs(strlen($w) - strlen($joined)) <= 1 && $this->wordDistance($w, $joined) <= 1) {
                        return true;
                    }
                }
            }
        }

        for ($i = 0; $i + $n <= count($words); $i++) {

            $total = 0;
            $ok = true;

            for ($j = 0; $j < $n; $j++) {
                $distance = $this->wordDistance($words[$i + $j], $phraseWords[$j]);
                $allowed = strlen($phraseWords[$j]) >= 3 ? 1 : 0;

                if ($distance > $allowed) {
                    $ok = false;
                    break;
                }

                $total += $distance;
            }

            if ($ok && $total <= 2) {
                return true;
            }
        }

        return false;
    }

    /**
     * Số điện thoại Việt Nam: đúng 10 chữ số, bắt đầu bằng 0
     * (cho phép khoảng trắng, dấu chấm, gạch ngang giữa các số).
     *
     * @return string|null số đã chuẩn hóa hoặc null nếu sai
     */
    private function normalizePhone($phone)
    {
        $phone = trim((string) $phone);
        if (!preg_match('/^[0-9 .\-]+$/', $phone)) {
            return null;
        }
        $digits = preg_replace('/\D/', '', $phone);
        return preg_match('/^0\d{9}$/', $digits) ? $digits : null;
    }

    /**
     * Kiểm tra email ngay ở bước nhập của chatbox.
     */
    public function validateChatEmail(Request $request)
    {
        $error = RealEmail::check($request->email);

        return response()->json([
            'status' => $error === null,
            'message' => $error
        ]);
    }

    public function createChatOrder(Request $request)
{
    // Trả JSON thay vì redirect khi dữ liệu sai (fetch không gửi Accept: application/json)
    $validator = Validator::make($request->all(), [
        'product_id' => 'required|integer',
        'quantity'   => 'required|integer|min:1',
        'name'       => 'required|string|max:255',
        'phone'      => 'required|string|max:20',
        'email'      => ['required', 'max:255', new RealEmail()],
        'address'    => 'required|string|max:500',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors()->first()
        ], 422);
    }

    $phone = $this->normalizePhone($request->phone);
    if (!$phone) {
        return response()->json([
            'status' => false,
            'message' => 'Số điện thoại phải gồm đúng 10 chữ số, bắt đầu bằng 0.'
        ], 422);
    }
    $request->merge(['phone' => $phone, 'email' => trim($request->email)]);

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

    \Log::error('Chat order error: ' . $e->getMessage());

    return response()->json([
        'status' => false,
        'message' => 'Không thể tạo đơn hàng. Vui lòng thử lại.'
    ], 500);
}
}
public function createChatReservation(Request $request)
{
    // Khách chưa đăng nhập vẫn được đặt lịch (lưu theo họ tên + số điện thoại)
    $services = Service::where('active', 1)->get();
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
    $request->validate([
        'service_id' => 'required|integer',
        'doctor_id' => 'required|integer',
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'date' => 'required|date',
        'time' => 'required',
    ]);

    $phone = $this->normalizePhone($request->phone);
    if (!$phone) {
        return response()->json([
            'status' => false,
            'message' => 'Số điện thoại phải gồm đúng 10 chữ số, bắt đầu bằng 0.'
        ], 422);
    }
    $request->merge(['phone' => $phone]);

    $reservationModel = new Reservation();

    $response = $reservationModel->createReservation($request);

    return response()->json([
        'status' => $response['status'],
        'message' => $response['message']
    ]);
}

public function getChatDoctors(Request $request)
{
    $doctors = Doctor::with('schedules')->where('active', 1)->get()
        ->map(function ($doctor) {
            // Ngày làm việc trong tuần (1 = Thứ 2 ... 7 = Chủ nhật), null = chưa cài lịch (làm mọi ngày)
            $doctor->working_days = $doctor->schedules->isEmpty()
                ? null
                : $doctor->schedules->pluck('day_of_week')->map(fn ($day) => (int) $day)->values();
            unset($doctor->schedules);
            return $doctor;
        });

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

    $reservationModel = new Reservation();

    if ($reservationModel->getDoctorWorkingHours($request->doctor_id, $request->date) === false) {
        return response()->json([
            'status' => true,
            'free_times' => [],
            'message' => 'Bác sĩ không làm việc vào ngày này.'
        ]);
    }

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
/**
 * Danh sách đơn hàng gần đây của tài khoản (xem đơn trong chatbox).
 */
public function getChatOrders(Request $request)
{
    if (!Auth::check()) {
        return response()->json([
            'status' => false,
            'login_required' => true,
            'login_url' => route('screen_login'),
            'message' => 'Bạn vui lòng đăng nhập để xem đơn hàng đã đặt.'
        ]);
    }

    $orders = InvoiceExport::with('detailInvoiceExport.product')
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'DESC')
        ->limit(10)
        ->get()
        ->map(function ($order) {
            return [
                'code_invoice' => $order->code_invoice,
                'created_at' => optional($order->created_at)->format('d/m/Y H:i'),
                'status_ship' => $order->status_ship,
                'need_pay' => (int) $order->need_pay,
                'is_pay_cod' => (bool) $order->is_pay_cod,
                'via_chat' => $order->message === 'Đơn hàng được đặt qua Chatbox AI',
                'can_cancel' => $order->status_ship === Lang::get('message.received'),
                'items' => $order->detailInvoiceExport->map(function ($detail) {
                    return [
                        'name' => optional($detail->product)->name ?? 'Sản phẩm đã xóa',
                        'quantity' => (int) $detail->quantity,
                    ];
                })->values(),
            ];
        });

    if ($orders->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'Bạn chưa có đơn hàng nào.'
        ]);
    }

    return response()->json([
        'status' => true,
        'orders' => $orders
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

/**
 * Giới hạn lịch khám theo người hỏi:
 * - Đã đăng nhập: lịch của tài khoản.
 * - Chưa đăng nhập: lịch đặt không cần tài khoản, khớp số điện thoại.
 *
 * @return \Illuminate\Database\Eloquent\Builder|null null nếu khách chưa nhập số điện thoại
 */
private function reservationOwnerQuery(Request $request)
{
    if (Auth::check()) {
        return Reservation::where('user_id', Auth::id());
    }

    $phone = preg_replace('/\D/', '', (string) $request->phone);
    if (strlen($phone) === 11 && str_starts_with($phone, '84')) {
        $phone = '0' . substr($phone, 2);
    }
    if (strlen($phone) < 9) {
        return null;
    }

    return Reservation::whereNull('user_id')
        ->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(phone, ' ', ''), '.', ''), '-', ''), '+84', '0') = ?", [$phone]);
}

public function checkChatReservation(Request $request)
{
    $query = $this->reservationOwnerQuery($request);

    if (!$query) {
        return response()->json([
            'status' => false,
            'need_phone' => true,
            'message' => 'Vui lòng nhập số điện thoại bạn đã dùng khi đặt lịch.'
        ]);
    }

    $reservations = $query->with(['doctor', 'service'])
        ->whereIn('status', [0, 1])
        ->whereDate('date', '>=', Carbon::today())
        ->orderBy('date', 'ASC')
        ->orderBy('time', 'ASC')
        ->get();

    if ($reservations->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => Auth::check()
                ? 'Bạn hiện không có lịch khám sắp tới nào.'
                : 'Không tìm thấy lịch khám sắp tới nào với số điện thoại này.'
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

    // Chỉ tìm lịch thuộc đúng tài khoản đang đăng nhập / đúng số điện thoại của khách
    $query = $this->reservationOwnerQuery($request);

    $reservation = $query
        ? $query->where('id', $request->reservation_id)->first()
        : null;

    if (!$reservation) {
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy lịch khám này.'
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
    // Lưu cả hội thoại của khách chưa đăng nhập (user_id = null) để AI hiểu ngữ cảnh
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
