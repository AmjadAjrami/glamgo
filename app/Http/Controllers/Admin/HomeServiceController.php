<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeService;
use App\Models\HomeServiceCategory;
use App\Models\HomeServiceGallery;
use App\Models\HomeServiceTranslation;
use App\Models\MakeupArtist;
use App\Models\MakeupArtistCategory;
use App\Models\MakeupArtistGallery;
use App\Models\MakeupArtistTranslation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HomeServiceController extends Controller
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
        return view('admin.home_services.index', compact('type', 'categories'));
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
            'email' => 'required|unique:makeup_artists,email',
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
        $data['email'] = $request->email;
        $data['mobile'] = $request->mobile;
        $data['password'] = Hash::make($request->password);
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

        $home_service = HomeService::query()->create($data);

        if ($request->video) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->video->getClientOriginalName(), strrpos($request->video->getClientOriginalName(), '.'));
            $filename = $request->video->move(public_path('gallery'), $filename);

            $thumbnail = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $thumbnail .= substr($request->thumbnail->getClientOriginalName(), strrpos($request->thumbnail->getClientOriginalName(), '.'));
            $thumbnail = $request->thumbnail->move(public_path('gallery'), $thumbnail);

            HomeServiceGallery::query()->insert([
                'home_service_id' => $home_service->id,
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

                HomeServiceGallery::query()->insert([
                    'home_service_id' => $home_service->id,
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
                HomeServiceCategory::query()->insert([
                    'home_service_id' => $home_service->id,
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
        return redirect(route('home_services.index'));
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
        $home_service = HomeService::query()->find($id);

        $rules = [
            'image' => 'nullable',
            'cover_image' => 'nullable',
            'email' => 'required|unique:makeup_artists,email,' . $id,
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
        $data['email'] = $request->email;
        $data['mobile'] = $request->mobile;
        $data['bank_name'] = $request->bank_name;
        $data['bank_account_name'] = $request->bank_account_name;
        $data['iban'] = $request->iban;
        $data['bank_account_number'] = $request->bank_account_number;
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

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

        $home_service->update($data);

        if ($request->video) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->video->getClientOriginalName(), strrpos($request->video->getClientOriginalName(), '.'));
            $filename = $request->video->move(public_path('gallery'), $filename);

            HomeServiceGallery::query()->where('home_service_id', $home_service->id)->where('type', 2)->update([
                'home_service_id' => $home_service->id,
                'item' => $filename->getBasename(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($request->thumbnail) {
            $thumbnail = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $thumbnail .= substr($request->thumbnail->getClientOriginalName(), strrpos($request->thumbnail->getClientOriginalName(), '.'));
            $thumbnail = $request->thumbnail->move(public_path('gallery'), $thumbnail);
            $thumbnail_file = $thumbnail->getBasename();

            HomeServiceGallery::query()->where('home_service_id', $home_service->id)->where('type', 2)->update([
                'home_service_id' => $home_service->id,
                'thumbnail' => $thumbnail_file,
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($request->preloaded) {
            HomeServiceGallery::query()->where('home_service_id', $home_service->id)->where('type', 1)->whereNotIn('id', $request->preloaded)->delete();
        } else {
            HomeServiceGallery::query()->where('home_service_id', $home_service->id)->where('type', 1)->delete();
        }

        if ($request->images) {
            foreach ($request->images as $image) {
                $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
                $filename .= substr($image->getClientOriginalName(), strrpos($image->getClientOriginalName(), '.'));
                $filename = $image->move(public_path('gallery'), $filename);

                HomeServiceGallery::query()->insert([
                    'home_service_id' => $home_service->id,
                    'item' => $filename->getBasename(),
                    'thumbnail' => null,
                    'type' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if ($request->category_id) {
            HomeServiceCategory::query()->where('home_service_id', $home_service->id)->delete();
            foreach ($request->category_id as $category_id) {
                HomeServiceCategory::query()->insert([
                    'home_service_id' => $home_service->id,
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
        return redirect(route('home_services.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            HomeServiceCategory::query()->whereIn('id', explode(',', $id))->delete();
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
        $home_service = HomeServiceCategory::query()->find($id);
        if ($home_service->status == 1) {
            $home_service->status = 0;
            $home_service->update();
            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $home_service->status = 1;
            $home_service->update();
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
            MakeupArtist::query()->whereIn('id', explode(',', $request->ids))
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
        $home_services = HomeService::query()->orderByDesc('created_at');
        return DataTables::of($home_services)->filter(function ($query) use ($request) {
            if ($request->name) {
                $ids = HomeServiceTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('home_service_id');
                $query->whereIn('id', $ids);
            }
            if ($request->email) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }
        })->addColumn('action', function ($home_service) {
            $data_attr = '';
            foreach (locales() as $key => $value) {
                $data_attr .= 'data-name_' . $key . '="' . $home_service->translate($key)->name . '" ';
                $data_attr .= 'data-bio_' . $key . '="' . $home_service->translate($key)->bio . '" ';
            }
            $data_attr .= 'data-email="' . $home_service->email . '" ';
            $data_attr .= 'data-mobile="' . $home_service->mobile . '" ';
            $data_attr .= 'data-image="' . $home_service->image . '" ';
            $data_attr .= 'data-cover_image="' . $home_service->cover_image . '" ';
            $data_attr .= 'data-video="' . $home_service->video . '" ';
            $data_attr .= 'data-thumbnail="' . $home_service->thumbnail . '" ';
            $data_attr .= 'data-category_id="' . $home_service->home_service_categories->pluck('category_id') . '" ';
            $data_attr .= "data-images='" . $home_service->gallery . "' ";
            $data_attr .= "data-bank_name='" . $home_service->bank_name . "' ";
            $data_attr .= "data-bank_account_name='" . $home_service->bank_account_name . "' ";
            $data_attr .= "data-iban='" . $home_service->iban . "' ";
            $data_attr .= "data-bank_account_number='" . $home_service->bank_account_number . "' ";

            $string = '';

            if (auth()->user()->hasAnyPermission(['edit_home_service_status'])) {
                $string .= '<button onclick="add_remove(' . $home_service->id . ')" id="btn_' . $home_service->id . '" class="btn ' . ($home_service->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($home_service->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['edit_home_service'])) {
                $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $home_service->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['delete_home_service'])) {
                $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $home_service->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';
            }

            return $string;
        })->make(true);
    }
}
