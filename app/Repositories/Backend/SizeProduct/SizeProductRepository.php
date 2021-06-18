<?php

namespace App\Repositories\Backend\SizeProduct;

use App\Models\Size;
use App\Repositories\BaseRepository;

class SizeProductRepository extends BaseRepository implements SizeProductInterface
{
    /**
     * @var ModelName
     */
    protected $model;

    public function __construct(Size $model)
    {
        $this->model = $model;
    }

    public function allSizeProduct($keyword = null)
    {
        $size = $this->model->when($keyword != null, function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%')->orWhere('size', 'LIKE', '%' . $keyword . '%');
        })->get();

        return $size;
    }

    public function getSizeProduct($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getSizeProductTrashed($id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    public function paginateSizeProduct($keyword = null, $page = null, $status = null)
    {
        $size = $this->model->when($keyword != null, function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%')->orWhere('size', 'LIKE', '%' . $keyword . '%');
        })->when($status != null, function ($query) use ($status) {
            if ($status == 'all') {
                $query->withTrashed();
            } else if ($status == 'trash') {
                $query->onlyTrashed();
            }
        })->paginate($page != null ? $page : 10);

        return $size;
    }

    public function createSizeProduct(array $data)
    {
        return $this->model->create($data);
    }

    public function dataset()
    {
        return $this->model->select('id', 'name', 'size')->get();
    }
}
