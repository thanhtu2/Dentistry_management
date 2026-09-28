<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class Doctor extends Model
{
    use HasFactory, ResponseTraits;

    protected $table = 'doctor';

    protected $fillable = [
        'name',
        'email',
        'image',
        'level_id',
        'description',
        'introduce',
        'user_id',
        'active',
    ];

    private $modelProduct;
    private $modelReservation;
    private $modelUser;
    private $url;

    /**
     * Constructor
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->modelProduct = new Product();
        $this->modelReservation = new Reservation();
        $this->modelUser = new User();
        $this->url = Config::get('app.image.url');
    }

    /**
     * Relation with level
     */
    public function levelDoctor()
    {
        return $this->hasOne(Level::class, 'id', 'level_id');
    }

    /**
     * Relation with user
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get doctors
     */
    public function getDoctors()
    {
        try {
            $data = Doctor::orderBy('id', 'DESC')
                ->where('active', 1)
                ->get();

            $status = true;
            $message = null;

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }

        return $this->responseData($status, $message, $data);
    }

    /**
     * Get doctor
     */
    public function getDoctor($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $data = null;

            $doctor = Doctor::find($id);

            if ($doctor && $doctor->active == 1) {
                $status = true;
                $message = null;
                $data = $doctor;
            }

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }

        return $this->responseData($status, $message, $data);
    }

    /**
     * Add doctor
     */
    public function addDoctor($request)
    {
        try {
            $status = false;
            $message = null;
            $data = null;

            // Tạo tài khoản cho bác sĩ
            $response = $this->modelUser->addAccount($request, 'doctor');

            if (!$response['status']) {
                return $this->responseData(
                    false,
                    $response['message'],
                    null
                );
            }

            $doctor = new Doctor();

            $doctor->name = $request->name;
            $doctor->level_id = $request->level;
            $doctor->description = $request->description ?? null;
            $doctor->introduce = $request->introduce ?? null;

            // Liên kết bác sĩ với tài khoản vừa tạo
            $doctor->user_id = $response['data']->id;

            if ($request->hasFile('image')) {

                $image = $this->modelProduct->checkImage($request->image);

                if (!$image['status']) {
                    throw new Exception($image['message']);
                }

                $newImage = date('YmdHis') . '.'
                    . $request->image->getClientOriginalExtension();

                $doctor->image = $this->url . $newImage;

                $request->image->move(
                    $this->url,
                    $newImage
                );
            }

            $doctor->save();

            $status = true;
            $message = Lang::get('message.add_done');
            $data = $doctor;

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }

        return $this->responseData($status, $message, $data);
    }

    /**
     * Update doctor
     */
    public function updateDoctor($request, $id)
    {
        try {
            $status = false;
            $message = Lang::get('message.exist');
            $data = null;

            $doctor = Doctor::find($id);

            if ($doctor && $doctor->active == 1) {

                $doctor->name = $request->name;
                $doctor->level_id = $request->level;
                $doctor->description = $request->description ?? null;
                $doctor->introduce = $request->introduce ?? null;

                if ($request->hasFile('image')) {

                    $image = $this->modelProduct->checkImage($request->image);

                    if (!$image['status']) {
                        throw new Exception($image['message']);
                    }

                    $newImage = date('YmdHis') . '.'
                        . $request->image->getClientOriginalExtension();

                    $doctor->image = $this->url . $newImage;

                    $request->image->move(
                        $this->url,
                        $newImage
                    );
                }

                $doctor->save();

                $status = true;
                $message = Lang::get('message.update_done');
                $data = $doctor;

            } else {
                $message = Lang::get('message.can_not_find');
            }

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }

        return $this->responseData($status, $message, $data);
    }

    /**
     * Delete doctor
     */
    public function deleteDoctor($id)
{
    try {
        $status = false;
        $message = Lang::get('message.can_not_find');
        $data = null;

        $doctor = Doctor::find($id);

        if (!$doctor) {
            return $this->responseData(
                false,
                Lang::get('message.can_not_find'),
                null
            );
        }

        $userId = $doctor->user_id;

        // Kiểm tra bác sĩ đã có lịch khám chưa
        $hasReservation = Reservation::where('doctor_id', $doctor->id)->exists();

        if ($hasReservation) {

            // Giữ lại bác sĩ để không mất lịch sử khám
            $doctor->active = 0;
            $doctor->user_id = null;
            $doctor->save();

        } else {

            // Chưa có lịch khám thì xóa bác sĩ vĩnh viễn
            $doctor->delete();
        }

        // Xóa luôn tài khoản đăng nhập của bác sĩ
        if ($userId) {
            $user = User::find($userId);

            if ($user) {
                $user->delete();
            }
        }

        $status = true;
        $message = Lang::get('message.update_done');

    } catch (Exception $e) {
        $status = false;
        $message = $e->getMessage();
        $data = null;
    }

    return $this->responseData($status, $message, $data);
}

    /**
     * Get reservations of doctor
     */
    public function getReservation($request)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $data = null;

            $doctor = Doctor::where('user_id', Auth::id())
                ->where('active', 1)
                ->first();

            if ($doctor) {

                $data = $this->modelReservation
                    ->getReservationByDoctor($doctor->id, $request);

                $message = null;
                $status = true;
            }

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }

        return $this->responseData($status, $message, $data);
    }

    /**
     * Doctor confirms reservation
     */
    public function reservationConfirm($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $data = null;

            $reser = Reservation::find($id);

            if ($reser) {

                $reser->doctor_confirm_status = 1;
                $reser->save();

                $message = Lang::get('message.update_done');
                $status = true;
            }

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }

        return $this->responseData($status, $message, $data);
    }
}