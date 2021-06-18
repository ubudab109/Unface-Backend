<?php

namespace App\Repositories\Backend\SizeProduct;

interface SizeProductInterface
{
    public function allSizeProduct($keyword = null);

    public function getSizeProduct($id);

    public function getSizeProductTrashed($id);

    public function paginateSizeProduct($keyword = null, $page = null, $status = null);

    public function createSizeProduct(array $data);

    public function dataset();
    
}
