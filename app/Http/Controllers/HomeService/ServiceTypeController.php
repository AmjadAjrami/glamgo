<?php

namespace App\Http\Controllers\HomeService;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use App\Models\ServiceTypeTranslation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ServiceTypeController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth:home_service');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return view('home_service.service_types.index');
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
        $rules = [];

        foreach (locales() as $key => $value){
            $rules['name_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        $data['home_service_id'] = auth('home_service')->id();

        foreach (locales() as $key => $value){
            $data[$key] = ['name' => $request->get('name_' . $key)];
        }

        $type = ServiceType::query()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('home_service_service_types.index'));
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $service_type = ServiceType::query()->find($id);

        $rules = [];

        foreach (locales() as $key => $value){
            $rules['name_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        $data['home_service_id'] = auth('home_service')->id();

        foreach (locales() as $key => $value){
            $data[$key] = ['name' => $request->get('name_' . $key)];
        }

        $service_type->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('home_service_service_types.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            ServiceType::query()->whereIn('id', explode(',', $id))->delete();
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
        return response()->json(['status' => true]);
    }

    /**
     * @param $id
     * @return array
     */
    public function update_status($id)
    {
        $salon_service_type = ServiceType::query()->find($id);
        if ($salon_service_type->status == 1) {
            $salon_service_type->status = 0;
            $salon_service_type->update();
            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $salon_service_type->status = 1;
            $salon_service_type->update();
            return ['success' => true, 'message' => __('common.activated_successfully'), 'text' => __('common.deactivate'),
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
            ServiceType::query()->whereIn('id', explode(',', $request->ids))
                ->update(['status' => $request->status]);
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
        $home_service_service_types = ServiceType::query()->where('home_service_id', auth('home_service')->id())->orderByDesc('created_at');
        return DataTables::of($home_service_service_types)->filter(function ($query) use ($request) {
            if ($request->status) {
                $query->where('status', $request->status);
            }
            if ($request->name) {
                $ids = ServiceTypeTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('service_type_id')->toArray();
                $query->whereIn('id', $ids);
            }
        })->addColumn('action', function ($artist_service_type) {
            $data_attr = '';
            foreach (locales() as $key => $value) {
                $data_attr .= 'data-name_' . $key . '="' . $artist_service_type->translate($key)->name . '" ';
            }

            $string = '';

//                if (auth()->user()->hasAnyPermission(['edit_country_status'])) {
            $string .= '<button onclick="add_remove(' . $artist_service_type->id . ')" id="btn_' . $artist_service_type->id . '" class="btn ' . ($artist_service_type->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($artist_service_type->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
//                }

//                if (auth()->user()->hasAnyPermission(['edit_country'])) {
            $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $artist_service_type->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
//                }

//                if (auth()->user()->hasAnyPermission(['delete_country'])) {
            $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $artist_service_type->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';
//                }

            return $string;
        })->make(true);
    }
}
