<?php

namespace App\Http\Controllers\Admin\Material;

use App\Http\Controllers\BaseController;
use App\Repositories\Backend\Material\MaterialInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MaterialController extends BaseController
{
    public $material;

    public function __construct(MaterialInterface $material)
    {
        $this->material = $material;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $material = $this->material;
        try {
            if ($request->show != 'all') {
                $data = $material->paginateMaterial($request->keyword, (int)$request->show, $request->status);
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
                $response = $material->allMaterial($request->keyword);
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
            'name' => ['required', 'max:255'],
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validator Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $this->material->createMaterial($data);
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
            return $this->sendResponse($this->material->getMaterial($id), 'Data Fetched Successfully');
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
            'name' => ['max:255'],
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validator Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $this->material->getMaterial($id)->update($data);
            DB::commit();
            return $this->sendResponse(true, 'Data Updated Successfully');
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
            $this->material->getMaterial($id)->delete();
            DB::commit();
            return $this->sendResponse(true, 'Data Deleted Successfully');
        } catch (Throwable $th) {
            return $this->sendError('Not Found', $th);
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
            $this->material->getMaterialTrashed($id)->restore();
            DB::commit();
            return $this->sendResponse(true, 'Data Restored Succesfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Error', $th);
        }
    }
}
