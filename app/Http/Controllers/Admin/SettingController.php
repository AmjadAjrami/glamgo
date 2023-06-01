<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingController extends Controller
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
        $email = Setting::query()->where('key', 'email')->first();
        $mobile = Setting::query()->where('key', 'mobile')->first();
        $website = Setting::query()->where('key', 'website')->first();
        $facebook_link = Setting::query()->where('key', 'facebook_link')->first();
        $twitter_link = Setting::query()->where('key', 'twitter_link')->first();
        $instagram_link = Setting::query()->where('key', 'instagram_link')->first();
        $snapchat_link = Setting::query()->where('key', 'snapchat_link')->first();
        $whatsapp_link = Setting::query()->where('key', 'whatsapp_link')->first();
        $evacuation_responsibility = Setting::query()->where('key', 'evacuation_responsibility')->first();
        $vat = Setting::query()->where('key', 'vat')->first();
        $delivery_price = Setting::query()->where('key', 'delivery_price')->first();
        $app_percentage = Setting::query()->where('key', 'app_percentage')->first();
        $app_percentage_store = Setting::query()->where('key', 'app_percentage_store')->first();

        return view('admin.settings.index', compact('email', 'mobile', 'website', 'facebook_link', 'twitter_link', 'instagram_link',
            'snapchat_link', 'whatsapp_link', 'evacuation_responsibility', 'vat', 'app_percentage', 'delivery_price', 'app_percentage_store'));
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
//        dd($request->all());
        foreach ($request->settings as $key => $value) {
            $setting = Setting::query()->where('key', $key)->first();
            if ($key == 'evacuation_responsibility'){
                if ($setting) {
                    $setting->update([
                        'value' => $value['ar'],
                        'value_en' => $value['en'],
                    ]);
                } else {
                    Setting::query()->insert([
                        'key' => $key,
                        'value' => $value['ar'],
                        'value_en' => $value['en'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }else{
                if ($setting) {
                    $setting->update([
                        'value' => $value,
                    ]);
                } else {
                    Setting::query()->insert([
                        'key' => $key,
                        'value' => $value,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }

        flash()->success(__('common.updated_successfully'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
