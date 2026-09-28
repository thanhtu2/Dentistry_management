<?php

namespace App\Models;

use App\Http\Controllers\ReservationController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class Reservation extends Model
{
    use HasFactory, ResponseTraits;

//    const TIME_ACCEPT = ["8:00", "8:20", "8:40",
//        "9:00", "9:20", "9:40",
//        "10:00", "10:20", "10:40",
//        "11:00", "11:20", "11:40",
//        "12:00", "12:20", "12:40",
//        "13:00", "13:20", "13:40",
//        "14:00", "14:20", "14:40",
//        "15:00", "15:20", "15:40",
//        "16:00", "16:20", "16:40",
//        "17:00", "17:20", "17:40",
//        "18:00", "18:20", "18:40",
//        "19:00", "19:20", "19:40"];

const TIME_ACCEPT = ["08:00", "08:30",
        "09:00", "09:30",
        "10:00", "10:30",
        "11:00", "11:30",
        "12:00", "12:30",
        "13:00", "13:30",
        "14:00", "14:30",
        "15:00", "15:30",
        "16:00", "16:30",
        "17:00", "17:30",
        "18:00", "18:30",
        "19:00", "19:30",];

    protected $table = 'reservation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'doctor_id',
        'user_id',
        'service_id',
        'name',
        'phone',
        'date',
        'time',
        'message',
        'status',
        'doctor_confirm_status',
    ];

    private $model;
    private $modelReservationLeave;
    private $url;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->url = Config::get('app.image.url');
        $this->modelReservationLeave = new ReservationLeave();
    }

    /**
     * Relation with user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Relation with doctor
     *
     * @return BelongsTo
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id', 'doctor_id');
    }

    /**
     * Relation with service
     *
     * @return BelongsTo
     */
    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    public function getReservations($request)
    {
        try {
            $startDate = date('Y-m-d', strtotime(substr($request->date, 0, 10)));
            $endDate = date('Y-m-d', strtotime(substr($request->date, 13, 23)));
            $data = Reservation::whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate)->orderBy('id', 'DESC')->get();
            foreach($data as $key => $value) {
                $currentDate = Carbon::parse($value->date);
                switch ($value->service->unit_recheck) {
                    case 'day':
                        $data[$key]->date_recheck = $currentDate->addDays($value->service->number_recheck)->format("Y-m-d");
                        break;
                    case 'month':
                        $data[$key]->date_recheck = $currentDate->addMonths($value->service->number_recheck)->format("Y-m-d");;
                        break;
                    case 'year':
                        $data[$key]->date_recheck = $currentDate->addYears($value->service->number_recheck)->format("Y-m-d");;
                        break;
                    default:
                        $data[$key]->date_recheck = $value->date;
                        break;
                }
            }
            $status = true;
            $message = null;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    public function getReservation($id)
    {
        try {
            $data = Reservation::find($id);
            $status = true;
            $message = null;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    public function updateReservation($request, $id)
    {
        try {
            $data = null;
            $reservation = Reservation::find($id);
            $reservation->status = (int) $request->status;
            if (isset($request->doctor_id) && $request->doctor_id != null) 
            {
                $reservation->doctor_id = $request->doctor_id;
            }
            $reservation->save();
            $status = true;
            $message = Lang::get('message.update_done');
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    public function getFreeTime($request): array
    {
        return $this->getFreeTimeDoctor($request->doctor_id, $request->date, $request->service_id);
    }

    public function getFreeTimeDoctor($doctorId, $date, $serviceId): array
    {
        $busyTime = $this->modelReservationLeave->getDoctorLeave($doctorId, $date);
        $reservation = Reservation::where('date', $date)
         ->where('doctor_id', $doctorId)
         ->whereIn('status', [1, 2])
         ->get();
        $timeReservation = [];
        if ($serviceId) {
            $serviceReq = Service::where('id', $serviceId)->first();
        }

        foreach ($reservation as $resv) {
            $timeService = Carbon::createFromFormat("H:i:s", $resv->service->work_time);
            $timeMain = Carbon::createFromFormat("H:i:s", $resv->time);
            $timeReservation[] = $timeMain->format('H:i');

            while ($timeService->greaterThan('00:30:00')) {
                $timeMain->addMinutes(30);
                $timeReservation[] = $timeMain->format('H:i');
                $timeService->subMinutes(30);
            }
        }

        foreach ($busyTime as $busy) {
            $startTime = Carbon::createFromFormat("H:i:s", $busy->start_time);
            $endTime = Carbon::createFromFormat("H:i:s", $busy->end_time);

            while ($startTime->lessThan($endTime)) {
                $timeReservation[] = $startTime->format('H:i');
                $startTime->addMinutes(30);
            }
        }

        if ($serviceId != null) {
            $timeServiceReq = Carbon::createFromFormat("H:i:s", $serviceReq->work_time);
            if ($timeServiceReq->greaterThan('00:30:00')) {
                foreach ($timeReservation as $time) {
                    $timeMain = Carbon::createFromFormat("H:i", $time);
                    while ($timeServiceReq->greaterThan('00:30:00')) {
                        $timeMain->subMinutes(30);
                        $timeReservation[] = $timeMain->format('H:i');
                        $timeServiceReq->subMinutes(30);
                    }
                }
            }
        }
        $timeReservation = new Collection($timeReservation);
        $time = $timeReservation->map(function ($time) {
            return date('H:i', strtotime($time));
        });
        $diff = array_diff(self::TIME_ACCEPT, $time->toArray());
        return array_values($diff);
    }

    public function createReservation($request) {
        $status = false;
        $message = null;
        $data = null;
        $date = $request['date'];
        $time = $request['time'];

        $dateTimeString = $date . ' ' . $time;
        $dateTime = Carbon::createFromFormat('Y-m-d H:i', $dateTimeString);
        $now = Carbon::now();
        if ($dateTime->lt($now)) {
            $message = Lang::get('message.time_in_past');

        } else {
            $freetime = $this->getFreeTime($request);
            if (Reservation::where([['doctor_id', $request['doctor_id']], ['date', $request['date']], ['time', $request['time']]])->first() || !in_array($request['time'], $freetime)) {
                $message = Lang::get('message.exist_reservation');
            } else {
                $reser = new Reservation();
                $reser->doctor_id = $request['doctor_id'];
                $reser->user_id = auth()->id();
                $reser->name = $request['name'];
                $reser->phone = $request['phone'];
                $reser->date = $request['date'];
                $reser->time = $request['time'];
                $reser->service_id = $request['service_id'];
                $reser->message = $request['message'] ?? null;
                $reser->status = 0;
                $reser->save();
                $status = true;
                if (
    $request['doctor_id'] != 0 &&
    isset($reser->doctor) &&
    isset($reser->doctor->user) &&
    isset($reser->doctor->user->email)
) {

    $data = array(
        "name" => $reser->doctor->name,
        "time" => $reser->time,
        "date" => $reser->date,
        "email" => $reser->doctor->user->email,
        "service" => $reser->service->name
    );

    Mail::send('mail.mail_reservation', $data, function ($messages) use ($data) {
        $messages->to($data['email'])
            ->subject(Lang::get('message.notification_reservation'));
    });
}
                $message = Lang::get('message.done_reservation');
            }
        }
        return $this->responseData($status, $message, $data);
    }

    public function getReservationByDoctor($doctorId, $request) {
        $startDate = date('Y-m-d', strtotime(substr($request->date, 0, 10)));
        $endDate = date('Y-m-d', strtotime(substr($request->date, 13, 23)));
        return Reservation::where([['doctor_id', $doctorId], ['status', 1]])->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate)->orderBy('date', 'DESC')->get();
    }
}
