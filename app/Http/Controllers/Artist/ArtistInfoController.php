<?php

namespace App\Http\Controllers\Artist;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MakeupArtist;
use App\Models\MakeupArtistCategory;
use App\Models\MakeupArtistGallery;
use App\Models\Salon;
use App\Models\SalonCategory;
use App\Models\SalonGallery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ArtistInfoController extends Controller
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
        $artist = MakeupArtist::query()->find(auth('artist')->id());
        $categories = Category::query()->where('type', 2)->get();

        return view('artist.artist_info.index', compact('artist', 'categories'));
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
        $artist = MakeupArtist::query()->find(auth('artist')->id());

        $rules = [
            'image' => 'nullable',
            'cover_image' => 'nullable',
            'email' => 'required|email|unique:makeup_artists,email,' . $artist->id,
            'mobile' => 'required|unique:makeup_artists,mobile,' . $artist->id,
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

        $artist->update($data);

        if ($request->video){
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->video->getClientOriginalName(), strrpos($request->video->getClientOriginalName(), '.'));
            $filename = $request->video->move(public_path('gallery'), $filename);

            MakeupArtistGallery::query()->where('makeup_artist_id', $artist->id)->where('type', 2)->update([
                'makeup_artist_id' => $artist->id,
                'item' => $filename->getBasename(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($request->thumbnail){
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

        if ($request->images){
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

        if ($request->category_id){
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

        flash()->success(__('common.updated_successfully'));
        return redirect(route('artist_info.index'));
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
