<?php

namespace App\Services;

use Illuminate\Support\Facades\{Log, DB, Auth};
use Illuminate\Support\Str;
use App\Models\User;

class UserService
{
    private $model;
    public function __construct()
    {
        $this->model = new User();
    }

    public function create($request)
    {
        $request->mergeIfMissing(['password' => $this->autoGeneratePassword()]);
        return $this->model->create($request->all());
    }

    public function update($request, $id)
    {
        $user = $this->fetch($id);
        $user->fill(array_filter($request->all()))->save();
        if (!$user->hasRole('admin')) {
            $user->syncRoles($request->user_role);
        }
        return $user;
    }

    public function delete($id)
    {
        DB::beginTransaction();
        $user     = $this->fetch($id);
        $response = $this->setResponse('error', trans('crud.error', ['NAME' => $user->name]));
        try {
            $user->delete();
            $response = $this->setResponse('error', trans('crud.delete', ['NAME' => $user->name]));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
        return $response;
    }

    public function updateStatus($request, $id)
    {
        $user = $this->fetch($id);
        $user->fill($request->all())->save();
        return $user;
    }

    /**
     *
     * set response
     */
    public function setResponse($type, $message, $code = 200)
    {
        $response = array('type' => $type, 'message' => $message, 'code' => $code);
        return $response;
    }

    /**
     *
     * returns all the users except the logged-in admin himself
     * **/
    public function getAllUsersExceptAdmin()
    {
        return User::all()->except(Auth::id());
    }

    public function fetchAll($request)
    {
        return User::orderBy('created_at', 'desc')
            ->when($request->has('name') && $request->name, function ($query) use ($request) {
                return $query->where('name', $request->input('name'));
            })
            ->when($request->has('email') && $request->email, function ($query) use ($request) {
                return $query->where('email', $request->input('email'));
            })
            ->when($request->has('status') && $request->status >= 0, function ($query) use ($request) {
                return $query->where('is_active', (int)$request->input('status'));
            })
            ->paginate(config('constants.pagination_limit'));
    }

    /**
     *
     * returns user by id
     * **/
    public function fetch($id)
    {
        return User::findOrFail($id);
    }

    /**
     *
     * returns user role name
     * **/
    function fetchUserRoleName($user)
    {
        $user->roles()->pluck('name')->first();
    }

    /**
     *
     * returns random password string
     * **/
    public function autoGeneratePassword($length = 10): string
    {
        $password = Str::random($length);
        return $password;
    }
}
