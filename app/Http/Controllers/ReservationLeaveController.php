<?php

namespace App\Http\Controllers;

use App\Exceptions\RoleAdminException;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Doctor;
use App\Models\ReservationLeave;
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

class ReservationLeaveController extends Controller
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
        $this->model = new ReservationLeave();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $this->checkRoleDoctor();
        $response = $this->model->getReservationLeave($request);
        if (!$response['status']) {
            $message = $response['message'];
            return back()->with('message', $message);
        }
        $reservationLeave = $response["data"];
        return view('admin.reservation_leave.reservation_leave', compact('reservationLeave'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->checkRoleDoctor();
        return view('admin.reservation_leave.reservation_leave_add');
    }

    /**
     * Store a newly created resource i n storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $this->checkRoleDoctor();
            $response['data'] = [];
            $response['message'] = null;
            $response['status'] = true;
            $response = $this->model->addReservationLeave($request);
            $message = $response["message"];
            if ($response['status']) {
                return redirect(route('admin.reservation_leave.index'))->with('message', $message)->with('conflictReservation', $response['data']['conflictReservation']);
            } else {
                if($response["data"] != null){
                    return redirect(route('admin.reservation_leave.edit', ['doctor_reservation' => $response["data"]["id"]]))->with('message', $message);
                } else {
                    return back()->with('message', $message);
                }
            }
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
        $this->checkRoleDoctor();
        $response = $this->model->getReservationLeaveById($id);
        if (!$response['status']) {
            $message = $response['message'];
            return back()->with('message', $message);
        }
        $reservationLeave = $response['data']['data'];
        $conflictingReservations = $response['data']['conflictingReservations'];
        return view('admin.reservation_leave.reservation_leave_edit', compact('reservationLeave', 'conflictingReservations'));
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
            if ($this->checkRoleDoctor()) {
                $response = $this->model->updateReservationLeave($request, $id);
                $message = $response['message'];
                if ($response['data']['data'] != null && isset($response['data']['data']['status']) && !$response['data']['data']['status']) {
                    return back()->with('message', $message);
                } else {
                    return redirect(route('admin.reservation_leave.index'))->with('message', $message);
                }
            } else {
                throw new RoleAdminException();
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $this->checkRoleDoctor();
        $this->model->destroy($id);
        return back();
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
