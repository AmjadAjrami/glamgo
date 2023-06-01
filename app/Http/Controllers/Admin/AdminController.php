<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $countries = Country::query()->where('status', 1)->get();
        $permissions = Permission::query()->where('guard_name', 'admin')->get();

        return view('admin.admins.index', compact('countries', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:admins,email',
            'mobile' => 'required|unique:admins,mobile',
            'password' => 'required',
            'country_id' => 'required',
            'city_id' => 'required',
            'permissions' => 'required|array',
        ];

        $this->validate($request, $rules);

        $data = $request->except('password', 'permissions');
        $data['password'] = Hash::make($request->password);

        $admin = Admin::query()->create($data);

        $admin->givePermissionTo($request->permissions);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('admins.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $admin = Admin::query()->with('permissions')->find($id);
        $countries = Country::query()->where('status', 1)->get();
        $country_cities = City::query()->where('status', 1)->where('country_id', $admin->country_id)->get();

        $permissions = Permission::all();
        $permission_groups = [];

        for ($i = 1; $i < 24; $i++) {
            $permission_groups[$i - 1]['name'] = Permission::groupName($i);
            $permissions_group = [];
            foreach (Permission::query()->where('type', $i)->get() as $k => $permission) {
                $permissions_group[$k] = $permission;
                $permissions_group[$k]['name_trans'] = __('common.' . $permission['name']);
            }
            $permission_groups[$i - 1]['permissions'] = $permissions_group;
        }

        return view('admin.admins.edit', compact('permission_groups', 'permissions', 'admin', 'countries', 'country_cities'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::query()->find($id);

        $rules = [
            'name' => 'required',
            'email' => 'required|unique:admins,email,' . $id,
            'mobile' => 'required|unique:admins,mobile,' . $id,
            'password' => 'nullable',
            'country_id' => 'required',
            'city_id' => 'required',
            'permissions' => 'required|array',
        ];

        $this->validate($request, $rules);

        $data = $request->except('password', 'permissions');
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        if ($request->permissions) {
            DB::table('model_has_permissions')->where('model_id', $id)->delete();
            $admin->givePermissionTo($request->permissions);
        }

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect(route('admins.index'));
    }

    /**
     * @param $id
     * @return array
     */
    public function update_status($id)
    {
        $admins = Admin::query()->find($id);
        if ($admins->status == 1) {
            $admins->status = 0;
            $admins->update();
            return ['success' => true, 'message' => __('common.updated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $admins->status = 1;
            $admins->update();
            return ['success' => true, 'message' => __('common.updated_successfully'), 'text' => __('common.deactivate'),
                'remove' => 'btn btn-dark', 'add' => 'btn btn-warning'];
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_all_status(Request $request)
    {
        $rules = [
            'ids' => 'required',
            'status' => 'required|in:0,1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false]);
        }
        try {
            Admin::query()->whereIn('id', explode(',', $request->ids))
                ->update(['status' => $request->status]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
        return response()->json(['status' => true]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Admin::query()->whereIn('id', explode(',', $id))->delete();
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
        return response()->json(['status' => true]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function indexTable(Request $request)
    {
        $admins = Admin::query()->orderByDesc('created_at')->where('id', '!=', 1);
        return DataTables::of($admins)
            ->filter(function ($query) use ($request) {
                if ($request->title) {
                    $locale = app()->getLocale();
                    $query->where("name->$locale", 'Like', "%" . $request->name . "%");
                }
            })->addColumn('action', function ($admin) {
                $data_attr = '';
                $data_attr .= 'data-name="' . $admin->name . '" ';
                $data_attr .= 'data-email="' . $admin->email . '" ';
                $data_attr .= 'data-mobile="' . $admin->mobile . '" ';
                $data_attr .= 'data-country_id' . $admin->country_id . '" ';
                $data_attr .= 'data-city_id' . $admin->city_id . '" ';
                $data_attr .= "data-permissions='" . $admin->permissions . "' ";
                $string = '';

                if (auth()->user()->hasAnyPermission(['edit_admin_status'])) {
                    $string .= '<button onclick="add_remove(' . $admin->id . ')" id="btn_' . $admin->id . '" class="btn ' . ($admin->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($admin->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
                }

                if (auth()->user()->hasAnyPermission(['edit_admin'])) {
                    $string .= '<a class="bs-tooltip" href="' . route('admins.edit', $admin->id) . '" data-placement="top">
                               <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </a>';
                }

                if (auth()->user()->hasAnyPermission(['delete_admin'])) {
                    $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $admin->id . '" title="" data-original-title="Delete" data-placement="top">
                               <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>';
                }

                return $string;
            })->make(true);
    }
}
