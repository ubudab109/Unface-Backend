<?php

namespace App\Repositories\Backend\Material;

interface MaterialInterface
{
    public function allMaterial($keyword = null);

    public function getMaterial($id);

    public function getMaterialTrashed($id);

    public function paginateMaterial($keyword = null, $page = null, $status = null);

    public function createMaterial(array $data);

    public function dataset();
}
