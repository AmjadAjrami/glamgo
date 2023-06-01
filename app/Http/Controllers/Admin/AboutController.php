<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AboutController extends Controller
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
        $page = Page::query()->where('type', 1)->exists();
        return view('admin.pages.about_us.index', compact('page'));
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
        $rules = [];

        foreach (locales() as $key => $value) {
            $rules['title_' . $key] = 'required|string';
            $rules['description_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        $data['type'] = 1;

        foreach (locales() as $key => $value) {
            $data[$key] = ['title' => $request->get('title_' . $key), 'description' => $request->get('description_' . $key)];
        }

        Page::query()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('about_app.index'));
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
        $page = Page::query()->find($id);

        $rules = [];

        foreach (locales() as $key => $value) {
            $rules['title_' . $key] = 'required|string';
            $rules['description_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        $data['type'] = 1;

        foreach (locales() as $key => $value) {
            $data[$key] = ['title' => $request->get('title_' . $key), 'description' => $request->get('description_' . $key)];
        }

        $page->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect(route('about_app.index'));
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

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function indexTable(Request $request)
    {
        $pages = Page::query()->where('type', 1)->orderByDesc('created_at');
        return DataTables::of($pages)
            ->addColumn('action', function ($page) {
                $data_attr = '';
                $string = '';
                foreach (locales() as $key => $value) {
                    $data_attr .= 'data-title_' . $key . '="' . $page->translate($key)->title . '" ';
                    $string .= '<div hidden id="description_' . $key . '_' . $page->id . '">' . $page->translate($key)->description . '</div>';
                }

                if (auth()->user()->hasAnyPermission(['edit_about_us'])) {
                    $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $page->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
                }


                return $string;
            })->make(true);
    }
}
