<?php

namespace App\Http\Controllers\Admin\ColorProduct;

use App\Http\Controllers\BaseController;
use App\Repositories\Backend\ColorProduct\ColorProductInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ColorProductController extends BaseController
{

    public $color;

    public function __construct(ColorProductInterface $color)
    {
        $this->color = $color;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $color = $this->color;
        try {
            if ($request->show != 'all') {
                $data = $color->paginateColorProduct($request->keyword, $request->hex, (int)$request->show, $request->status);
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
                $response = $color->allColorProduct($request->keyword, $request->hex);
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
            'name' => ['required', 'max:20'],
            'hex_color' => ['required', 'max:20'],
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validator Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            $this->color->createColorProduct($input);
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
            $color = $this->color->getColorProduct($id);
            return $this->sendResponse($color, 'Data Fetched Successfully');
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
            'name' => ['max:20'],
            'hex_color' => ['max:20'],
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validator Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $input = $request->all();
            $this->color->getColorProduct($id)->update($input);
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
            $this->color->getColorProduct($id)->delete();
            DB::commit();
            return $this->sendResponse(true, 'Data Deleted Succesfully');
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
    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->color->getColorProductTrashed($id)->restore();
            DB::commit();
            return $this->sendResponse(true, 'Data Restored Succesfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Error', $th);
        }
    }
}
