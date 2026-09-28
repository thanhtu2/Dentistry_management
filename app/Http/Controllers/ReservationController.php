<?php

namespace App\Http\Controllers;

use App\Exceptions\RoleAdminException;
use App\Models\Category;
use App\Models\Doctor;
use App\Models\Level;
use App\Models\Reservation;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Redirector;

class ReservationController extends Controller
{
    use ValidateTraits, ResponseTraits;

    private $model;
    private $modelDoctor;


    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Reservation();
        $this->modelDoctor = new Doctor();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|RedirectResponse
     * @throws RoleAdminException
     */
    public function index(Request $request)
{
    $this->checkRoleAdmin();

    if (isset($request->date) && $request->date != '') {

        $response = $this->model->getReservations($request);

        $reservations = $response['data'];
        $message = $response['message'];

        if (!$response['status']) {
            return back()->with('message', $message);
        }

    } else {

        $reservations = \App\Models\Reservation::with(['doctor', 'service'])
            ->orderBy('date', 'DESC')
            ->orderBy('time', 'DESC')
            ->get();
    }

    return view(
        'admin.reservation.reservations',
        compact('reservations')
    );
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
        $response = $this->model->getReservation($id);
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('admin.reservation.index'))->with('message', $message);
        }
        $reservation = $response['data'];

        $response = $this->modelDoctor->getDoctors();
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('admin.reservation.index'))->with('message', $message);
        }
        $doctors = $response['data'];
        return view('admin.reservation.reservation_edit', compact('doctors', 'reservation'));
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
                $response = $this->model->updateReservation($request, $id);
                $message = $response['message'];
            } else {
                throw new RoleAdminException();
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return redirect(route('admin.reservation.edit', ['reservation' => $id]))->with('message', $message);
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

        $reservation = \App\Models\Reservation::find($id);

        if (!$reservation) {
            return back()->with('message', 'Không tìm thấy lịch hẹn');
        }

        $reservation->delete();

        return back()->with('message', 'Xóa lịch hẹn thành công');

    } catch (Exception $e) {
        return back()->with('message', $e->getMessage());
    }
}

    public function getFreeTime(Request $request) {
        $response = $this->model->getFreeTime($request);
        $res['status'] = true;
        $res['data'] = $response;
        return $res;
    }
}
