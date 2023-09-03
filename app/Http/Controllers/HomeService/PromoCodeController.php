<?php

namespace App\Http\Controllers\HomeService;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PromoCodeController extends Controller
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
    public function index()
    {
        return view('home_service.promo_codes.index');
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
            'code' => 'required',
            'discount_type' => 'required|in:1,2',
            'discount' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
            'number_of_usage' => 'required',
            'number_of_usage_for_user' => 'required',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['home_service_id'] = auth('home_service')->id();

        PromoCode::query()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('home_service_promo_codes.index'));
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
        $code = PromoCode::query()->find($id);

        $rules = [
            'code' => 'required',
            'discount_type' => 'required|in:1,2',
            'discount' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
            'number_of_usage' => 'required',
            'number_of_usage_for_user' => 'required',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['home_service_id'] = auth('home_service')->id();

        $code->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('home_service_promo_codes.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try{
            PromoCode::query()->whereIn('id', explode(',', $id))->delete();
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
        $code = PromoCode::query()->find($id);
        if ($code->status == 1){
            $code->status = 0;
            $code->update();
            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        }else{
            $code->status = 1;
            $code->update();
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
            PromoCode::query()->whereIn('id', explode(',', $request->ids))
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
        $codes = PromoCode::query()->orderByDesc('created_at')->where('home_service_id', auth('home_service')->id());
        return DataTables::of($codes)->filter(function ($query) use ($request) {
            if ($request->code) {
                $query->where('code', 'like', '%' . $request->code . '%');
            }
            if ($request->discount_type) {
                $query->where('discount_type', $request->discount_type);
            }
        })->addColumn('action', function ($code) {
                $data_attr = '';
                $data_attr .= 'data-code="' . $code->code . '" ';
                $data_attr .= 'data-discount_type="' . $code->discount_type . '" ';
                $data_attr .= 'data-discount="' . $code->discount . '" ';
                $data_attr .= 'data-date_from="' . $code->date_from . '" ';
                $data_attr .= 'data-date_to="' . $code->date_to . '" ';
                $data_attr .= 'data-number_of_usage="' . $code->number_of_usage . '" ';
                $data_attr .= 'data-number_of_usage_for_user="' . $code->number_of_usage_for_user . '" ';

                $string = '';

//                if (auth()->user()->hasAnyPermission(['edit_country_status'])) {
                $string .= '<button onclick="add_remove(' . $code->id . ')" id="btn_' . $code->id . '" class="btn ' . ($code->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($code->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
//                }

//                if (auth()->user()->hasAnyPermission(['edit_country'])) {
                $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $code->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
//                }

//                if (auth()->user()->hasAnyPermission(['delete_country'])) {
                $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $code->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';
//                }

                return $string;
            })->make(true);
    }
}
