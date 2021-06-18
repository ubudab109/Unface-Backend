<?php

namespace App\Repositories\Backend\SubCategory;

interface SubCategoryInterface
{
    public function allSubCategory($keyword = null, $category = null);

    public function paginateSubCategory($keyword = null, $category = null, $page = null, $status = null);

    public function getSubCategory($id);

    public function getSubCategoryTrashed($id);

    public function createSubCategory(array $data);

    public function dataset();
    
}
