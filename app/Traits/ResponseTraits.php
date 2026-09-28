<?php

namespace App\Traits;

use App\Exceptions\RoleAdminException;
use App\Http\Controllers\AuthController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

trait ResponseTraits
{
    /**
     * Response after login
     *
     * @param $status
     * @param $message
     * @param $tokenResult
     * @return JsonResponse
     */
    public function responseAuth($status, $message, $tokenResult = null)
    {
        return response()->json([
            'status_code' => $status,
            'message' => $message,
            'access_token' => $tokenResult,
        ]);
    }

    /**
     * Check role manager
     *
     * @return bool
     * @throws RoleAdminException
     */
    public function checkRoleManager()
    {
        $auth = new AuthController();
        if (Auth::user()->role->name === $auth->manager) {
            return true;
        }
        Throw new RoleAdminException();
    }

    /**
     * Check role admin
     *
     * @return bool
     * @throws RoleAdminException
     */
    public function checkRoleAdmin()
{
    $auth = new AuthController();

    // Chưa đăng nhập
    if (!Auth::check()) {
        throw new RoleAdminException();
    }

    $user = Auth::user();

    // Tài khoản chưa có role
    if (!$user->role) {
        throw new RoleAdminException();
    }

    $roleName = $user->role->name;

    if (
        $roleName === $auth->doctor ||
        $roleName === $auth->admin ||
        $roleName === $auth->manager
    ) {
        return true;
    }

    throw new RoleAdminException();
}

    /**
     * Check role user
     *
     * @return bool
     */
    public function checkRoleUser()
    {
        if (Auth::user()->role->name === $this->user) {
            return true;
        } return false;
    }

    /**
     * Check role doctor
     *
     * @return bool
     */
    public function checkRoleDoctor()
    {
        $auth = new AuthController();
        if (Auth::user()->role->name ===  $auth->doctor) {
            return true;
        } return false;
    }

    /**
     * Response data
     *
     * @param $status
     * @param $message
     * @param $data
     * @return array
     */
    public function responseData($status = null, $message = null, $data = null)
    {
        $response['status']    = $status;
        $response['message']   = $message;
        $response['data']      = $data;
        return $response;
    }
}
