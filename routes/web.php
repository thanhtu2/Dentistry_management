<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceImportController;
use App\Http\Controllers\InvoiceExportController;
use App\Http\Controllers\SideBarController;
use App\Http\Controllers\StatisticalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ReservationLeaveController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatHistoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Admin
Route::prefix('admin')->group(function () {

    Route::get(
        '/login',
        [AuthController::class, 'initScreenLoginAdmin']
    )->name('screen_admin_login');

    Route::post(
        '/login',
        [AuthController::class, 'loginAdmin']
    )->name('admin_login');

    Route::get(
        '/forgot-password',
        [AuthController::class, 'initScreenForgotPasswordAdmin']
    )->name('screen_admin_forgot_password');

    Route::post(
        '/forgot-password',
        [AuthController::class, 'forgotPasswordAmin']
    )->name('admin_forgot_password');

    Route::get(
        '/reset-password',
        [AuthController::class, 'initScreenUpdatePasswordAdmin']
    )->name('screen_admin_reset_password');

    Route::post(
        '/update-password',
        [AuthController::class, 'updatePasswordAdmin']
    )->name('admin_update_password');


    // Admin Authenticate
    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::get(
            '/home',
            [AuthController::class, 'indexAdmin']
        )->name('screen_admin_home');

        Route::get(
            '/admin/info',
            [AuthController::class, 'initScreenInfoAdmin']
        )->name('screen_admin_info');

        Route::get(
            '/logout',
            [AuthController::class, 'logoutAdmin']
        )->name('admin_logout');


        // Thông tin tài khoản Admin
        Route::get(
            '/info',
            [AuthController::class, 'initScreenInfoAdmin']
        )->name('screen_admin_info');


        // Đổi mật khẩu Admin
        Route::post(
            '/change-password',
            [AuthController::class, 'changePasswordAdmin']
        )->name('admin_change_password');


        // Admin invoice import
        Route::get(
            'invoice-import/pay/{invoice_import}',
            [InvoiceImportController::class, 'pay']
        )->name('admin.invoice_import.pay');

        Route::resource(
            'invoice-import',
            InvoiceImportController::class,
            ['names' => 'admin.invoice_import']
        );


        // Admin invoice export
        Route::get(
            'order',
            [InvoiceExportController::class, 'orders']
        )->name('admin.invoice_export.order');

        Route::get(
            'order/{id}',
            [InvoiceExportController::class, 'order']
        )->name('admin.invoice_export.order_view');

        Route::get(
            'accept-order/{id}',
            [InvoiceExportController::class, 'acceptOrder']
        )->name('admin.invoice_export.accept_order');

        Route::get(
            'cancel-order/{id}',
            [InvoiceExportController::class, 'cancelOrder']
        )->name('admin.invoice_export.cancel_order');

        Route::get(
            'invoice',
            [InvoiceExportController::class, 'invoices']
        )->name('admin.invoice_export.invoice');

        Route::get(
            'invoice{id}',
            [InvoiceExportController::class, 'invoice']
        )->name('admin.invoice_export.invoice_view');

        Route::get(
            'up-status-ship/{id}',
            [InvoiceExportController::class, 'upStatusShip']
        )->name('admin.invoice_export.up_status_ship');

        Route::get(
            'close-order',
            [InvoiceExportController::class, 'closeOrders']
        )->name('admin.invoice_export.close_orders');

        Route::get(
            'close-order/{id}',
            [InvoiceExportController::class, 'closeOrder']
        )->name('admin.invoice_export.close_order');


        // Admin statistical
        Route::get(
            'statistical-products',
            [StatisticalController::class, 'statisticalProduct']
        )->name('admin.statistical.products');

        Route::get(
            'statistical-invoices',
            [StatisticalController::class, 'statisticalInvoice']
        )->name('admin.statistical.invoices');

        Route::get(
            'statistical-users',
            [StatisticalController::class, 'statisticalUser']
        )->name('admin.statistical.users');


        // Admin product
        Route::resource(
            'brand',
            BrandController::class,
            ['names' => 'admin.brand']
        );

        Route::resource(
            'category',
            CategoryController::class,
            ['names' => 'admin.category']
        );

        Route::resource(
            'product',
            ProductController::class,
            ['names' => 'admin.product']
        );

        Route::resource(
            'doctor',
            DoctorController::class,
            ['names' => 'admin.doctor']
        );

        Route::resource(
            'reservation',
            ReservationController::class,
            ['names' => 'admin.reservation']
        );

        Route::resource(
            'service',
            ServiceController::class,
            ['names' => 'admin.service']
        );

        Route::resource(
            'doctor-reservation',
            ReservationLeaveController::class,
            ['names' => 'admin.reservation_leave']
        );


        // Admin account
        Route::resource(
            'account',
            AdminController::class,
            ['names' => 'admin.account']
        );

        Route::resource(
            'sidebar',
            SideBarController::class,
            ['names' => 'admin.sidebar']
        );


        // Doctor control
        Route::get(
            'doctor-reservation-customer',
            [DoctorController::class, 'getReservation']
        )->name('admin.doctor.reservation');

        Route::get(
            'doctor-reservation-confirm/{id}',
            [DoctorController::class, 'reservationConfirm']
        )->name('admin.doctor.reservation.confirm');

        // Quản lý lịch sử Chatbox AI
         Route::get(
             '/chat-history',
             [ChatHistoryController::class, 'index']
        )->name('admin.chat_history');
    });
});


// User

Route::get(
    '/register',
    [AuthController::class, 'initScreenRegister']
)->name('screen_register');

Route::post(
    '/register',
    [AuthController::class, 'register']
)->name('register');

Route::get(
    '/login',
    [AuthController::class, 'initScreenLogin']
)->name('screen_login');

Route::post(
    '/login',
    [AuthController::class, 'login']
)->name('login');

Route::get(
    '/',
    [AuthController::class, 'index']
)->name('screen_home');

Route::get(
    '/forgot-password',
    [AuthController::class, 'initScreenForgotPassword']
)->name('screen_forgot_password');

Route::post(
    '/forgot-password',
    [AuthController::class, 'forgotPassword']
)->name('forgot_password');

Route::get(
    '/reset-password',
    [AuthController::class, 'initScreenUpdatePassword']
)->name('screen_reset_password');

Route::post(
    '/update-password',
    [AuthController::class, 'updatePassword']
)->name('update_password');

Route::get(
    '/search',
    [UserController::class, 'searchProducts']
)->name('search_products');

Route::get(
    '/search-category',
    [UserController::class, 'searchCategories']
)->name('search_categories');

Route::get(
    '/search-brand',
    [UserController::class, 'searchBrands']
)->name('search_brands');

Route::get(
    '/product/{id}',
    [UserController::class, 'detailProduct']
)->name('detail_product');

Route::post(
    '/product/{id}',
    [UserController::class, 'addCart']
)->name('add_cart');

Route::post(
    '/buy-product/{id}',
    [UserController::class, 'buyProduct']
)->name('buy_product');

Route::get(
    '/cart',
    [UserController::class, 'detailCart']
)->name('cart');

Route::post(
    '/update-cart',
    [UserController::class, 'updateCart']
)->name('update_cart');

Route::get(
    '/delete-cart/{id}',
    [UserController::class, 'deleteCart']
)->name('delete_cart');

Route::post(
    '/create-order',
    [UserController::class, 'createOrder']
)->name('create_order');

Route::get(
    '/search-order',
    [UserController::class, 'searchOrder']
)->name('search_order');


// Các route này KHÔNG cần đăng nhập

Route::get(
    '/getFreeTime',
    [ReservationController::class, 'getFreeTime']
)->name('get_freetime');

Route::get(
    '/service-info/{id}',
    [ServiceController::class, 'getInfo']
)->name('service_info');

Route::get(
    '/doctor-info/{id}',
    [DoctorController::class, 'getInfo']
)->name('doctor_info');

Route::post(
    '/ai-chat',
    [ChatController::class, 'chat']
)->name('ai_chat');


// =====================================================
// USER AUTHENTICATE - PHẢI ĐĂNG NHẬP
// =====================================================

Route::group(['middleware' => 'auth:sanctum'], function () {

    // Lưu toàn bộ tương tác Chatbox AI
    Route::post(
        '/ai-chat/save-history',
        [ChatController::class, 'saveChatHistory']
    )->name('ai_chat_save_history');

    // Đặt lịch khám
    Route::get(
        '/reservation',
        [UserController::class, 'reservation']
    )->name('reservation');

    Route::post(
        '/reservation',
        [UserController::class, 'createReservation']
    )->name('create_reservation');

    Route::post(
        '/ai-chat/create-reservation',
        [ChatController::class, 'createChatReservation']
    )->name('ai_chat_create_reservation');

    Route::post(
        '/ai-chat/get-doctors',
        [ChatController::class, 'getChatDoctors']
    )->name('ai_chat_get_doctors');

    Route::post(
        '/ai-chat/get-free-times',
        [ChatController::class, 'getChatFreeTimes']
    )->name('ai_chat_get_free_times');

    Route::post(
        '/ai-chat/confirm-reservation',
        [ChatController::class, 'confirmChatReservation']
    )->name('ai_chat_confirm_reservation');

    Route::post(
        '/ai-chat/check-reservation',
        [ChatController::class, 'checkChatReservation']
    )->name('ai_chat_check_reservation');

    Route::post(
        '/ai-chat/cancel-reservation',
        [ChatController::class, 'cancelChatReservation']
    )->name('ai_chat_cancel_reservation');


    // Bình luận sản phẩm
    Route::post(
        '/comment/{id}',
        [UserController::class, 'addComment']
    )->name('comment');


    // Thông tin cá nhân
    Route::get(
        '/info',
        [AuthController::class, 'initScreenInfo']
    )->name('screen_info');

    Route::post(
        '/update-info',
        [AuthController::class, 'updateInfo']
    )->name('update_info');


    // Đổi mật khẩu
    Route::post(
        '/change-password',
        [AuthController::class, 'changePassword']
    )->name('change_password');


    // Đăng xuất
    Route::get(
        '/logout',
        [AuthController::class, 'logout']
    )->name('logout');


    // Lịch sử đơn hàng
    Route::get(
        '/history-order',
        [UserController::class, 'historyOrder']
    )->name('history_order');


    // Lịch khám của tôi
    Route::get(
        '/history-reservation',
        [UserController::class, 'historyReservation']
    )->name('reservation.history');


    // Chi tiết đơn hàng
    Route::get(
        '/detail-order/{id}',
        [UserController::class, 'detailOrder']
    )->name('detail_order');
});


// Tạo đơn hàng trực tiếp từ Chatbox AI
Route::post(
    '/ai-chat/create-order',
    [ChatController::class, 'createChatOrder']
)->name('ai_chat_create_order');

Route::post(
    '/ai-chat/check-order',
    [ChatController::class, 'checkChatOrder']
)->name('ai_chat_check_order');

Route::post(
    '/ai-chat/cancel-order',
    [ChatController::class, 'cancelChatOrder']
)->name('ai_chat_cancel_order');

