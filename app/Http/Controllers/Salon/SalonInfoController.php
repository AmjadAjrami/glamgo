<?php

namespace App\Http\Controllers\Salon;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Salon;
use App\Models\SalonCategory;
use App\Models\SalonGallery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SalonInfoController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth:salon');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $salon = Salon::query()->find(auth('salon')->id());
        $categories = Category::query()->where('type', auth('salon')->user()->type)->get();

        return view('salon.salon_info.index', compact('salon', 'categories'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $salon = Salon::query()->find(auth('salon')->id());

        $rules = [
            'image' => 'nullable',
            'cover_image' => 'nullable',
            'lat' => 'required',
            'lng' => 'required',
            'address_text' => 'required',
            'email' => 'required|email|unique:salons,email,' . $salon->id,
            'mobile' => 'nullable|unique:salons,mobile,' . $salon->id,
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

        if ($request->password){
            $data['password'] = Hash::make($request->password);
        }

        if ($request->image){
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        if ($request->cover_image){
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->cover_image->getClientOriginalName(), strrpos($request->cover_image->getClientOriginalName(), '.'));
            $filename = $request->cover_image->move(public_path('images'), $filename);
            $data['cover_image'] = $filename->getBasename();
        }

        foreach (locales() as $key => $value){
            $data[$key] = ['name' => $request->get('name_' . $key), 'bio' => $request->get('bio_' . $key)];
        }

        $salon->update($data);

        if ($request->video){
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->video->getClientOriginalName(), strrpos($request->video->getClientOriginalName(), '.'));
            $filename = $request->video->move(public_path('gallery'), $filename);

            SalonGallery::query()->where('salon_id', $salon->id)->where('type', 2)->update([
                'salon_id' => $salon->id,
                'item' => $filename->getBasename(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($request->thumbnail){
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

        if ($request->images){
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

        if ($request->category_id){
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

        flash()->success(__('common.updated_successfully'));
        return redirect(route('salon_info.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
