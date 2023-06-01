<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MakeupArtist;
use App\Models\MakeupArtistCategory;
use App\Models\MakeupArtistGallery;
use App\Models\MakeupArtistTranslation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MakeupArtistController extends Controller
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
        return view('admin.artists.index', compact('type', 'categories'));
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

        $artist = MakeupArtist::query()->create($data);

        if ($request->video) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->video->getClientOriginalName(), strrpos($request->video->getClientOriginalName(), '.'));
            $filename = $request->video->move(public_path('gallery'), $filename);

            $thumbnail = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $thumbnail .= substr($request->thumbnail->getClientOriginalName(), strrpos($request->thumbnail->getClientOriginalName(), '.'));
            $thumbnail = $request->thumbnail->move(public_path('gallery'), $thumbnail);

            MakeupArtistGallery::query()->insert([
                'makeup_artist_id' => $artist->id,
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

                MakeupArtistGallery::query()->insert([
                    'makeup_artist_id' => $artist->id,
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
                MakeupArtistCategory::query()->insert([
                    'makeup_artist_id' => $artist->id,
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
        return redirect(route('makeup_artists.index'));
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
        $artist = MakeupArtist::query()->find($id);

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
        if ($request->passwword) {
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

        $artist->update($data);

        if ($request->video) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->video->getClientOriginalName(), strrpos($request->video->getClientOriginalName(), '.'));
            $filename = $request->video->move(public_path('gallery'), $filename);

            MakeupArtistGallery::query()->where('makeup_artist_id', $artist->id)->where('type', 2)->update([
                'makeup_artist_id' => $artist->id,
                'item' => $filename->getBasename(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($request->thumbnail) {
            $thumbnail = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $thumbnail .= substr($request->thumbnail->getClientOriginalName(), strrpos($request->thumbnail->getClientOriginalName(), '.'));
            $thumbnail = $request->thumbnail->move(public_path('gallery'), $thumbnail);
            $thumbnail_file = $thumbnail->getBasename();

            MakeupArtistGallery::query()->where('makeup_artist_id', $artist->id)->where('type', 2)->update([
                'makeup_artist_id' => $artist->id,
                'thumbnail' => $thumbnail_file,
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($request->preloaded) {
            MakeupArtistGallery::query()->where('makeup_artist_id', $artist->id)->where('type', 1)->whereNotIn('id', $request->preloaded)->delete();
        } else {
            MakeupArtistGallery::query()->where('makeup_artist_id', $artist->id)->where('type', 1)->delete();
        }

        if ($request->images) {
            foreach ($request->images as $image) {
                $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
                $filename .= substr($image->getClientOriginalName(), strrpos($image->getClientOriginalName(), '.'));
                $filename = $image->move(public_path('gallery'), $filename);

                MakeupArtistGallery::query()->insert([
                    'makeup_artist_id' => $artist->id,
                    'item' => $filename->getBasename(),
                    'thumbnail' => null,
                    'type' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if ($request->category_id) {
            MakeupArtistCategory::query()->where('makeup_artist_id', $artist->id)->delete();
            foreach ($request->category_id as $category_id) {
                MakeupArtistCategory::query()->insert([
                    'makeup_artist_id' => $artist->id,
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
        return redirect(route('makeup_artists.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            MakeupArtist::query()->whereIn('id', explode(',', $id))->delete();
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
        $artist = MakeupArtist::query()->find($id);
        if ($artist->status == 1) {
            $artist->status = 0;
            $artist->update();
            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $artist->status = 1;
            $artist->update();
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
        $artists = MakeupArtist::query()->orderByDesc('created_at');
        return DataTables::of($artists)->filter(function ($query) use ($request) {
            if ($request->name) {
                $ids = MakeupArtistTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('makeup_artist_id');
                $query->whereIn('id', $ids);
            }
            if ($request->email) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }
        })->addColumn('action', function ($artist) {
            $data_attr = '';
            foreach (locales() as $key => $value) {
                $data_attr .= 'data-name_' . $key . '="' . $artist->translate($key)->name . '" ';
                $data_attr .= 'data-bio_' . $key . '="' . $artist->translate($key)->bio . '" ';
            }
            $data_attr .= 'data-email="' . $artist->email . '" ';
            $data_attr .= 'data-mobile="' . $artist->mobile . '" ';
            $data_attr .= 'data-image="' . $artist->image . '" ';
            $data_attr .= 'data-cover_image="' . $artist->cover_image . '" ';
            $data_attr .= 'data-video="' . $artist->video . '" ';
            $data_attr .= 'data-thumbnail="' . $artist->thumbnail . '" ';
            $data_attr .= 'data-category_id="' . $artist->makeup_artist_categories->pluck('category_id') . '" ';
            $data_attr .= "data-images='" . $artist->gallery . "' ";
            $data_attr .= "data-bank_name='" . $artist->bank_name . "' ";
            $data_attr .= "data-bank_account_name='" . $artist->bank_account_name . "' ";
            $data_attr .= "data-iban='" . $artist->iban . "' ";
            $data_attr .= "data-bank_account_number='" . $artist->bank_account_number . "' ";

            $string = '';

            if (auth()->user()->hasAnyPermission(['edit_artist_status'])) {
                $string .= '<button onclick="add_remove(' . $artist->id . ')" id="btn_' . $artist->id . '" class="btn ' . ($artist->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($artist->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['edit_artist'])) {
                $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $artist->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['delete_artist'])) {
                $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $artist->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';
            }

            return $string;
        })->make(true);
    }
}
