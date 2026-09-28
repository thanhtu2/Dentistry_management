<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use League\Flysystem\Config;
use PhpParser\Comment\Doc;

class ReservationLeave extends Model
{
    use HasFactory, ResponseTraits;

    protected $table = 'reservation_leave';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_time',
        'end_time',
        'date',
        'user_id',
        'status'
    ];

    /**
     * Relation with user
     *
     * @return void
     */
    public function user()
    {
        $this->hasOne(User::class);
    }

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function addReservationLeave($request) {
        $conflict = false;
        $date = date('Y-m-d', strtotime($request->date));
        $startTime = Carbon::createFromFormat('H:i', $request->startTime);
        $endTime = Carbon::createFromFormat('H:i', $request->endTime);
        $resLeaves = ReservationLeave::where('date', $date)->where('user_id', Auth::id())->get();
        foreach ($resLeaves as $key => $resLeave) {
            $resLeaveStartTime = Carbon::createFromFormat('H:i:s', $resLeave->start_time);
            $resLeaveEndTime = Carbon::createFromFormat('H:i:s', $resLeave->end_time);
            if (($startTime < $resLeaveStartTime && $endTime <= $resLeaveStartTime) ||
                ($startTime >= $resLeaveEndTime && $endTime > $resLeaveEndTime)
            ) {

            } else {
                $conflict = true;
                break;
            }
        }
        if ($conflict) {
            $status = false;
            $message = Lang::get('message.add_conflict_reservation');
            return $this->responseData($status, $message, null);
        } else {
            $reservationLeave = new ReservationLeave();
            $reservationLeave->date = $date;
            $reservationLeave->start_time = $startTime;
            $reservationLeave->end_time = $endTime;
            $reservationLeave->status = false;
            $reservationLeave->user_id = Auth::id();
            $reservationLeave->save();
            $doctor = Doctor::where('user_id', Auth::id())->first();

            $reservations = Reservation::where('doctor_id', $doctor->id)
                                        ->where('date', $date)
                                        ->where('status', 1)
                                        ->get();

            $conflictingReservations = [];
            foreach ($reservations as $reservation) {
                $reservationStartTime = Carbon::createFromFormat('H:i:s', $reservation->time);
                $reservationEndTime = $reservationStartTime->copy()->addMinutes($reservation->service->work_time);
                if (($startTime < $reservationEndTime->format('H:i:s')) && ($endTime > $reservationStartTime->format('H:i:s'))) {
                    $conflictingReservations[] = $reservation;
                }
            }
            $data = [];
            $data["id"] = $reservationLeave->id;
            $data["conflictReservation"] = $conflictingReservations;
            if (count($conflictingReservations) > 0) {
                $message = Lang::get('message.conflict_reservation');
                $status = false;
                return $this->responseData($status, $message, $data);
            }
            $reservationLeave->status = 1;
            $reservationLeave->save();
            $message = Lang::get('message.add_done');
            $status = true;
            return $this->responseData($status, $message, $reservationLeave);
        }
    }

    public function getReservationLeave($request) {
        $startDate = date('Y-m-d', strtotime(substr($request->date, 0, 10)));
        $endDate = date('Y-m-d', strtotime(substr($request->date, 13, 23)));
        $reservationLeave = ReservationLeave::where('user_id', Auth::id())->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate)->orderBy('date', 'DESC')->get();
        $message = Lang::get('message.add_done');
        $status = true;
        return $this->responseData($status, $message, $reservationLeave);
    }

    public function getDoctorLeave($doctorId, $date) {
        return ReservationLeave::where([['user_id', $doctorId], ['status', 1]])->whereDate('date', '>=', $date)->get();
    }

    public function getReservationLeaveById($id) {
        $reservationLeave = ReservationLeave::find($id);
        $conflictingReservations = [];
        $message = $reservationLeave == null ? Lang::get('message.can_not_find') : "";
        $status = true;

        if ($reservationLeave != null) {
            $doctorCur = Doctor::where('user_id', Auth::id())->first();
            $doctors = Doctor::where('id', '!=', $doctorCur->id)->get();
            $reservations = Reservation::where('date', $reservationLeave->date)
                                        ->where('status', 1)
                                        ->get();

            foreach ($reservations as $reservation) {
                $availableDoctors = [];
                $isConflict = false;
                $doctorCurStartTime = Carbon::createFromFormat('H:i:s', $reservationLeave->start_time);
                $doctorCurEndTime = Carbon::createFromFormat('H:i:s', $reservationLeave->end_time);

                $reservationCurStartTime = Carbon::createFromFormat('H:i:s', $reservation->time);
                $reservationCurEndTime = Carbon::createFromFormat('H:i:s', $reservation->time)
                    ->addHours(explode(':', $reservation->service->work_time)[0])
                    ->addMinutes(explode(':', $reservation->service->work_time)[1])
                    ->addSeconds(explode(':', $reservation->service->work_time)[2]);
                if (($reservationCurStartTime >= $doctorCurEndTime && $reservationCurEndTime > $doctorCurEndTime) ||
                    ($reservationCurStartTime < $doctorCurStartTime && $reservationCurEndTime <= $doctorCurStartTime)
                ) {

                } else{
                     foreach ($doctors as $doctor) {

                    $doctorReservations = Reservation::where('doctor_id', $doctor->id)
                                                      ->where('date', $reservation->date)
                                                      ->where('status', 1)
                                                      ->get();
                    foreach ($doctorReservations as $doctorReservation) {
                        $doctorStartTime = Carbon::createFromFormat('H:i:s', $doctorReservation->time);
                        $doctorEndTime = Carbon::createFromFormat('H:i:s', $doctorReservation->time)->addHours(explode(':', $reservation->service->work_time)[0])
                    ->addMinutes(explode(':', $reservation->service->work_time)[1])
                    ->addSeconds(explode(':', $reservation->service->work_time)[2]);;
                        $reservationStartTime = Carbon::createFromFormat('H:i:s', $reservation->time);
                        $reservationEndTime = Carbon::createFromFormat('H:i:s', $reservation->time)
                            ->addHours(explode(':', $reservation->service->work_time)[0])
                            ->addMinutes(explode(':', $reservation->service->work_time)[1])
                            ->addSeconds(explode(':', $reservation->service->work_time)[2]);

                        if (($reservationStartTime >= $doctorStartTime && $reservationEndTime < $doctorEndTime) ||
                            ($reservationEndTime > $doctorStartTime && $reservationEndTime <= $doctorEndTime)) {
                            $isConflict = true;
                            break;
                        }
                    }
                    if (!$isConflict) {
                        $availableDoctors[] = $doctor;
                    }
                }
                if (!$isConflict) {
                    $conflictingReservations[] = [
                        'reservation' => $reservation,
                        'availableDoctors' => $availableDoctors
                    ];
                }
                }
            }
        }
        $data = [
            'data' => $reservationLeave,
            'conflictingReservations' => $conflictingReservations
        ];
        if($data['conflictingReservations'] != null) {
            $data['status'] = false;
            $message = Lang::get('message.conflict_reservation');
        }
        return $this->responseData($status, $message, $data);
    }

    public function updateReservationLeave($request, $id) {
        $reservationLeave = ReservationLeave::Find($id);
        $reservationLeave->status = 0;
        $reservationLeave->save();

        $reservations = $request->input('reservations');
        foreach ($reservations as $key => $reservation) {
            $res = Reservation::find($reservation['id']);
            $res->doctor_id = $reservation['doctor'];
            $res->save();
        }

        $res = $this->getReservationLeaveById($id);
        if (!isset($res['data']['status'])) {
            $reservationLeave->status = 1;
            $reservationLeave->save();
        }
        $status = $res["status"];
        $message = $res["message"];
        return $this->responseData($status, $message, $res);
    }
}
