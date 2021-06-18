<?php

namespace App\Repositories\Core\Product;

use App\Models\Product;
use App\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductInterface
{
    /**
     * @var ModelName
     */
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getAll($keyword = null, $catalogue = null, $subCategory = null, $category = null, $minPrice = null, $maxPrice = null)
    {
        $product = $this->model->with('catalogue:id,name')->with('model:id,src')->with('material:id,name')->with('SizeCollection.size:id,name,size')->with('SizeCollection.productColor.color:id,name,hex_color')
            ->when($keyword != null, function ($query) use ($keyword) {
                $query->where('product.name', 'LIKE', '%' . $keyword . '%');
            })
            ->when($catalogue != null, function ($query) use ($catalogue) {
                $query->whereIn('catalogue_id', [$catalogue]);
            })
            ->when($subCategory != null, function ($query) use ($subCategory) {
                $query->whereHas('catalogue.subCategory', function ($querySub) use ($subCategory) {
                    $querySub->whereIn('sub_category_product.id', [$subCategory]);
                });
            })
            ->when($category != null, function ($query) use ($category) {
                $query->whereHas('catalogue.subCategory.category', function ($queryCat) use ($category) {
                    $queryCat->whereIn('category_product.id', [$category]);
                });
            })->when($maxPrice != null && $minPrice != null, function ($query) use ($minPrice, $maxPrice) {
                $query->whereHas('SizeCollection', function ($queryPrice) use ($minPrice, $maxPrice) {
                    $queryPrice->whereBetween('price', [$minPrice, $maxPrice]);
                });
            });

        return $product->get();
    }

    public function paginateProduct($keyword = null, $catalogue = null, $subCategory = null, $category = null, $minPrice = null, $maxPrice = null, $show = null)
    {
        $product = $this->model->with('catalogue:id,name,sub_cat_id')->with('model:id,src,model_id,model_type')->with('material:id,name')->with('SizeCollection.size:id,name,size')->with('SizeCollection.productColor.color:id,name,hex_color')
            ->when($keyword != null, function ($query) use ($keyword) {
                $query->where('product.name', 'LIKE', '%' . $keyword . '%');
            })
            ->when($catalogue != null, function ($query) use ($catalogue) {
                $query->whereIn('catalogue_id', [$catalogue]);
            })
            ->when($subCategory != null, function ($query) use ($subCategory) {
                $query->whereHas('catalogue.subCategory', function ($querySub) use ($subCategory) {
                    $querySub->whereIn('sub_category_product.id', [$subCategory]);
                });
            })
            ->when($category != null, function ($query) use ($category) {
                $query->whereHas('catalogue.subCategory.category', function ($queryCat) use ($category) {
                    $queryCat->whereIn('category_product.id', [$category]);
                });
            })->when($maxPrice != null && $minPrice != null, function ($query) use ($minPrice, $maxPrice) {
                $query->whereHas('SizeCollection', function ($queryPrice) use ($minPrice, $maxPrice) {
                    $queryPrice->whereBetween('price', [$minPrice, $maxPrice]);
                });
            });

        return $product->paginate($show != null ? $show : 10);
    }

    public function getProduct($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getOnlyProduct($id)
    {
        return $this->model->with('catalogue:id,name')->findOrFail($id)->makeHidden(['sub_category', 'category']);
    }

    public function getProductTrashed($id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    public function createProduct(array $data)
    {
        return $this->model->create($data);
    }
}
