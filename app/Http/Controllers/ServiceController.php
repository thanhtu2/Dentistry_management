<?php

namespace App\Http\Controllers;

use App\Exceptions\RoleAdminException;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Doctor;
use App\Models\Service;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Exception;

class ServiceController extends Controller
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
        $this->model = new Service();
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
        $response = $this->model->getServices();
        $services = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return view('admin.service.services', compact('services'));
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
        $workTimes = Service::WORKTIME;
        return view('admin.service.service_add', compact('workTimes'));
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
            $this->validateService($request);
            $response = $this->model->addService($request);
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
        $response = $this->model->getService($id);
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('admin.service.index'))->with('message', $message);
        }
        $service = $response['data'];
        $workTimes = Service::WORKTIME;
        return view('admin.service.service_edit', compact('service', 'workTimes'));
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
                $this->validateService($request);
                $response = $this->model->updateService($request, $id);
                $message = $response['message'];
            } else {
                throw new RoleAdminException();
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return redirect(route('admin.service.edit', ['service' => $id]))->with('message', $message);
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

        $service = \App\Models\Service::find($id);

        if (!$service) {
            return back()->with('message', 'Không tìm thấy dịch vụ');
        }

        // Nếu dịch vụ đang được dùng trong lịch hẹn thì không xóa cứng
        $hasReservation = \App\Models\Reservation::where('service_id', $id)->exists();

        if ($hasReservation) {
            // Ẩn dịch vụ để không làm mất lịch sử đặt khám
            $service->active = 0;
            $service->save();

            return back()->with(
                'message',
                'Dịch vụ đang có lịch hẹn nên đã được ẩn thay vì xóa.'
            );
        }

        // Chưa có lịch hẹn thì xóa hẳn
        $service->delete();

        return back()->with(
            'message',
            'Xóa dịch vụ thành công'
        );

    } catch (Exception $e) {
        return back()->with(
            'message',
            $e->getMessage()
        );
    }
}

    public function getInfo($id) {
        $response = $this->model->getService($id);
        $message = $response['message'];
        $serv = $response['data'];
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('screen_home'));
        }
        $brands = Brand::all();
        $categories = Category::all();
        $services = Service::where('active', true)->get();
        $doctors = Doctor::where('active', true)->get();
        $categories = Category::all();
        return view('user.service.info', compact('message', 'serv', 'brands', 'categories', 'services', 'doctors', 'categories'));
    }
}
