<?php

namespace App\Http\Controllers\Admin\SubCategory;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\SubCategory\SubCategoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SubCategoryController extends BaseController
{
    public $subCategory;

    public function __construct(SubCategoryInterface $subCategory)
    {
        $this->subCategory = $subCategory;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subCategory = $this->subCategory;
        try {
            if ($request->show != 'all') {
                $data = $subCategory->paginateSubCategory($request->keyword, $request->category, (int)$request->show, $request->status);
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
                $response = $subCategory->allSubCategory($request->keyword, $request->category);
            }

            return $this->sendResponse($response, 'Data Fetched Successfully');
        } catch (Throwable $th) {
            $this->sendError('Error', $th);
        }
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
            'category_id' => ['required', 'integer'],
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
            'src' => ['required', 'mimes:jpg,png', 'max:2048'],
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validator Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $subCategoryInput = $request->all();
            $subCategory = $this->subCategory->createSubCategory($subCategoryInput);
            $subCategoryInput['src'] = URL::to('storage/'. Storage::disk('public')->put("/subcategory/img/" . $subCategory->id, $subCategoryInput['src']));
            $subCategory->model()->create($subCategoryInput);
            DB::commit();
            return $this->sendResponse(true, 'Data Created Succesfully');
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
            $subCategory = $this->subCategory->getSubCategory($id);
            $data = $subCategory;
            $data['category'] = $subCategory->category()->select('id', 'name')->first();
            $data['img'] = $subCategory->model()->select('id', 'src')->first();

            return $this->sendResponse($data, 'Data Fetched Succesfully');
        } catch (Throwable $th) {
            return $this->sendError('Not Found', $th);
        }
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
        $validate = Validator::make($request->all(), [
            'category_id' => ['integer'],
            'name' => ['max:255'],
            'description' => ['nullable'],
            'src' => ['mimes:jpg,png', 'max:2048']
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validator Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $subCategory = $this->subCategory->getSubCategory($id);
            $subCategoryInput = $request->all();
            $image = $subCategory->model()->first();
            $subCategory->update($subCategoryInput);
            if ($request->src) {
                $subCategoryInput['src'] = URL::to('storage/'. Storage::disk('public')->put("/subcategory/img/" . $subCategory->id, $subCategoryInput['src']));
                if($image == null){
                    $subCategory->model()->create($subCategoryInput);
                }else{
                    $image->update($subCategoryInput);
                }
            }

            DB::commit();
            return $this->sendResponse(true, 'Data Update Succesfully');
        } catch (Throwable $th) {
            DB::rollback();
            return $this->sendError('Not Found', $th);
        }
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
            $this->subCategory->getSubCategory($id)->delete();
            DB::commit();
            return $this->sendResponse(true, 'Data Deleted Successfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Not Found', $th);
        }
    }

    /**
     * Restore Deleted Data
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->subCategory->getSubCategoryTrashed($id)->restore();
            DB::commit();
            return $this->sendResponse(true, 'Data Restore Successfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Not Found', $th);
        }
    }
}
