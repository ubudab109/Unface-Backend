<?php

namespace App\Repositories\Backend\ColorProduct;

use App\Models\Color;
use App\Repositories\BaseRepository;

class ColorProductRepository extends BaseRepository implements ColorProductInterface
{
    /**
     * @var ModelName
     */
    protected $model;

    public function __construct(Color $model)
    {
        $this->model = $model;
    }

    public function allColorProduct($keyword = null, $hex = null)
    {
        $color = $this->model->when($keyword != null, function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        })->when($hex != null, function ($query) use ($hex) {
            $query->where('hex_color', $hex);
        })->get();

        return $color;
    }

    public function getColorProduct($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getColorProductTrashed($id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    public function paginateColorProduct($keyword = null, $hex = null, $page = null, $status = null)
    {
        $color = $this->model->when($keyword != null, function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        })->when($hex != null, function ($query) use ($hex) {
            $query->where('hex_color', $hex);
        })->when($status != null, function ($query) use ($status) {
            if ($status == 'all') {
                $query->withTrashed();
            } else if ($status == 'trash') {
                $query->onlyTrashed();
            }
        })->paginate($page != null ? $page : 10);

        return $color;
    }

    public function createColorProduct(array $data)
    {
        return $this->model->create($data);
    }

    public function dataset()
    {
        return $this->model->select('id', 'name', 'hex_color')->get();
    }
}
