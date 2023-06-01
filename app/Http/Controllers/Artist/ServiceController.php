<?php

namespace App\Http\Controllers\Artist;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth:artist');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $service_types = ServiceType::query()->where('makeup_artist_id', auth('artist')->id())->where('status', 1)->get();
        return view('artist.services.index', compact('service_types'));
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
            'image' => 'required',
            'service_type_id' => 'required|exists:service_types,id',
            'service_category' => 'required',
            'execution_time' => 'required',
            'price' => 'required',
            'has_discount' => 'nullable',
            'discount_price' => 'required_if:has_discount,=,1',
        ];

        foreach (locales() as $key => $value){
            $rules['name_' . $key] = 'required|string';
            $rules['description_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];

        foreach (locales() as $key => $value){
            $data[$key] = ['name' => $request->get('name_' . $key), 'description' => $request->get('description_' . $key)];
        }

        $data['makeup_artist_id'] = auth('artist')->id();
        $data['service_type_id'] = $request->service_type_id;
        $data['service_category'] = $request->service_category;
        $data['execution_time'] = $request->execution_time;
        $data['price'] = $request->price;
        $data['discount_price'] = $request->discount_price ?? null;
        $data['has_discount'] = $request->has_discount == null ? 0 : 1;

        if ($request->image){
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        Service::query()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('artist_services.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $service = Service::query()->find($id);

        $rules = [
            'image' => 'nullable',
            'service_type_id' => 'required|exists:service_types,id',
            'service_category' => 'required',
            'execution_time' => 'required',
            'price' => 'required',
            'has_discount' => 'nullable',
            'discount_price' => 'required_if:has_discount,=,1',
        ];

        foreach (locales() as $key => $value){
            $rules['name_' . $key] = 'required|string';
            $rules['description_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];

        foreach (locales() as $key => $value){
            $data[$key] = ['name' => $request->get('name_' . $key), 'description' => $request->get('description_' . $key)];
        }

        $data['makeup_artist_id'] = auth('artist')->id();
        $data['service_type_id'] = $request->service_type_id;
        $data['service_category'] = $request->service_category;
        $data['execution_time'] = $request->execution_time;
        $data['price'] = $request->price;
        $data['discount_price'] = $request->discount_price ?? null;
        $data['has_discount'] = $request->has_discount == null ? 0 : 1;

        if ($request->image){
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        $service->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect(route('artist_services.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Service::query()->whereIn('id', explode(',', $id))->delete();
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
        $service = Service::query()->find($id);
        if ($service->status == 1) {
            $service->status = 0;
            $service->update();
            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $service->status = 1;
            $service->update();
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
            Service::query()->whereIn('id', explode(',', $request->ids))
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
        $services = Service::query()->where('makeup_artist_id', auth('artist')->id())->orderByDesc('created_at');
        return DataTables::of($services)->filter(function ($query) use ($request) {
            if ($request->status) {
                $query->where('status', $request->status);
            }
            if ($request->name) {
                $ids = ServiceTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('category_id')->toArray();
                $query->whereIn('id', $ids);
            }
        })->addColumn('action', function ($service) {
            $data_attr = '';
            foreach (locales() as $key => $value) {
                $data_attr .= 'data-name_' . $key . '="' . $service->translate($key)->name . '" ';
                $data_attr .= 'data-description_' . $key . '="' . $service->translate($key)->description . '" ';
            }
            $data_attr .= 'data-image="' . $service->image . '" ';
            $data_attr .= 'data-service_type_id="' . $service->service_type_id . '" ';
            $data_attr .= 'data-service_category="' . $service->service_category . '" ';
            $data_attr .= 'data-execution_time="' . $service->execution_time . '" ';
            $data_attr .= 'data-price="' . $service->price . '" ';
            $data_attr .= 'data-discount_price="' . $service->discount_price . '" ';
            $data_attr .= 'data-has_discount="' . $service->has_discount . '" ';

            $string = '';

//                if (auth()->user()->hasAnyPermission(['edit_country_status'])) {
            $string .= '<button onclick="add_remove(' . $service->id . ')" id="btn_' . $service->id . '" class="btn ' . ($service->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($service->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
//                }

//                if (auth()->user()->hasAnyPermission(['edit_country'])) {
            $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $service->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
//                }

//                if (auth()->user()->hasAnyPermission(['delete_country'])) {
            $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $service->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';
//                }

            return $string;
        })->make(true);
    }

    /**
     * @param Request $request
     * @return bool[]
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_is_completed_status(Request $request)
    {
        $rules = [
            'id' => 'required',
            'is_completed' => 'required',
        ];

        $this->validate($request, $rules);

        Service::query()->find($request->id)->update(['is_completed' => $request->is_completed]);

        return ['status' => true];
    }
}
