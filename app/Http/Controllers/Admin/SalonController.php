<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ClinicImage;
use App\Models\Salon;
use App\Models\SalonCategory;
use App\Models\SalonGallery;
use App\Models\SalonTranslation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SalonController extends Controller
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
        $categories = Category::query()->where('type', $request->type)->get();
        return view('admin.salons.index', compact('type', 'categories'));
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
            'cover_image' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'address_text' => 'required',
            'email' => 'required|unique:salons,email',
            'mobile' => 'nullable|unique:salons,mobile',
            'password' => 'required',
            'video' => 'nullable',
            'thumbnail' => 'required_if:video,!=,null',
            'images' => 'required|array',
            'images.*' => 'required',
            'category_id' => 'required|array',
            'category_id.*' => 'required',
        ];

        foreach (locales() as $key => $value) {
            $rules['name_' . $key] = 'required|string';
            $rules['bio_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        $data['lat'] = $request->lat;
        $data['lng'] = $request->lng;
        $data['address_text'] = $request->address_text;
        $data['email'] = $request->email;
        if ($request->mobile){
            $data['mobile'] = $request->mobile;
        }
        $data['password'] = Hash::make($request->password);
        $data['type'] = $request->type;
        $data['bank_name'] = $request->bank_name;
        $data['bank_account_name'] = $request->bank_account_name;
        $data['iban'] = $request->iban;
        $data['bank_account_number'] = $request->bank_account_number;

        if ($request->image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        if ($request->cover_image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->cover_image->getClientOriginalName(), strrpos($request->cover_image->getClientOriginalName(), '.'));
            $filename = $request->cover_image->move(public_path('images'), $filename);
            $data['cover_image'] = $filename->getBasename();
        }

        foreach (locales() as $key => $value) {
            $data[$key] = ['name' => $request->get('name_' . $key), 'bio' => $request->get('bio_' . $key)];
        }

        $salon = Salon::query()->create($data);

        if ($request->video) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->video->getClientOriginalName(), strrpos($request->video->getClientOriginalName(), '.'));
            $filename = $request->video->move(public_path('gallery'), $filename);

            $thumbnail = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $thumbnail .= substr($request->thumbnail->getClientOriginalName(), strrpos($request->thumbnail->getClientOriginalName(), '.'));
            $thumbnail = $request->thumbnail->move(public_path('gallery'), $thumbnail);

            SalonGallery::query()->insert([
                'salon_id' => $salon->id,
                'item' => $filename->getBasename(),
                'thumbnail' => $thumbnail->getBasename(),
                'type' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($request->images) {
            foreach ($request->images as $image) {
                $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
                $filename .= substr($image->getClientOriginalName(), strrpos($image->getClientOriginalName(), '.'));
                $filename = $image->move(public_path('gallery'), $filename);

                SalonGallery::query()->insert([
                    'salon_id' => $salon->id,
                    'item' => $filename->getBasename(),
                    'thumbnail' => null,
                    'type' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if ($request->category_id) {
            foreach ($request->category_id as $category_id) {
                SalonCategory::query()->insert([
                    'salon_id' => $salon->id,
                    'category_id' => $category_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('salons.index'));
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
        $salon = Salon::query()->find($id);

        $rules = [
            'image' => 'nullable',
            'cover_image' => 'nullable',
            'lat' => 'required',
            'lng' => 'required',
            'address_text' => 'required',
            'email' => 'required|unique:salons,email,' . $id,
            'mobile' => 'nullable|unique:salons,mobile,' . $id,
            'password' => 'nullable',
            'video' => 'nullable',
            'thumbnail' => 'required_if:video,!=,null',
            'images' => 'nullable|array',
            'images.*' => 'nullable',
            'category_id' => 'required|array',
            'category_id.*' => 'required',
        ];

        foreach (locales() as $key => $value) {
            $rules['name_' . $key] = 'required|string';
            $rules['bio_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        $data['lat'] = $request->lat;
        $data['lng'] = $request->lng;
        $data['address_text'] = $request->address_text;
        $data['email'] = $request->email;
        if ($request->mobile){
            $data['mobile'] = $request->mobile;
        }
        $data['bank_name'] = $request->bank_name;
        $data['bank_account_name'] = $request->bank_account_name;
        $data['iban'] = $request->iban;
        $data['bank_account_number'] = $request->bank_account_number;

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $data['type'] = $request->type;

        if ($request->image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        if ($request->cover_image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->cover_image->getClientOriginalName(), strrpos($request->cover_image->getClientOriginalName(), '.'));
            $filename = $request->cover_image->move(public_path('images'), $filename);
            $data['cover_image'] = $filename->getBasename();
        }

        foreach (locales() as $key => $value) {
            $data[$key] = ['name' => $request->get('name_' . $key), 'bio' => $request->get('bio_' . $key)];
        }

        $salon->update($data);

        if ($request->video) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->video->getClientOriginalName(), strrpos($request->video->getClientOriginalName(), '.'));
            $filename = $request->video->move(public_path('gallery'), $filename);

            $is_exists = SalonGallery::query()->where('salon_id', $salon->id)->where('type', 2)->first();
            if ($is_exists) {
                $is_exists->update([
                    'salon_id' => $salon->id,
                    'item' => $filename->getBasename(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                SalonGallery::query()->insert([
                    'salon_id' => $salon->id,
                    'item' => $filename->getBasename(),
                    'type' => 2,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if ($request->thumbnail) {
            $thumbnail = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $thumbnail .= substr($request->thumbnail->getClientOriginalName(), strrpos($request->thumbnail->getClientOriginalName(), '.'));
            $thumbnail = $request->thumbnail->move(public_path('gallery'), $thumbnail);
            $thumbnail_file = $thumbnail->getBasename();

            SalonGallery::query()->where('salon_id', $salon->id)->where('type', 2)->update([
                'salon_id' => $salon->id,
                'thumbnail' => $thumbnail_file,
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($request->preloaded) {
            SalonGallery::query()->where('salon_id', $salon->id)->where('type', 1)->whereNotIn('id', $request->preloaded)->delete();
        } else {
            SalonGallery::query()->where('salon_id', $salon->id)->where('type', 1)->delete();
        }

        if ($request->images) {
            foreach ($request->images as $image) {
                $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
                $filename .= substr($image->getClientOriginalName(), strrpos($image->getClientOriginalName(), '.'));
                $filename = $image->move(public_path('gallery'), $filename);

                SalonGallery::query()->insert([
                    'salon_id' => $salon->id,
                    'item' => $filename->getBasename(),
                    'thumbnail' => null,
                    'type' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if ($request->category_id) {
            SalonCategory::query()->where('salon_id', $salon->id)->delete();
            foreach ($request->category_id as $category_id) {
                SalonCategory::query()->insert([
                    'salon_id' => $salon->id,
                    'category_id' => $category_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect(route('salons.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Salon::query()->whereIn('id', explode(',', $id))->delete();
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
        $salon = Salon::query()->find($id);
        if ($salon->status == 1) {
            $salon->status = 0;
            $salon->update();
            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $salon->status = 1;
            $salon->update();
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
            Salon::query()->whereIn('id', explode(',', $request->ids))
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
        $salons = Salon::query()->where('type', $type)->orderByDesc('created_at');
        return DataTables::of($salons)->filter(function ($query) use ($request) {
            if ($request->name) {
                $ids = SalonTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('salon_id');
                $query->whereIn('id', $ids);
            }
            if ($request->email) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }
        })->addColumn('action', function ($salon) {
            $data_attr = '';
            foreach (locales() as $key => $value) {
                $data_attr .= 'data-name_' . $key . '="' . $salon->translate($key)->name . '" ';
                $data_attr .= 'data-bio_' . $key . '="' . $salon->translate($key)->bio . '" ';
            }
            $data_attr .= 'data-email="' . $salon->email . '" ';
            $data_attr .= 'data-mobile="' . $salon->mobile . '" ';
            $data_attr .= 'data-image="' . $salon->image . '" ';
            $data_attr .= 'data-cover_image="' . $salon->cover_image . '" ';
            $data_attr .= 'data-lat="' . $salon->lat . '" ';
            $data_attr .= 'data-lng="' . $salon->lng . '" ';
            $data_attr .= 'data-address_text="' . $salon->address_text . '" ';
            $data_attr .= 'data-video="' . $salon->video . '" ';
            $data_attr .= 'data-thumbnail="' . $salon->thumbnail . '" ';
            $data_attr .= 'data-category_id="' . $salon->salon_categories->pluck('category_id') . '" ';
            $data_attr .= "data-images='" . $salon->gallery . "' ";
            $data_attr .= "data-bank_name='" . $salon->bank_name . "' ";
            $data_attr .= "data-bank_account_name='" . $salon->bank_account_name . "' ";
            $data_attr .= "data-iban='" . $salon->iban . "' ";
            $data_attr .= "data-bank_account_number='" . $salon->bank_account_number . "' ";

            $string = '';

            if (auth()->user()->hasAnyPermission(['edit_salon_status'])) {
                $string .= '<button onclick="add_remove(' . $salon->id . ')" id="btn_' . $salon->id . '" class="btn ' . ($salon->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($salon->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['edit_salon'])) {
                $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $salon->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['delete_salon'])) {
                $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $salon->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';
            }

            return $string;
        })->make(true);
    }
}
