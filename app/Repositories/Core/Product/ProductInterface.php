<?php

namespace App\Repositories\Core\Product;

interface ProductInterface
{
    public function getAll($keyword = null, $catalogue = null, $subCategory = null, $category = null, $minPrice = null, $maxPrice = null);

    public function paginateProduct($keyword = null, $catalogue = null, $subCategory = null, $category = null, $minPrice = null, $maxPrice = null, $show = null);

    public function getProduct($id);

    public function getOnlyProduct($id);

    public function getProductTrashed($id);

    public function createProduct(array $data);
}
