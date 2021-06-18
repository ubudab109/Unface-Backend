<?php

namespace App\Repositories\Backend\Catalogue;

interface CatalogueInterface
{
    public function allCatalogue($keyword = null, $subcategory = null, $category = null);

    public function paginateCatalogue($keyword = null, $subcategory = null, $category = null, $show = null, $status = null);

    public function getCatalogue($id);

    public function getCatalogueTrashed($id);

    public function createCatalogue(array $data);

    public function dataset();
}
