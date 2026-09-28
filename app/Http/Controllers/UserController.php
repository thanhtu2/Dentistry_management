<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Comment;
use App\Models\InvoiceExport;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Lang;

class UserController extends Controller
{
    use ValidateTraits, ResponseTraits;

    private $modelProduct;
    private $modelCategory;
    private $modelBrand;
    private $modelInvoiceExport;
    private $modelComment;
    private $modelUser;
    private $modelReservation;
    private $modelDoctor;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->modelProduct = new Product();
        $this->modelCategory = new Category();
        $this->modelBrand = new Brand();
        $this->modelInvoiceExport = new InvoiceExport();
        $this->modelComment = new Comment();
        $this->modelUser = new User();
        $this->modelReservation = new Reservation();
        $this->modelDoctor = new Doctor();
    }

    /**
     * Search product with scope
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function searchProducts(Request $request)
    {
        $response = $this->modelProduct->searchProducts($request);
        $products = $response['data'];
        $response = $this->modelProduct->searchProductsRelate();
        $productsRelate = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }

        $response = $this->modelCategory->getCategories();
        $categories = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }

        $response = $this->modelBrand->getBrands();
        $brands = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }

        $services = Service::where('active', true)->get();
        $doctors = Doctor::where('active', true)->get();

        $categories = Category::all();
        return view('user.product.search', compact('products', 'request', 'categories', 'brands', 'services', 'doctors', 'categories', 'productsRelate'));
    }

    /**
     * Search category with scope
     *
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function searchCategories(Request $request)
    {
        $response = $this->modelCategory->getCategories();
        $categories = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }

        $response = $this->modelCategory->getNameCategory($request);
        $category = $response['data'];

        $response = $this->modelBrand->getBrands();
        $brands = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        $services = Service::where('active', true)->get();
        $doctors = Doctor::where('active', true)->get();
        $categories = Category::all();
        return view('user.product.category', compact('categories', 'brands', 'category', 'services', 'doctors', 'categories'));
    }

    /**
     * Search brand with scope
     *
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function searchBrands(Request $request)
    {
        $response = $this->modelCategory->getCategories();
        $categories = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }

        $response = $this->modelProduct->searchProducts($request);
        $product = $response['data'];
        $response = $this->modelBrand->getNameBrand($request);
        $brands = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        $services = Service::where('active', true)->get();
        $doctors = Doctor::where('active', true)->get();
        $categories = Category::all();
        return view('user.product.brand', compact('categories', 'brands', 'product', 'services', 'doctors', 'categories'));
    }

    /**
     * Init screen detail product
     *
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function detailProduct($id)
    {
        $response = $this->modelProduct->getProduct($id);
        $product = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return redirect(route('search_products'))->with('message', $message);
        }

        $response = $this->modelComment->getComments($id);
        $comments = $response['data'];
        $message = $response['message'];
        if (!$response['status']){
            return back()->with('message', $message);
        }
        $brands = Brand::all();
        $categories = Category::all();
        $services = Service::where('active', true)->get();
        $doctors = Doctor::where('active', true)->get();
        $categories = Category::all();
        return view('user.product.detail', compact('product', 'comments', 'brands', 'categories', 'services', 'doctors', 'categories'));
    }

    /**
     * Add product to cart
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function addCart(Request $request, $id)
    {
        $response = $this->modelProduct->addProductToCart($request, $id);
        $message = $response['message'];
        return back()->with('message', $message);
    }

    /**
     * Buy product now
     *
     * @param $id
     * @return RedirectResponse
     */
    public function buyProduct(Request $request, $id)
    {
        $response = $this->modelProduct->buyProduct($request, $id);
        $message = $response['message'];
        if (!$response['status']){
            return back()->with('message', $message);
        }
        return redirect(route('cart'));
    }

    /**
     * Screen cart
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function detailCart()
    {
        $response = $this->modelProduct->getProducts();
        $products = $response['data'];
        $user = null;
        if (Auth::user() && Auth::user()->role->name === Config::get('auth.roles.user')){
            $user = User::find(Auth::id());
        }
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        if (!Cart::count()){
            return redirect(route('screen_home'));
        }
        $brands = Brand::all();
        $categories = Category::all();
        $services = Service::where('active', true)->get();
        $doctors = Doctor::where('active', true)->get();
        $categories = Category::all();
        return view('user.cart.detail', compact('products', 'message', 'user', 'brands', 'categories', 'services', 'doctors', 'categories'));
    }


    /**
     * Delete cart
     *
     * @return RedirectResponse
     */
    public function updateCart(Request $request)
    {
        $response = $this->modelProduct->updateProductInCart($request);
        $message = $response['message'];
        if (!$response['status']) {
            $message = $response['message'];
        }
        return redirect(route('cart'))->with('message', $message);
    }

    /**
     * Delete cart
     *
     * @param $id
     * @return RedirectResponse
     */
    public function deleteCart($id)
    {
        $response = $this->modelProduct->deleteProductInCart($id);
        $message = $response['message'];
        if (!$response['status']) {
            $message = $response['message'];
        }
        return redirect(route('cart'))->with('message', $message);
    }

    /**
     * Create order
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function createOrder(Request $request)
    {
        $response = $this->modelInvoiceExport->createOrder($request);
        $message = $response['message'];
        if (!$response['status']) {
            $message = $response['message'];
        } else {
            $order = $response['data'];
            $data = array("name" => $order->name_user, "code" => $order->code_invoice, "email" => $order->email_user);

            Mail::send('mail.mail_confirm_order', $data, function ($message) use ($data) {
                $message->to($data['email'])->subject(Lang::get('message.confirm_order'));
            });
            Cart::destroy();
        }
        return redirect(route('search_products'))->with('message', $message);
    }

    /**
     * Find order
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function searchOrder(Request $request)
    {
        $response = $this->modelInvoiceExport->searchOrder($request);
        $message = $response['message'];
        $order = $response['data'];
        if (!$response['status']) {
            $message = $response['message'];
        }
        $brands = Brand::all();
        $categories = Category::all();
        $services = Service::where('active', true)->get();
        $doctors = Doctor::where('active', true)->get();
        $categories = Category::all();
        return view('user.order.search', compact('message', 'order', 'brands', 'categories', 'services', 'doctors', 'categories'));
    }

    /**
     * Find order
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function historyOrder()
    {
        $response = $this->modelInvoiceExport->historyOrder();
        $message = $response['message'];
        $orders = $response['data'];
        if (!$response['status']) {
            $message = $response['message'];
        }
        $brands = Brand::all();
        $categories = Category::all();
        $services = Service::where('active', true)->get();
        $doctors = Doctor::where('active', true)->get();
        $categories = Category::all();
        return view('user.cart.history', compact('message', 'orders', 'brands', 'categories', 'services', 'doctors', 'categories'));
    }
    public function historyReservation()
{
    $reservations = \App\Models\Reservation::with(['doctor', 'service'])
        ->where('user_id', auth()->id())
        ->orderBy('date', 'desc')
        ->orderBy('time', 'desc')
        ->get();

    $brands = Brand::all();
    $categories = Category::all();
    $services = Service::where('active', true)->get();
    $doctors = Doctor::where('active', true)->get();

    return view(
        'user.reservation.history',
        compact('reservations', 'brands', 'categories', 'services', 'doctors')
    );
}

    /**
     * Find order
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function detailOrder($id)
    {
        $response = $this->modelInvoiceExport->detailOrder($id);
        $message = $response['message'];
        $details = $response['data'];
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('screen_home'));
        }

        $response = $this->modelInvoiceExport->getCodeInvoiceExport($id);
        $message = $response['message'];
        $order = $response['data'];
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('screen_home'));
        }
        $brands = Brand::all();
        $categories = Category::all();
        $services = Service::where('active', true)->get();
        $doctors = Doctor::where('active', true)->get();
        $categories = Category::all();
        return view('user.cart.detail_history', compact('message', 'details', 'brands', 'categories', 'order','services', 'doctors', 'categories'));
    }

    /**
     * Add comment
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function addComment(Request $request, $id)
    {
        $this->modelComment->addComments($request, $id);
        return back();
    }

    public function reservation(Request $request) {
        $response = $this->modelUser->getUserByPhone($request);
        $message = $response['message'];
        $user = $response['data'];
        $phone = $request->phone;
        $dataDoctor = $this->modelDoctor->getDoctors();
        $doctors = $dataDoctor['data'];
        if (!$response['status']) {
            $message = $response['message'];
        }
        $services = Service::where('active', true)->get();
        $doctors = Doctor::where('active', true)->get();
        $categories = Category::all();
        return view('user.reservation.reservation', compact('message', 'user', 'phone', 'doctors', 'services', 'categories'));
    }

    public function createReservation(Request $request) {
        $response = $this->modelReservation->createReservation($request);
        $message = $response['message'];
        $user = $response['data'];
        if (!$response['status']) {
            $message = $response['message'];
        }
        return back()->with('message', $message);
    }
}
