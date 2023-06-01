<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Salon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
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
        $categories = Category::query()->where('status', 1)->where('type', 3)->get();
        return view('admin.products.index', compact('categories'));
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
            'images' => 'required|array',
            'images.*' => 'required',
            'category_id' => 'required',
            'salon_id' => 'nullable',
            'price' => 'required',
            'type' => 'required',
            'stock' => 'required',
            'cover_image' => 'required',
            'has_discount' => 'nullable',
            'discount_price' => 'required_if:has_discount,=,1',
        ];

        foreach (locales() as $key => $value) {
            $rules['title_' . $key] = 'required|string';
            $rules['description_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        $data['category_id'] = $request->category_id;
        $data['salon_id'] = $request->salon_id ?? null;
        $data['price'] = $request->price;
        $data['discount_price'] = $request->discount_price ?? null;
        $data['stock'] = $request->stock;
        $data['type'] = $request->type;
        $data['has_discount'] = $request->has_discount == null ? 0 : 1;

        foreach (locales() as $key => $value) {
            $data[$key] = ['title' => $request->get('title_' . $key), 'description' => $request->get('description_' . $key)];
        }

        if ($request->cover_image) {
            $cover_image = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $cover_image .= substr($request->cover_image->getClientOriginalName(), strrpos($request->cover_image->getClientOriginalName(), '.'));
            $cover_image = $request->cover_image->move(public_path('images'), $cover_image);
            $data['cover_image'] = $cover_image->getBasename();
        }

        $product = Product::query()->create($data);

        if ($request->images) {
            foreach ($request->images as $image) {
                $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
                $filename .= substr($image->getClientOriginalName(), strrpos($image->getClientOriginalName(), '.'));
                $filename = $image->move(public_path('images'), $filename);

                ProductImage::query()->insert([
                    'product_id' => $product->id,
                    'image' => $filename->getBasename(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('products.index'));
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
    public function get_categories_and_salons($type)
    {
        $categories = Category::query()->where('status', 1)->where('type', 3)->where('user_type', $type)->get();
        $salons = Salon::query()->where('status', 1)->where('type', $type)->get();
        return ['status' => true, 'categories' => $categories, 'salons' => $salons];
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
        $product = Product::query()->find($id);

        $rules = [
            'images' => 'nullable|array',
            'images.*' => 'nullable',
            'category_id' => 'required',
            'salon_id' => 'nullable',
            'price' => 'required',
            'type' => 'required',
            'stock' => 'required',
            'cover_image' => 'nullable',
            'has_discount' => 'nullable',
            'discount_price' => 'required_if:has_discount,=,1',
        ];

        foreach (locales() as $key => $value) {
            $rules['title_' . $key] = 'required|string';
            $rules['description_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        $data = [];
        $data['category_id'] = $request->category_id;
        if ($request->add_to_salon == 1) {
            $data['salon_id'] = $request->salon_id ?? null;
        } else {
            $data['salon_id'] = null;
        }
        $data['price'] = $request->price;
        $data['discount_price'] = $request->discount_price ?? null;
        $data['stock'] = $request->stock;
        $data['type'] = $request->type;
        $data['has_discount'] = $request->has_discount == null ? 0 : 1;

        foreach (locales() as $key => $value) {
            $data[$key] = ['title' => $request->get('title_' . $key), 'description' => $request->get('description_' . $key)];
        }

        if ($request->cover_image) {
            $cover_image = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $cover_image .= substr($request->cover_image->getClientOriginalName(), strrpos($request->cover_image->getClientOriginalName(), '.'));
            $cover_image = $request->cover_image->move(public_path('images'), $cover_image);
            $data['cover_image'] = $cover_image->getBasename();
        }

        $product->update($data);

        if ($request->preloaded) {
            ProductImage::query()->where('product_id', $product->id)->whereNotIn('id', $request->preloaded)->delete();
        } else {
            ProductImage::query()->where('product_id', $product->id)->delete();
        }

        if ($request->images) {
            foreach ($request->images as $image) {
                $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
                $filename .= substr($image->getClientOriginalName(), strrpos($image->getClientOriginalName(), '.'));
                $filename = $image->move(public_path('images'), $filename);

                ProductImage::query()->insert([
                    'product_id' => $product->id,
                    'image' => $filename->getBasename(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect(route('products.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Product::query()->whereIn('id', explode(',', $id))->delete();
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
        $product = Product::query()->find($id);
        if ($product->status == 1) {
            $product->status = 0;
            $product->update();
            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $product->status = 1;
            $product->update();
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
            Product::query()->whereIn('id', explode(',', $request->ids))
                ->update(['status' => $request->status]);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
        return response()->json(['status' => true]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function indexTable(Request $request)
    {
        $products = Product::query()->orderByDesc('created_at');
        return DataTables::of($products)->filter(function ($query) use ($request) {

        })->addColumn('action', function ($product) {
            $data_attr = '';
            foreach (locales() as $key => $value) {
                $data_attr .= 'data-title_' . $key . '="' . $product->translate($key)->title . '" ';
                $data_attr .= 'data-description_' . $key . '="' . $product->translate($key)->description . '" ';
            }
            $data_attr .= 'data-category_id="' . $product->category_id . '" ';
            $data_attr .= 'data-salon_id="' . $product->salon_id . '" ';
            $data_attr .= 'data-price="' . $product->price . '" ';
            $data_attr .= 'data-discount_price="' . $product->discount_price . '" ';
            $data_attr .= 'data-stock="' . $product->stock . '" ';
            $data_attr .= 'data-type="' . $product->type . '" ';
            $data_attr .= "data-images='" . $product->product_images . "' ";
            $data_attr .= "data-cover_image='" . $product->image . "' ";
            $data_attr .= 'data-has_discount="' . $product->has_discount . '" ';

            $string = '';

            if (auth()->user()->hasAnyPermission(['edit_product_status'])) {
                $string .= '<button onclick="add_remove(' . $product->id . ')" id="btn_' . $product->id . '" class="btn ' . ($product->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($product->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['edit_product'])) {
                $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $product->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['delete_product'])) {
                $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $product->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';
            }

            return $string;
        })->make(true);
    }
}
