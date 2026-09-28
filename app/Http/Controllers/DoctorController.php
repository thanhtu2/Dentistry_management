<?php

namespace App\Http\Controllers;

use App\Exceptions\RoleAdminException;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Doctor;
use App\Models\Level;
use App\Models\Service;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Redirector;

class DoctorController extends Controller
{
    use ValidateTraits, ResponseTraits;

    private $model;
    private $modelLevel;


    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Doctor();
        $this->modelLevel = new Level();
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
        $response = $this->model->getDoctors();
        $doctors = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return view('admin.doctor.doctors', compact('doctors'));
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
        $levels = $this->modelLevel->getLevels();
        return view('admin.doctor.doctor_add')->with('levels', $levels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $this->checkRoleAdmin();
            $this->validateDoctor($request);
            $response = $this->model->addDoctor($request);
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
        $response = $this->model->getDoctor($id);
        $levels = $this->modelLevel->getLevels();
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('admin.doctor.index'))->with('message', $message);
        }
        $doctor = $response['data'];
        return view('admin.doctor.doctor_edit', compact('doctor', 'levels'));
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
                $this->validateDoctor($request);
                $response = $this->model->updateDoctor($request, $id);
                $message = $response['message'];
            } else {
                throw new RoleAdminException();
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return redirect(route('admin.doctor.edit', ['doctor' => $id]))->with('message', $message);
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
            $response = $this->model->deleteDoctor($id);
            $message = $response['message'];
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }

    public function getInfo($id) {
        $response = $this->model->getDoctor($id);
        $message = $response['message'];
        $details = $response['data'];
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('screen_home'));
        }
        $doc = $response['data'];
        $brands = Brand::all();
        $categories = Category::all();
        $services = Service::where('active', true)->get();
        $doctors = Doctor::where('active', true)->get();

        $categories = Category::all();
        return view('user.doctor.info', compact('message', 'details', 'brands', 'categories', 'services', 'doctors', 'doc', 'categories'));
    }

    /**
     * Get reservation customer for doctor.
     *
     * @return Application|Factory|View
     */
    public function getReservation(Request $request)
    {
        $this->checkRoleDoctor();
        $response['data'] = [];
        $response['message'] = null;
        $response['status'] = true;
        if (isset($request->date)) {
            $response = $this->model->getReservation($request);
        }
        $reservations = $response['data'];
        $message = $response['message'];
        return view('admin.reservation_leave.doctor_reservation', compact('message', 'reservations'));
    }

    public function reservationConfirm($id) {
        $this->checkRoleDoctor();
        $response['data'] = [];
        $response['message'] = null;
        $response['status'] = true;
        $response = $this->model->reservationConfirm($id);
        $message = $response['message'];
        return back()->with('message', $message);
    }
}
