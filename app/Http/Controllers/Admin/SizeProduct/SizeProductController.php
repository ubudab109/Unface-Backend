<?php

namespace App\Http\Controllers\Admin\SizeProduct;

use App\Http\Controllers\BaseController;
use App\Repositories\Backend\SizeProduct\SizeProductInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SizeProductController extends BaseController
{

    public $size;

    public function __construct(SizeProductInterface $size)
    {
        $this->size = $size;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $size = $this->size;
        try {
            if ($request->show != 'all') {
                $data = $size->paginateSizeProduct($request->keyword, (int)$request->show, $request->status);
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
                $response = $size->allSizeProduct($request->keyword);
            }

            return $this->sendResponse($response, 'Data Fetched Succesfully');
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
            'name' => ['required', 'max:10'],
            'size' => ['required', 'max:100']
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validator Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            $this->size->createSizeProduct($input);
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
            $size = $this->size->getSizeProduct($id);
            return $this->sendResponse($size, 'Data Fetched Succesfully');
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
            'name' => ['max:10'],
            'size' => ['max:100']
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validator Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            $this->size->getSizeProduct($id)->update($input);
            DB::commit();
            return $this->sendResponse(true, 'Data Updated Succesfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Error', $th);
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
            $this->size->getSizeProduct($id)->delete();
            DB::commit();
            return $this->sendResponse(true, 'Data Deleted Succesfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Error', $th);
        }
    }

    /**
     * Restore Deleted item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->size->getSizeProductTrashed($id)->restore();
            DB::commit();
            return $this->sendResponse(true, 'Data Restored Succesfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Error', $th);
        }
    }
}
