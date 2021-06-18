<?php

namespace App\Repositories\Backend\SubCategory;

use App\Models\SubCategoryProduct;
use App\Repositories\BaseRepository;

class SubCategoryRepository extends BaseRepository implements SubCategoryInterface
{
    /**
     * @var ModelName
     */
    protected $model;

    public function __construct(SubCategoryProduct $model)
    {
        $this->model = $model;
    }

    public function allSubCategory($keyword = null, $category = null)
    {
        $subCat = $this->model->with('category:id,name')->with('model:id,src,model_id,model_type')->when($keyword != null, function ($query) use ($keyword) {
            $query->where('sub_category_product.name', 'LIKE', '%' . $keyword . '%');
        })->when($category != null, function ($query) use ($category) {
            $query->where('sub_category_product.category_id', $category);
        });
        return $subCat->get();
    }

    public function paginateSubCategory($keyword = null, $category = null, $page = null,$status = null)
    {
        $subCat = $this->model->with('category:id,name')->with('model:id,src,model_id,model_type')->when($keyword != null, function ($query) use ($keyword) {
            $query->where('sub_category_product.name', 'LIKE', '%' . $keyword . '%');
        })->when($category != null, function ($query) use ($category) {
            $query->where('sub_category_product.category_id', $category);
        })->when($status != null, function($query) use ($status) {
            if($status == 'all'){
                $query->withTrashed();
            }else if($status == 'trash'){
                $query->onlyTrashed();
            }
        });

        return $subCat->paginate($page != null ? $page : 10);
    }

    public function getSubCategory($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getSubCategoryTrashed($id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    public function createSubCategory(array $data)
    {
        $subCat = $this->model->create($data);
        return $subCat;
    }

    public function dataset()
    {
        return $this->model->select('id','name')->get();
    }
}
