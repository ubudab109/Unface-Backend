<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\BaseController;
use App\Repositories\Core\Product\ProductInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProductController extends BaseController
{
    public $product;

    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $product = $this->product;
        if ($request->show != 'all') {
            $data = $product->paginateProduct($request->keyword, $request->catalogue, $request->sub_category, $request->category, $request->min_price, $request->max_price, (int)$request->show);
            $response = [
                'pagination' => [
                    'total'         => $data->total(),
                    'showed'        => $data->count(),
                    'per_page'      => $data->perPage(),
                    'current_page'  => $data->currentPage(),
                    'last_page'     => $data->lastPage(),
                    'from'          => $data->firstItem(),
                    'to'            => $data->lastItem(),
                ],
                'data' => $data->items(),
            ];
        } else {
            $response = $product->getAll($request->keyword, $request->catalogue, $request->sub_category, $request->category, $request->min_price, $request->max_price);
        }

        return $this->sendResponse($response, 'Data Fetched Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'catalogue_id' => ['required', 'integer'],
            'material_id' => ['required', 'integer'],
            'name' => ['required', 'max:255'],
            'description' => ['required'],
            'discount' => ['nullable'],
            'discount_type' => ['nullable'],
            'src[*]' => ['required', 'array', 'mimes:jpg,jpeg,png', 'max:2048'],
            'collection' => ['required', 'array'],
            'collection[*]color[*][]' => ['required', 'array'],
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validate Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            $product = $this->product->createProduct($input);
            foreach ($input['src'] as $key => $value) {
                $input['src'][$key] = URL::to('storage/' . Storage::disk('public')->put("/product/img/" . $product->id, $input['src'][$key]));
                $product->model()->create([
                    'src' => $input['src'][$key]
                ]);
            }
            foreach ($input['collection'] as $key => $value) {
                $sizeData = [
                    'size_id' => $input['collection'][$key]['size_id'],
                    'stock' => $input['collection'][$key]['stock'],
                    'price' => $input['collection'][$key]['price'],
                ];
                $sizeData = $product->SizeCollection()->create($sizeData);
                foreach ($input['collection'][$key]['color'] as $index => $value) {
                    $colorData = [
                        'color_id' => $input['collection'][$key]['color'][$index]['color_id'],
                        'stock' => $input['collection'][$key]['color'][$index]['stock']
                    ];
                    $sizeData->productColor()->create($colorData);
                }
            }
            DB::commit();
            return $this->sendResponse(true, 'Data Created Successfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Error', $th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = $this->product->getProduct($id);

            $response = $data;
            $response['material'] = $data->material()->select('id', 'name')->first();
            $response['collection'] = $data->SizeCollection()->with('size:id,name,size')->with('productColor.color:id,name,hex_color')->get();
            $response['img'] = $data->model()->select('id', 'model_id', 'model_type', 'src')->get();

            return $this->sendResponse($response, 'Data Fetched Successfully');
        } catch (Throwable $th) {
            return $this->sendError('Error', $th);
        }
    }


    /**
     * Display the specified product without collection.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showProduct($id)
    {
        try {
            $data = $this->product->getOnlyProduct($id);
            return $this->sendResponse($data, 'Data Fetched Successfully');
        } catch (Throwable $th) {
            return $this->sendError('Error', $th);
        }
    }

    /**
     * Update the specified resource in storage.
     * Update only product, not collection
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'catalogue_id' => ['integer'],
            'material_id' => ['integer'],
            'name' => ['max:255'],
            'description' => [''],
            'discount' => ['nullable'],
            'discount_type' => ['nullable'],
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validate Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            $this->product->getProduct($id)->update($input);
            DB::commit();
            return $this->sendResponse(true, 'Product Updated Successfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Error', $th);
        }
    }


    public function getSizeCollection($id)
    {
        try {
            $product = $this->product->getProduct($id);
            $data = $product->SizeCollection()->with('size:id,name,size')->with('productColor.color:id,name,hex_color')->get();
            return $this->sendResponse($data, 'Data Fetched Succesfully');
        } catch (Throwable $th) {
            return $this->sendError('Not Found', $th);
        }
    }

    public function updateCollection(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'stock' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'color[*]' => ['array'],
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validation Error', $validate->errors());
        }

        $input = $request->all();

        $product = $this->product->getProduct($id);

        $size = $product->SizeCollection()->where('id', $request->id)->first();
        $size->update($input);

        if ($request->color) {
            foreach ($input['color'] as $index => $value) {
                $color = $size->productColor()->where('id', $input['color'][$index]['id'])->first();
                $color->updateOrCreate(
                    ['id' => $input['color'][$index]['id']],
                    [
                        'color_id' => $input['color'][$index]['id'] || $input['color'][$index]['id'] !== null ? $color->color_id : $input['color'][$index]['color_id'],
                        'stock' => $input['color'][$index]['stock'],
                    ],
                );
            }
        }

        return $this->sendResponse(true, 'Data Update Succesfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->product->getProduct($id)->delete();
            DB::commit();
            return $this->sendResponse(true, 'Data Deleted Successfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Error', $th);
        }
    }

    /**
     * restore deleted resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->product->getProductTrashed($id)->restore();
            DB::commit();
            return $this->sendResponse(true, 'Data Restore Successfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Error', $th);
        }
    }
}
