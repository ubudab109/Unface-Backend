<?php

namespace App\Repositories\Backend\Category;

interface CategoryProductInterface
{
    public function allCategory($keyword = null);

    public function getCategory($id);

    public function getCategoryTrashed($id);

    public function paginateCategory($keyword = null, $page = null, $status = null);

    public function createCategory(array $data);

    public function dataset();
}
