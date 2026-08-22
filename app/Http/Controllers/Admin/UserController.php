<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Role;
use App\Services\AdminAuditLogger;

class UserController extends Controller
{
    private const CUSTOMER_ROLE_NAMES = ['khach-hang', 'khach_hang', 'customer'];
    private const CUSTOMER_ROLE_DISPLAY_NAMES = ['Khách hàng', 'Khach hang', 'Customer'];

    protected $auditLogger;

    public function __construct(Role $role, AdminAuditLogger $auditLogger)
    {
        view()->share([
            'user_active' => 'active',
        ]);

        view()->composer(['admin.user.*'], function ($view) use ($role) {
            $view->with('roles', $role->all());
        });

        $this->auditLogger = $auditLogger;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        view()->share([
            'user_staff_active' => 'active',
        ]);

        return $this->renderUserList($request, 'staff');
    }

    public function customers(Request $request)
    {
        view()->share([
            'user_customer_active' => 'active',
        ]);

        return $this->renderUserList($request, 'customers');
    }

    private function renderUserList(Request $request, string $listType)
    {
        $users = User::with('userRole');

        if ($listType === 'customers') {
            $this->scopeCustomers($users);
        } else {
            $this->scopeStaff($users);
        }

        $this->applyFilters($users, $request);

        $users = $users->orderByDesc('id')
            ->paginate(NUMBER_PAGINATION)
            ->appends($request->query());

        $staffCount = $this->countStaffUsers();
        $customerCount = $this->countCustomerUsers();
        $filterRoles = $this->getFilterRoles($listType);

        return view('admin.user.index', compact('users', 'listType', 'staffCount', 'customerCount', 'filterRoles'));
    }

    private function applyFilters($users, Request $request): void
    {
        if ($request->name) {
            $users->where('name', 'like', '%'.$request->name.'%');
        }
        if ($request->email) {
            $users->where('email', 'like', '%'.$request->email.'%');
        }
        if ($request->phone) {
            $users->where('phone', 'like', '%'.$request->phone.'%');
        }

        if ($request->user_id) {
            $users->where('user_id', $request->user_id);
        }

        if ($request->role_id) {
            $listUser = \DB::table('role_user')->where('role_id', $request->role_id)->pluck('user_id');
            $users->whereIn('id', $listUser);
        }
    }

    private function scopeStaff($users): void
    {
        $users->whereHas('userRole', function ($role) {
            $this->scopeInternalRole($role);
        });
    }

    private function scopeCustomers($users): void
    {
        $users->whereDoesntHave('userRole', function ($role) {
            $this->scopeInternalRole($role);
        });
    }

    private function scopeInternalRole($role): void
    {
        $role->where(function ($query) {
            $query->whereNotIn('name', self::CUSTOMER_ROLE_NAMES)
                ->orWhereNull('name');
        })->where(function ($query) {
            $query->whereNotIn('display_name', self::CUSTOMER_ROLE_DISPLAY_NAMES)
                ->orWhereNull('display_name');
        });
    }

    private function countStaffUsers(): int
    {
        $query = User::query();
        $this->scopeStaff($query);

        return $query->count();
    }

    private function countCustomerUsers(): int
    {
        $query = User::query();
        $this->scopeCustomers($query);

        return $query->count();
    }

    private function getFilterRoles(string $listType)
    {
        $roles = Role::query()->orderBy('display_name');

        if ($listType === 'customers') {
            $roles->where(function ($query) {
                $query->whereIn('name', self::CUSTOMER_ROLE_NAMES)
                    ->orWhereIn('display_name', self::CUSTOMER_ROLE_DISPLAY_NAMES);
            });
        } else {
            $this->scopeInternalRole($roles);
        }

        return $roles->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        //
        \DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = bcrypt($request->password);
            $user->status = $request->status;
            if ($request->hasFile('images')) {
                $image = upload_image('images');
                if ($image['code'] == 1)
                    $user->avatar = $image['name'];
            }
            if ($user->save()) {
                \DB::table('role_user')->insert(['role_id'=> $request->role, 'user_id'=> $user->id]);
            }
            $this->auditLogger->log('user.created', $user, [
                'role_id' => (int) $request->role,
                'status' => (int) $user->status,
            ], $request);

            \DB::commit();
            return redirect()->back()->with('success','Thêm mới thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::with([
            'userRole' => function($userRole)
            {
                $userRole->select('*');
            }
        ])->find($id);
        $listRoleUser = \DB::table('role_user')->where('user_id', $id)->first();
        if(!$user) {
            return redirect()->route('get.list.user')->with('danger', 'Quyền không tồn tại');
        }

        $viewData = [
            'user' => $user,
            'listRoleUser' => $listRoleUser
        ];
        return view('admin.user.create', $viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        //
        \DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;

            if ($request->hasFile('images')) {
                $image = upload_image('images');
                if ($image['code'] == 1)
                    $user->avatar = $image['name'];
            }
            if ($user->save()) {
                $oldRole = \DB::table('role_user')->where('user_id', $id)->value('role_id');
                \DB::table('role_user')->where('user_id', $id)->delete();
                \DB::table('role_user')->insert(['role_id'=> $request->role, 'user_id'=> $user->id]);
            }
            $this->auditLogger->log('user.updated', $user, [
                'old_role_id' => isset($oldRole) ? (int) $oldRole : null,
                'new_role_id' => (int) $request->role,
                'status' => (int) $user->status,
            ], $request);

            \DB::commit();
            return redirect()->back()->with('success','Chỉnh sửa thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }
        \DB::beginTransaction();
        try {
            $this->auditLogger->log('user.deleted', $user, [
                'email' => $user->email,
                'status' => (int) $user->status,
            ]);
            $user->delete();
            \DB::commit();
            return redirect()->back()->with('success','Đã xóa thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }
}
