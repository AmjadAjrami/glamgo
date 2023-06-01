<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class  ContactUsController extends Controller
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
        return view('admin.contact_us.index');
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function indexTable(Request $request)
    {
        $contacts = ContactUs::query()->orderByDesc('created_at');
        return DataTables::of($contacts)->filter(function ($query) use ($request) {
            if ($request->title) {
                $query->where('title', 'like', '%' . $request->title . '%');
            }
            if ($request->user_name) {
                $query->where('name', 'like', '%' . $request->user_name . '%');
            }
            if ($request->user_mobile) {
                $query->where('mobile', 'like', '%' . $request->mobile . '%');
            }
        })->make(true);
    }

    /**
     * @param Request $request
     * @param $id
     * @return bool[]
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reply(Request $request, $id)
    {
        $rules = [
            'message' => 'required',
        ];

        $this->validate($request, $rules);

        $contact = ContactUs::query()->find($id);

        if (is_rtl($request->message)){
            $lang = 'ar';
        }else{
            $lang = 'en';
        }

        Mail::send('mail', ['content' => $request->message, 'lang' => $lang], function ($message) use ($contact, $lang){
            $subject = $lang == 'ar' ? 'رد على طلب المساعدة' : 'Contact Request Reply';
            $message->to($contact->email, $contact->name)->subject($subject);
            $message->from('info@glamgoapp.com', 'GLamgoapp');
        });

        $contact->update(['reply_message' => $request->message]);

        return ['status' => true];
    }
}
