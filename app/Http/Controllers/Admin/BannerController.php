<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\MakeupArtist;
use App\Models\Product;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $type = $request->type;
        if ($type == 1) {
            $references = Salon::query()->where('status', 1)->get();
            $salons = [];
            $artists = [];
        } elseif ($type == 2) {
            $references = MakeupArtist::query()->where('status', 1)->get();
            $salons = Salon::query()->where('status', 1)->where('type', 2)->get();
            $artists = MakeupArtist::query()->where('status', 1)->get();
        } elseif ($type == 3) {
            $references = Product::query()->where('status', 1)->get();
            $salons = [];
            $artists = [];
        }
        return view('admin.banners.index', compact('type', 'references', 'salons', 'artists'));
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
            'banner_type' => 'nullable',
            'reference_id' => 'required_if:banner_type,=,1',
            'link' => 'required_if:banner_type,=,2',
            'image' => 'required',
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'banner_for' => 'required_if:type,=,3'
        ];

        foreach (locales() as $key => $value) {
            $rules['title_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];

        foreach (locales() as $key => $value) {
            $data[$key] = ['title' => $request->get('title_' . $key)];
        }

        if ($request->type == 3) {
            $data['type'] = $request->banner_for;
        } else {
            $data['type'] = $request->type;
        }
        $data['banner_type'] = $request->banner_type;
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['link'] = $request->link ?? null;
        if ($request->type == 1) {
            $data['salon_id'] = $request->reference_id;
        } elseif ($request->type == 2) {
            if ($request->user_type == 1) {
                $data['salon_id'] = $request->reference_id;
            } else {
                $data['makeup_artist_id'] = $request->reference_id;
            }
        } elseif ($request->type == 3) {
            $data['product_id'] = $request->reference_id;
        }

        if ($request->image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        Banner::query()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('banners.index'));
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
        $banner = Banner::query()->find($id);

        $rules = [
            'banner_type' => 'nullable',
            'reference_id' => 'required_if:banner_type,=,1',
            'reference_id_1' => 'required_if:user_type,=,1',
            'reference_id_2' => 'required_if:user_type,=,2',
            'link' => 'required_if:banner_type,=,2',
            'image' => 'nullable',
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'banner_for' => 'required_if:type,=,3'
        ];

        foreach (locales() as $key => $value) {
            $rules['title_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];

        foreach (locales() as $key => $value) {
            $data[$key] = ['title' => $request->get('title_' . $key)];
        }

        if ($request->type == 3) {
            $data['type'] = $request->banner_for;
        } else {
            $data['type'] = $request->type;
        }
        $data['banner_type'] = $request->banner_type;
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['link'] = $request->link ?? null;
        if ($request->type == 1) {
            $data['salon_id'] = $request->reference_id;
        } elseif ($request->type == 2) {
            if ($request->user_type == 1) {
                $data['salon_id'] = $request->reference_id_1;
                $data['makeup_artist_id'] = null;
            } else {
                $data['makeup_artist_id'] = $request->reference_id_2;
                $data['salon_id'] = null;
            }
        } elseif ($request->type == 3) {
            $data['product_id'] = $request->reference_id;
        }

        if ($request->image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        $banner->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect(route('banners.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Banner::query()->whereIn('id', explode(',', $id))->delete();
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
        $banner = Banner::query()->find($id);
        if ($banner->status == 1) {
            $banner->status = 0;
            $banner->update();
            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $banner->status = 1;
            $banner->update();
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
            Banner::query()->whereIn('id', explode(',', $request->ids))
                ->update(['status' => $request->status]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
        return response()->json(['status' => true]);
    }

    /**
     * @param Request $request
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public function indexTable(Request $request, $type)
    {
        if ($type == 3) {
            $banners = Banner::query()->whereIn('type', [3, 4, 5])->orderByDesc('created_at');
        } else {
            $banners = Banner::query()->where('type', $type)->orderByDesc('created_at');
        }
        return DataTables::of($banners)->addColumn('action', function ($banner) {
            $data_attr = '';
            foreach (locales() as $key => $value) {
                $data_attr .= 'data-title_' . $key . '="' . $banner->translate($key)->title . '" ';
            }
            $data_attr .= 'data-type="' . $banner->type . '" ';
            $data_attr .= 'data-banner_type="' . $banner->banner_type . '" ';
            $data_attr .= 'data-salon_id="' . $banner->salon_id . '" ';
            $data_attr .= 'data-makeup_artist_id="' . $banner->makeup_artist_id . '" ';
            $data_attr .= 'data-product_id="' . $banner->product_id . '" ';
            $data_attr .= 'data-link="' . $banner->link . '" ';
            $data_attr .= 'data-image="' . $banner->image . '" ';
            $data_attr .= 'data-start_date="' . $banner->start_date . '" ';
            $data_attr .= 'data-end_date="' . $banner->end_date . '" ';

            $string = '';

            if (auth()->user()->hasAnyPermission(['edit_banner_status'])) {
                $string .= '<button onclick="add_remove(' . $banner->id . ')" id="btn_' . $banner->id . '" class="btn ' . ($banner->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($banner->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['edit_banner'])) {
                $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $banner->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['delete_banner'])) {
                $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $banner->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';
            }

            return $string;
        })->make(true);
    }
}
