<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, ResponseTraits;

    protected $table = 'users';

    public const MANAGER = 'manager';
    public const ADMIN = 'admin';
    public const USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'phone',
        'role_id',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relation with role
     *
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get admin
     *
     * @return array
     */
    public function getAdmin($id)
    {
        try {
            $admin = User::find($id);
            $status = false;
            $message = Lang::get('message.email_exist');
            if (isset ($admin) && $admin->role->name === Config::get('auth.roles.admin') && !($admin->is_deleted)) {
                $status = true;
                $data = $admin;
                $message = null;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get admin
     *
     * @return array
     */
    public function getAdmins()
    {
        try {
            $roleAdmin = Role::where('name', Config::get('auth.roles.admin'))->first();
            $admins = User::where([
                ['role_id', $roleAdmin->id],
                ['is_deleted', false]])
                ->orderBy('id', 'DESC')
                ->get();
            $status = true;
            $message = null;
            $data = $admins;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Add brand
     *
     * @param $request
     * @return array
     */
    
    public function addAccount($request, $role)
{
    try {
        $status = false;
        $message = null;
        $data = null;

        if (User::where('email', $request->email)->first()) {
            $message = Lang::get('message.email_exist');
            return $this->responseData(false, $message, null);
        }

        if (User::where('username', $request->username)->first()) {
            $message = Lang::get('message.username_exist');
            return $this->responseData(false, $message, null);
        }

        $roleUser = Role::where(
            'name',
            Config::get('auth.roles.admin')
        )->first();

        if ($role != 'admin') {
            $roleUser = Role::where(
                'name',
                Config::get('auth.roles.doctor')
            )->first();
        }

        if (!$roleUser) {
            throw new Exception('Không tìm thấy quyền tài khoản.');
        }

        $account = new User();

        $account->name = $request->name;

        // QUAN TRỌNG: source cũ thiếu dòng này
        $account->email = $request->email;

        $account->username = $request->username;
        $account->phone = $request->phone;
        $account->password = Hash::make('123456');
        $account->role_id = $roleUser->id;

        $account->save();

        $status = true;
        $data = $account;
        $message = Lang::get('message.add_done');

    } catch (Exception $e) {
        $status = false;
        $message = $e->getMessage();
        $data = null;
    }

    return $this->responseData($status, $message, $data);
}
    /**
     * Delete product
     *
     * @param $id
     * @return array
     */
    public function deleteAdmin($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.delete_fail');
            $admin = User::find($id);
            if ($admin) {
                $admin->is_deleted = true;
                $admin->save();
                $status = true;
                $message = Lang::get('message.delete_done');
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }

    /**
     * Get admin
     *
     * @return array
     */
    public function getUsers()
    {
        try {
            $roleUser = Role::where('name', Config::get('auth.roles.user'))->get();
            $users = User::where([
                ['role_id', $roleUser->id],
                ['is_deleted', false]])
                ->orderBy('id', 'DESC')
                ->get();
            $status = true;
            $message = null;
            $data = $users;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Update info user
     *
     * @return array
     */
    public function updateInfo($request)
    {
        try {
            $status = true;
            $message = Lang::get('message.can_not_find');
            $user = User::find(Auth::id());
            if (isset($user) && $user->is_delete !== 1) {
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->address = null;
                if (isset($request->address)) {
                    $user->address = $request->address;
                }
                $user->save();
                $status = true;
                $message = Lang::get('message.update_done');
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }

    /**
     * Update info user
     *
     * @return array
     */
    public function changePassword($request)
    {
        try {
            $status = true;
            $message = Lang::get('message.can_not_find');
            $user = User::find(Auth::id());
            if (isset($user) && $user->is_deleted !== 1) {
                $credentials = ['username' => $user->username,
                    'password' => $request->old_password];
                if (Auth::guard('web')->attempt($credentials) && $request->password == $request->confirm_password) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                    $status = true;
                    $message = Lang::get('message.update_done');
                }
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }

    /**
     * Update info admin
     *
     * @return array
     */
   public function updateAdmin($request, $id)
{
    try {
        $status = false;
        $data = null;
        $message = Lang::get('message.can_not_find');

        // Tìm đúng tài khoản đang sửa bằng ID
        $user = User::find($id);

        if (!$user) {
            return $this->responseData(false, $message, null);
        }

        // Kiểm tra email có bị tài khoản khác sử dụng không
        $emailExist = User::where('email', $request->email)
            ->where('id', '!=', $id)
            ->first();

        if (!$emailExist) {

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;

            if (!empty($request->reset_password)) {
                $user->password = Hash::make($request->reset_password);
            }

            $user->save();

            $status = true;
            $data = $user;
            $message = Lang::get('message.update_done');

        } else {
            $message = Lang::get('message.email_exist');
        }

    } catch (Exception $e) {
        $status = false;
        $message = $e->getMessage();
    }

    return $this->responseData($status, $message, $data);
}

    public function getUserByPhone($request) {
        try {
            $users = User::where([
                ['phone', $request->phone],
                ['role_id', 2],
                ['is_deleted', false]])
                ->first();
            $status = true;
            $message = null;
            $data = $users;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }
}
