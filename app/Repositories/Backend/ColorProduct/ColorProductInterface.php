<?php

namespace App\Repositories\Backend\ColorProduct;

interface ColorProductInterface
{
    public function allColorProduct($keyword = null, $hex = null);

    public function getColorProduct($id);

    public function getColorProductTrashed($id);

    public function paginateColorProduct($keyword = null, $hex = null, $page = null, $status = null);

    public function createColorProduct(array $data);

    public function dataset();
}
