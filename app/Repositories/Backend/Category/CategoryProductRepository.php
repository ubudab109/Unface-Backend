<?php

namespace App\Repositories\Backend\Category;

use App\Models\CategoryProduct;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CategoryProductRepository extends BaseRepository implements CategoryProductInterface
{
    /**
     * @var ModelName
     */
    protected $model;

    public function __construct(CategoryProduct $model)
    {
        $this->model = $model;
    }

    public function allCategory($keyword = null)
    {
        $category = $this->model->with('model:id,src,model_id,model_type')->when($keyword != null, function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%')->orWhere('description', 'LIKE', '%' . $keyword . '%');
        })->get();

        return $category;
    }


    public function paginateCategory($keyword = null, $page = null, $status = null)
    {

        $category = $this->model->with('model:id,src,model_id,model_type')->when($keyword != null, function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%')->orWhere('description', 'LIKE', '%' . $keyword . '%');
        })->when($status != null, function($query) use ($status) {
            if($status == 'all'){
                $query->withTrashed();
            }else if($status == 'trash'){
                $query->onlyTrashed();
            }
        });

        return $category->paginate($page != null ? $page : 10);
    }

    public function getCategory($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getCategoryTrashed($id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    public function createCategory(array $data)
    {
        $category = $this->model->create($data);
        return $category;
    }

    public function dataset()
    {
        return $this->model->select('id','name')->get();
    }
}
