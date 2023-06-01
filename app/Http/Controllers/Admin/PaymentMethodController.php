<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
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
        return view('admin.payment_methods.index');
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
        ];

        foreach (locales() as $key => $value) {
            $rules['title_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];

        foreach (locales() as $key => $value) {
            $data[$key] = ['title' => $request->get('title_' . $key)];
        }

        $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
        $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
        $filename = $request->image->move(public_path('images'), $filename);
        $data['image'] = $filename->getBasename();

        PaymentMethod::query()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('payment_methods.index'));
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
        $method = PaymentMethod::query()->find($id);

        $rules = [
            'image' => 'nullable',
        ];

        foreach (locales() as $key => $value) {
            $rules['title_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];

        foreach (locales() as $key => $value) {
            $data[$key] = ['title' => $request->get('title_' . $key)];
        }

        if ($request->image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        $method->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect(route('payment_methods.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            PaymentMethod::query()->whereIn('id', explode(',', $id))->delete();
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
        $method = PaymentMethod::query()->find($id);
        if ($method->status == 1) {
            $method->status = 0;
            $method->update();
            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $method->status = 1;
            $method->update();
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
            PaymentMethod::query()->whereIn('id', explode(',', $request->ids))
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
        $methods = PaymentMethod::query()->orderByDesc('created_at');
        return DataTables::of($methods)->filter(function ($query) use ($request) {
            if ($request->status) {
                $query->where('status', $request->status);
            }
//            if ($request->name) {
//                $ids = CategoryTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('category_id')->toArray();
//                $query->whereIn('id', $ids);
//            }
        })->addColumn('action', function ($method) {
            $data_attr = '';
            foreach (locales() as $key => $value) {
                $data_attr .= 'data-title_' . $key . '="' . $method->translate($key)->title . '" ';
            }
            $data_attr .= 'data-image="' . $method->image . '" ';

            $string = '';

            if (auth()->user()->hasAnyPermission(['edit_payment_method_status'])) {
                $string .= '<button onclick="add_remove(' . $method->id . ')" id="btn_' . $method->id . '" class="btn ' . ($method->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($method->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['edit_payment_method'])) {
                $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $method->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
            }

//                if (auth()->user()->hasAnyPermission(['delete_country'])) {
//            $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $method->id . '" title="" data-original-title="Delete" data-placement="top">
//                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
//                           </button>';
//                }

            return $string;
        })->make(true);
    }
}
