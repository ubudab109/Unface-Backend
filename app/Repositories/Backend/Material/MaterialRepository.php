<?php

namespace App\Repositories\Backend\Material;

use App\Models\Material;
use App\Repositories\BaseRepository;

class MaterialRepository extends BaseRepository implements MaterialInterface
{
    /**
     * @var ModelName
     */
    protected $model;

    public function __construct(Material $model)
    {
        $this->model = $model;
    }

    public function allMaterial($keyword = null)
    {
        $material = $this->model->when($keyword != null, function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        });

        return $material->get();
    }

    public function getMaterial($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getMaterialTrashed($id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    public function paginateMaterial($keyword = null, $page = null, $status = null)
    {
        $material = $this->model->when($keyword != null, function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        })->when($status != null, function($query) use ($status) {
            if($status == 'all'){
                $query->withTrashed();
            }else if($status == 'trash'){
                $query->onlyTrashed();
            }
        });

        return $material->paginate($page != null ? $page : 10);
    }

    public function createMaterial(array $data)
    {
        return $this->model->create($data);
    }

    public function dataset()
    {
        return $this->model->select('id', 'name')->get();
    }
}
