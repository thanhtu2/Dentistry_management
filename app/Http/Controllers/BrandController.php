<?php

namespace App\Http\Controllers;

use App\Exceptions\RoleAdminException;
use App\Models\Brand;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Exception;

class BrandController extends Controller
{
    use ValidateTraits, ResponseTraits;

    private $model;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Brand();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|RedirectResponse
     * @throws RoleAdminException
     */
    public function index()
    {
        $this->checkRoleAdmin();
        $response = $this->model->getBrands();
        $brands = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return view('admin.brand.brands', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     * @throws RoleAdminException
     */
    public function create()
    {
        $this->checkRoleAdmin();
        return view('admin.brand.brand_add');
    }

    /**
     * Store a newly created resource i n storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $this->checkRoleAdmin();
            $this->validateBrand($request);
            $response = $this->model->addBrand($request);
            $message = $response['message'];
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function show($id)
    {
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|RedirectResponse|Redirector
     * @throws RoleAdminException
     */
    public function edit($id)
    {
        $this->checkRoleAdmin();
        $response = $this->model->getBrand($id);
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('admin.brand.index'))->with('message', $message);
        }
        $brand = $response['data'];
        return view('admin.brand.brand_edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        try {
            if ($this->checkRoleAdmin()) {
                $this->validateBrand($request);
                $response = $this->model->updateBrand($request, $id);
                $message = $response['message'];
            } else {
                throw new RoleAdminException();
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return redirect(route('admin.brand.edit', ['brand' => $id]))->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    

    public function destroy($id)
{
    try {
        $this->checkRoleAdmin();

        $brand = Brand::find($id);

        if (!$brand) {
            return back()->with('message', 'Không tìm thấy thương hiệu');
        }

        // Kiểm tra thương hiệu đang được sản phẩm sử dụng không
        $hasProduct = \App\Models\Product::where('brand_id', $id)->exists();

        if ($hasProduct) {
            return back()->with(
                'message',
                'Không thể xóa thương hiệu này vì đang có sản phẩm sử dụng.'
            );
        }

        $brand->delete();

        return back()->with(
            'message',
            'Xóa thương hiệu thành công'
        );

    } catch (Exception $e) {
        return back()->with(
            'message',
            $e->getMessage()
        );
    }
}
    
}
