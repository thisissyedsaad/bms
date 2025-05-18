<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Services\{RoleService, UserService};
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\{DB, Log};
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Request;
use App\Helpers\{RouterHelper};
use Exception;

class UserController extends Controller
{
    private $userService, $router, $routerHelper;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->router       = 'users.index';
        $this->routerHelper = new RouterHelper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(HttpRequest $request)
    {
        $users = $this->userService->fetchAll($request);

        // $roleService = new RoleService;
        // $roles       = $roleService->getAllRoleNames();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $error = false;
        $message = trans('crud.model_added', ['model' => $request->name]);
        DB::beginTransaction();
        try {
            $user = $this->userService->create($request);
            // $user->assignRole($request->user_role);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $error = true;
            Log::error($e);
            $message = $e->getMessage();
        }

        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->userService->fetch($id);

        if ($user->email == 'admin@admin.com') {
            abort(403, 'Unauthorized action.');
        }
        // $roleService = new RoleService();
        // $roles = $roleService->getAllRoleNames();
        // $user_role = $this->userService->fetchUserRoleName($user);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $error   = false;
        $message = trans('crud.model_updated', ['model' => 'User']);
        $request->validated();
        try {
            $this->userService->update($request, $id);
        } catch (Exception $e) {
            $error   = true;
            $message = $e->getMessage();
            Log::error($e);
        }

        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $error   = false;
        $message = trans('crud.model_deleted', ['model' => 'User']);
        try {
            $this->userService->delete($id);
        } catch (Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }
}
