<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\CountryTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
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
        return view('admin.countries.index');
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
            'country_key' => 'required',
            'flag' => 'required',
        ];

        foreach (locales() as $key => $value) {
            $rules['name_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        $data['country_key'] = $request->country_key;

        foreach (locales() as $key => $value) {
            $data[$key] = ['name' => $request->get('name_' . $key)];
        }

        if ($request->flag) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->flag->getClientOriginalName(), strrpos($request->flag->getClientOriginalName(), '.'));
            $filename = $request->flag->move(public_path('images'), $filename);
            $data['flag'] = $filename->getBasename();
        }

        $last_item = Country::query()->get()->last();
        $data['position'] = $last_item != null ? $last_item->position + 1 : 0;

        Country::query()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('countries.index'));
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
        $country = Country::query()->find($id);

        $rules = [
            'country_key' => 'required',
            'flag' => 'nullable',
        ];

        foreach (locales() as $key => $value) {
            $rules['name_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        $data['country_key'] = $request->country_key;

        foreach (locales() as $key => $value) {
            $data[$key] = ['name' => $request->get('name_' . $key)];
        }

        if ($request->flag) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->flag->getClientOriginalName(), strrpos($request->flag->getClientOriginalName(), '.'));
            $filename = $request->flag->move(public_path('images'), $filename);
            $data['flag'] = $filename->getBasename();
        }

        $country->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('countries.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Country::query()->whereIn('id', explode(',', $id))->delete();
            City::query()->where('country_id', $id)->delete();
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
        $country = Country::query()->find($id);
        if ($country->status == 1) {
            $country->status = 0;
            $country->update();
            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $country->status = 1;
            $country->update();
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
            Country::query()->whereIn('id', explode(',', $request->ids))
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
        $countries = Country::query()->orderBy('position', 'asc');
        return DataTables::of($countries)->filter(function ($query) use ($request) {
            if ($request->name) {
                $ids = CountryTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('country_id')->toArray();
                $query->whereIn('id', $ids);
            }
        })->addColumn('action', function ($country) {
            $data_attr = '';
            foreach (locales() as $key => $value) {
                $data_attr .= 'data-name_' . $key . '="' . $country->translate($key)->name . '" ';
            }
            $data_attr .= 'data-country_key="' . $country->country_key . '" ';
            $data_attr .= 'data-flag="' . $country->flag . '" ';

            $string = '';

            if (auth()->user()->hasAnyPermission(['edit_country_status'])) {
                $string .= '<button onclick="add_remove(' . $country->id . ')" id="btn_' . $country->id . '" class="btn ' . ($country->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($country->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['edit_country'])) {
                $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $country->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['delete_country'])) {
                $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $country->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';
            }

            return $string;
        })->make(true);
    }

    /**
     * @param Request $request
     * @return bool[]|false[]
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reorder(Request $request)
    {
        try {
            $rules = [
                'order' => 'required',
            ];

            $this->validate($request, $rules);

            $countries = Country::query()->get();
            foreach ($countries as $country) {
                $id = $country->id;
                foreach ($request->order as $key => $order) {
                    Country::query()->where("id", $order["id"])->update(['position' => $order['position']]);
                }
            }
            return ['status' => true];
        } catch (Exception $e) {
            return ['status' => false];
        }
    }
}
