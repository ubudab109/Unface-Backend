<?php

namespace App\Repositories\Backend\Catalogue;

use App\Models\CatalogueProduct;
use App\Repositories\BaseRepository;

class CatalogueRepository extends BaseRepository implements CatalogueInterface
{
    /**
     * @var ModelName
     */
    protected $model;

    public function __construct(CatalogueProduct $model)
    {
        $this->model = $model;
    }

    public function allCatalogue($keyword = null, $subcategory = null, $category = null)
    {
        $catalogue = $this->model->with('subCategory:id,name,category_id')->with('subCategory.category:id,name')->with('model:id,src,model_id,model_type')->when($keyword != null, function ($query) use ($keyword) {
            $query->where('catalogue_product.name', 'LIKE', '%' . $keyword . '%');
        })->when($category != null, function ($query) use ($category) {
            $query->whereHas('subCategory', function ($query) use ($category) {
                $query->where('category_id', $category);
            });
        })->when($subcategory != null, function ($query) use ($subcategory) {
            $query->where('catalogue_product.sub_cad_id', $subcategory);
        });

        return $catalogue->get();
    }

    public function paginateCatalogue($keyword = null, $subcategory = null, $category = null, $show = null, $status = null)
    {
        $catalogue = $this->model->with(array('subCategory' => function ($query) {
            $query->select('id', 'name', 'category_id')->with('category:id,name');
        }))->with('model:id,src,model_id,model_type')->when($keyword != null, function ($query) use ($keyword) {
            $query->where('catalogue_product.name', 'LIKE', '%' . $keyword . '%');
        })
            ->when($category != null, function ($query) use ($category) {
                $query->whereHas('subCategory', function ($query) use ($category) {
                    $query->where('category_id', $category);
                });
            })
            ->when($subcategory != null, function ($query) use ($subcategory) {
                $query->where('catalogue_product.sub_cat_id', $subcategory);
            })
            ->when($status != null, function ($query) use ($status) {
                if ($status == 'all') {
                    $query->withTrashed();
                } else if ($status == 'trash') {
                    $query->onlyTrashed();
                }
            });

        return $catalogue->paginate($show != null ? $show : 10);
    }

    public function getCatalogue($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getCatalogueTrashed($id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    public function createCatalogue(array $data)
    {
        $catalogue = $this->model->create($data);
        return $catalogue;
    }

    public function dataset()
    {
        return $this->model->select('id', 'name')->get();
    }
}
