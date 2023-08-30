<?php

namespace App\Http\Controllers\Salon;

use App\Http\Controllers\Controller;
use App\Models\BookingTime;
use App\Models\SalonSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingTimesController extends Controller
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
        $time_from = SalonSetting::query()->where('salon_id', auth('salon')->id())->where('key', 'time_from')->where('type', request()->type)->first();
        $time_to = SalonSetting::query()->where('salon_id', auth('salon')->id())->where('key', 'time_to')->where('type', request()->type)->first();
        $added_minutes = SalonSetting::query()->where('salon_id', auth('salon')->id())->where('key', 'reservation_duration')->where('type', request()->type)->first();
        $off_days = SalonSetting::query()->where('salon_id', auth('salon')->id())->where('key', 'off_days')->where('type', request()->type)->first();

        $days = [
            '1' => __('common.sunday'),
            '2' => __('common.monday'),
            '3' => __('common.tuesday'),
            '4' => __('common.wednesday'),
            '5' => __('common.thursday'),
            '6' => __('common.friday'),
            '7' => __('common.saturday'),
        ];

        return view('salon.booking_times.index', compact('time_from', 'time_to', 'added_minutes', 'off_days', 'days'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $rules = [
            'booking_time' => 'array',
            'booking_time.*' => 'required',
        ];

        $this->validate($request, $rules);

        if ($request->booking_time == null) {
            BookingTime::query()->where('salon_id', auth('salon')->id())->where('day', $request->day)->where('type', $request->type)->delete();

            if ($request->ajax()) {
                return response()->json(['status' => true]);
            }

            flash()->success(__('common.updated_successfully'));
            return redirect(route('booking_times.index'));
        }

        BookingTime::query()->where('salon_id', auth('salon')->id())->where('day', $request->day)->where('type', $request->type)
            ->whereNotIn('id', $request->booking_time['time_id'])->update([
                'status' => 3
            ]);

        for ($i = 0; $i < count($request->booking_time['from']); $i++) {
            if ($request->booking_time['time_id'][$i] == 0) {
                if (isset($request->booking_time['is_reserved'])) {
                    BookingTime::query()->insert([
                        'salon_id' => auth('salon')->id(),
                        'day' => $request->day,
                        'from' => $request->booking_time['from'][$i],
                        'to' => $request->booking_time['to'][$i],
                        'period' => 0,
                        'is_reserved' => isset($request->booking_time['is_reserved'][$i]) ? $request->booking_time['is_reserved'][$i] : 0,
                        'type' => $request->type,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                } else {
                    BookingTime::query()->insert([
                        'salon_id' => auth('salon')->id(),
                        'day' => $request->day,
                        'from' => $request->booking_time['from'][$i],
                        'to' => $request->booking_time['to'][$i],
                        'period' => 0,
                        'is_reserved' => 0,
                        'type' => $request->type,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            } else {
                if (isset($request->booking_time['is_reserved'])) {
                    BookingTime::query()->find($request->booking_time['time_id'][$i])->update([
                        'salon_id' => auth('salon')->id(),
                        'day' => $request->day,
                        'from' => $request->booking_time['from'][$i],
                        'to' => $request->booking_time['to'][$i],
                        'period' => 0,
                        'is_reserved' => $request->booking_time['is_reserved'][$i],
                        'type' => $request->type,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                } else {
                    BookingTime::query()->find($request->booking_time['time_id'][$i])->update([
                        'salon_id' => auth('salon')->id(),
                        'day' => $request->day,
                        'from' => $request->booking_time['from'][$i],
                        'to' => $request->booking_time['to'][$i],
                        'period' => 0,
                        'is_reserved' => 0,
                        'type' => $request->type,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect(route('booking_times.index'));
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function salon_day_booking_time(Request $request)
    {
        $rules = [
            'salon_id' => 'required',
            'day' => 'required',
        ];

        $this->validate($request, $rules);

        $times = BookingTime::query()->where('salon_id', $request->salon_id)->where('day', $request->day)->where('type', $request->type)->where('status', 1)->get();

        return ['status' => true, 'times' => $times];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function time_settings(Request $request)
    {
        if (!isset($request->settings['off_days'])) {
            SalonSetting::query()->where('salon_id', auth('salon')->id())->where('key', 'off_days')->where('type', $request->type)->delete();
        }

        foreach ($request->settings as $key => $value) {
            $setting = SalonSetting::query()->where('salon_id', auth('salon')->id())->where('key', $key)->where('type', $request->type)->first();
            if ($setting) {
                if ($key == 'off_days') {
                    $setting->update([
                        'value' => json_encode($value),
                    ]);
                } else {
                    $setting->update([
                        'value' => $value,
                    ]);
                }
            } else {
                if ($key == 'off_days') {
                    SalonSetting::query()->insert([
                        'salon_id' => auth('salon')->id(),
                        'key' => $key,
                        'value' => json_encode($value),
                        'type' => $request->type,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                } else {
                    SalonSetting::query()->insert([
                        'salon_id' => auth('salon')->id(),
                        'key' => $key,
                        'value' => $value,
                        'type' => $request->type,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add_times($type)
    {
        $time_from = SalonSetting::query()->where('salon_id', auth('salon')->id())->where('key', 'time_from')->where('type', $type)->first();
        $time_to = SalonSetting::query()->where('salon_id', auth('salon')->id())->where('key', 'time_to')->where('type', $type)->first();
        $added_minutes = SalonSetting::query()->where('salon_id', auth('salon')->id())->where('key', 'reservation_duration')->where('type', $type)->first();
        $off_days = SalonSetting::query()->where('salon_id', auth('salon')->id())->where('key', 'off_days')->where('type', $type)->first();

        if (!$time_from || !$time_to || !$added_minutes) {
            flash()->success(__('common.add_time_settings_first'));
            return redirect()->back();
        }

        $days_off = [];
        if ($off_days != null) {
            $days_off = json_decode($off_days->value);
        }

        $added_time = '+' . $added_minutes->value . ' minutes';

        $time = strtotime($time_from->value);
        $new_time = strtotime($time_from->value);
        $end_time = strtotime($time_to->value);

        $times = [];
        for ($i = 1; $i < 8; $i++) {
            if (!in_array($i, $days_off)) {
                $new_time = $time;
                while ($new_time < $end_time) {
                    $start_time = date('H:i', $new_time);
                    $new_time = strtotime($added_time, $new_time);
                    $times[] = [
                        'salon_id' => auth('salon')->id(),
                        'day' => $i,
                        'from' => $start_time,
                        'to' => date('H:i', $new_time),
                        'is_reserved' => 0,
                        'type' => $type
                    ];
                }
            }
        }

        BookingTime::query()->where('salon_id', auth('salon')->id())->where('type', $type)->update([
            'status' => 0
        ]);

        foreach ($times as $item) {
            if (!in_array($item['day'], $days_off)) {
                BookingTime::query()->insert([
                    'salon_id' => $item['salon_id'],
                    'day' => $item['day'],
                    'from' => $item['from'],
                    'to' => $item['to'],
                    'is_reserved' => $item['is_reserved'],
                    'type' => $type,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                $day_times = BookingTime::query()->where('salon_id', $item['salon_id'])->where('day', $item['day'])->where('type', $type)->first();
                if ($day_times) {
                    $day_times->delete();
                }
            }
        }

        flash()->success(__('common.done_successfully'));
        return redirect()->back();
    }
}
