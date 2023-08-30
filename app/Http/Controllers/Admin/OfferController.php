<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MakeupArtist;
use App\Models\Offer;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OfferController extends Controller
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
        $salons = Salon::query()->where('status', 1)->get();
        $artists = MakeupArtist::query()->where('status', 1)->get();
        $categories = Category::query()->where('status', 1)->get();

        return view('admin.offers.index', compact('salons', 'artists', 'categories'));
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
            'offer_for' => 'required',
            'salon_id' => 'required_if:offer_for,=,1',
            'makeup_artist_id' => 'required_if:offer_for,=,2',
            'category_id' => 'required',
            'price' => 'required',
            'discount_price' => 'required',
            'service_type' => 'required',
        ];

        foreach (locales() as $key => $value) {
            $rules['title_' . $key] = 'required|string';
            $rules['description_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        $data['salon_id'] = $request->salon_id;
        $data['makeup_artist_id'] = $request->makeup_artist_id;
        $data['category_id'] = $request->category_id;
        $data['price'] = $request->price;
        $data['discount_price'] = $request->discount_price;
        $data['service_type'] = $request->service_type;

        if ($request->image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        foreach (locales() as $key => $value) {
            $data[$key] = ['title' => $request->get('title_' . $key), 'description' => $request->get('description_' . $key)];
        }

        Offer::query()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('offers.index'));
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
     * @param $type
     * @return array
     */
    public function categories($type)
    {
        $type = $type == 3 ? 2 : $type;
        $categories = Category::query()->where('status', 1)->where('type', $type)->get();
        $salons = Salon::query()->where('type', $type)->get();
        return ['categories' => $categories, 'salons' => $salons];
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
        $offer = Offer::query()->find($id);

        $rules = [
            'image' => 'nullable',
            'offer_for' => 'required',
            'salon_id' => 'required_if:offer_for,=,1',
            'makeup_artist_id' => 'required_if:offer_for,=,2',
            'category_id' => 'required',
            'price' => 'required',
            'discount_price' => 'required',
            'service_type' => 'required',
        ];

        foreach (locales() as $key => $value) {
            $rules['title_' . $key] = 'required|string';
            $rules['description_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        if ($request->offer_for == 1 || $request->offer_for == 3) {
            $data['salon_id'] = $request->salon_id;
            $data['makeup_artist_id'] = null;
        } else {
            $data['salon_id'] = null;
            $data['makeup_artist_id'] = $request->makeup_artist_id;
        }
        $data['category_id'] = $request->category_id;
        $data['price'] = $request->price;
        $data['discount_price'] = $request->discount_price;
        $data['service_type'] = $request->service_type;

        if ($request->image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        foreach (locales() as $key => $value) {
            $data[$key] = ['title' => $request->get('title_' . $key), 'description' => $request->get('description_' . $key)];
        }

        $offer->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect(route('offers.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Offer::query()->whereIn('id', explode(',', $id))->delete();
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
        $offer = Offer::query()->find($id);
        if ($offer->status == 1) {
            $offer->status = 0;
            $offer->update();
            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $offer->status = 1;
            $offer->update();
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
            Offer::query()->whereIn('id', explode(',', $request->ids))
                ->update(['status' => $request->status]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
        return response()->json(['status' => true]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function indexTable(Request $request)
    {
        $offers = Offer::query()->orderByDesc('created_at');
        return DataTables::of($offers)->filter(function ($query) use ($request) {

        })->addColumn('action', function ($offer) {
            $data_attr = '';
            foreach (locales() as $key => $value) {
                $data_attr .= 'data-title_' . $key . '="' . $offer->translate($key)->title . '" ';
                $data_attr .= 'data-description_' . $key . '="' . $offer->translate($key)->description . '" ';
            }
            $data_attr .= 'data-image="' . $offer->image . '" ';
            $data_attr .= 'data-category_id="' . $offer->category_id . '" ';
            $data_attr .= 'data-salon_id="' . $offer->salon_id . '" ';
            $data_attr .= 'data-salon_type="' . @$offer->salon->type . '" ';
            $data_attr .= 'data-makeup_artist_id="' . $offer->makeup_artist_id . '" ';
            $data_attr .= 'data-price="' . $offer->price . '" ';
            $data_attr .= 'data-discount_price="' . $offer->discount_price . '" ';
            $data_attr .= 'data-service_type="' . $offer->service_type . '" ';

            $string = '';

            if (auth()->user()->hasAnyPermission(['edit_offer_status'])) {
                $string .= '<button onclick="add_remove(' . $offer->id . ')" id="btn_' . $offer->id . '" class="btn ' . ($offer->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($offer->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['edit_offer'])) {
                $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $offer->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['delete_offer'])) {
                $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $offer->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';
            }

            return $string;
        })->make(true);
    }
}
