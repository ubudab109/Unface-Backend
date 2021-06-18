<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\BaseController;
use App\Repositories\Backend\Category\CategoryProductInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CategoryController extends BaseController
{
    public $category;

    public function __construct(CategoryProductInterface $category)
    {
        $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = $this->category;

        try {
            if ($request->show != 'all') {
                $data = $category->paginateCategory($request->keyword, (int)$request->show, $request->status);
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
                $response = $category->allCategory($request->name, $request->desc);
            }

            return $this->sendResponse($response, 'Data fetched');
        } catch (Throwable $th) {
            return $this->sendError('Error', $th);
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
            'name' => ['required', 'max:255'],
            'src' => ['required', 'mimes:jpg,png', 'max:2048'],
        ]);

        if ($validate->fails()) {
            return $this->sendError('validator error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $categoryInput = $request->all();
            $category = $this->category->createCategory($categoryInput);
            $categoryInput['src'] = URL::to('storage/'. Storage::disk('public')->put("/category/img/" . $category->id, $categoryInput['src']));
            $category->model()->create($categoryInput);
            DB::commit();
            return $this->sendResponse(true, 'Category Added Succesfully');
        } catch (Throwable $th) {
            DB::rollback();
            return $this->sendError('error', $th);
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
            $category = $this->category->getCategory($id);
            $data = $category;
            $data['img'] = $category->model()->first();

            return $this->sendResponse($data, 'Data Fetched succesfully');
        } catch (Throwable $th) {
            return $this->sendError('error', $th);
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
            'name' => ['max:255'],
            'src' => ['mimes:jpg,png', 'max:2048'],
        ]);

        if ($validate->fails()) {
            return $this->sendError('validator error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $categoryInput = $request->all();
            $category = $this->category->getCategory($id);
            $image = $category->model()->first();
            $category->update($categoryInput);
            if ($request->src) {
                $categoryInput['src'] = URL::to('storage/'. Storage::disk('public')->put("/category/img/" . $category->id, $categoryInput['src']));
                if($image == null){
                    $category->model()->create($categoryInput);
                }else{
                    $image->update($categoryInput);    
                }
                
            }
            DB::commit();
            return $this->sendResponse(true, 'Category Updated Succesfully');
        } catch (Throwable $th) {
            DB::rollback();
            return $this->sendError('error', $th);
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
            $category = $this->category->getCategory($id);
            $category->delete();
            DB::commit();
            return $this->sendResponse(true, 'Category Deteled Succesfully');
        } catch (Throwable $th) {
            DB::rollback();
            return $this->sendError('error', $th);
        }
    }


    /**
     * Restore deleted category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $category = $this->category->getCategoryTrashed($id);
            $category->restore();
            DB::commit();
            return $this->sendResponse(true, 'Category Restore Succesfully');
        } catch (Throwable $th) {
            DB::rollback();
            return $this->sendError('error', $th);
        }
    }
}
