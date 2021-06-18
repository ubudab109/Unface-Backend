<?php

namespace App\Http\Controllers\Admin\Catalogue;

use App\Http\Controllers\BaseController;
use App\Repositories\Backend\Catalogue\CatalogueInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CatalogueController extends BaseController
{
    public $catalogue;


    public function __construct(CatalogueInterface $catalogue)
    {
        $this->catalogue = $catalogue;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $catalogue = $this->catalogue;

        try {
            if ($request->show != 'all') {
                $data = $catalogue->paginateCatalogue($request->keyword, $request->subcategory, $request->category, (int)$request->show, $request->status);
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
                $response = $catalogue->allCatalogue($request->keyword, $request->subcategory, $request->category);
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
            'sub_cat_id' => ['required', 'integer'],
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
            'src' => ['required', 'mimes:jpg,png', 'max:2048']
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validator Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $catalogueInput = $request->all();
            $catalogue = $this->catalogue->createCatalogue($catalogueInput);
            $catalogueInput['src'] = URL::to('storage/'. Storage::disk('public')->put("/catalogue/img/" . $catalogue->id, $catalogueInput['src']));
            $catalogue->model()->create($catalogueInput);
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
        try{
            $catalogue = $this->catalogue->getCatalogue($id);
            $data = $catalogue;
            $data['sub_category'] = $catalogue->subCategory()->select('id', 'name')->first();
            $data['img'] = $catalogue->model()->select('id', 'src')->first();

            return $this->sendResponse($data, 'Data Fetched Succesfully');
        }catch (\Throwable $th){
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
            'sub_cat_id' => ['integer'],
            'name' => ['max:255'],
            'description' => ['nullable'],
            'src' => ['mimes:jpg,png', 'max:2048']
        ]);

        if ($validate->fails()) {
            return $this->sendError('Validator Error', $validate->errors());
        }

        DB::beginTransaction();
        try {
            $catalogueInput = $request->all();
            $catalogue = $this->catalogue->getCatalogue($id);
            $catalogue->update($catalogueInput);
            $image = $catalogue->model()->first();
            if ($request->src) {
                $catalogueInput['src'] = URL::to('storage/'. Storage::disk('public')->put("/catalogue/img/" . $catalogue->id, $catalogueInput['src']));
                
                if($image == null){
                    $catalogue->model()->create($catalogueInput);
                }else{
                    $image->update($catalogueInput);    
                }
                
            }
            DB::commit();
            return $this->sendResponse(true, 'Data Update Succesfully');
        } catch (Throwable $th) {
            DB::rollback();
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
            $this->catalogue->getCatalogue($id)->delete();
            DB::commit();
            return $this->sendResponse(array('succes' => 1), 'Data Deleted Succesfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Not Found', $th);
        }
    }

    /**
     * Restore Deleted Data.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->catalogue->getCatalogueTrashed($id)->restore();
            DB::commit();
            return $this->sendResponse(array('succes' => 1), 'Data Restore Succesfully');
        } catch (Throwable $th) {
            DB::rollBack();
            return $this->sendError('Not Found', $th);
        }
    }
}
