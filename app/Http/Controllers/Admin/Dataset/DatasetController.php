<?php

namespace App\Http\Controllers\Admin\Dataset;

use App\Http\Controllers\BaseController;
use App\Repositories\Backend\Catalogue\CatalogueInterface;
use Illuminate\Http\Request;
use App\Repositories\Backend\Category\CategoryProductInterface;
use App\Repositories\Backend\ColorProduct\ColorProductInterface;
use App\Repositories\Backend\Material\MaterialInterface;
use App\Repositories\Backend\SizeProduct\SizeProductInterface;
use App\Repositories\Backend\SubCategory\SubCategoryInterface;
use Throwable;

class DatasetController extends BaseController
{
    public $category, $subCategory, $material, $catalogue, $color, $size;

    public function __construct(CategoryProductInterface $category, SubCategoryInterface $subCategory, MaterialInterface $material, CatalogueInterface $catalogue, SizeProductInterface $size, ColorProductInterface $color)
    {
        $this->category = $category;
        $this->subCategory = $subCategory;
        $this->material = $material;
        $this->catalogue = $catalogue;
        $this->size = $size;
        $this->color = $color;
    }

    /**
     * Display dataset for category
     *
     * @return \Illuminate\Http\Response
     */

    public function categoryDataset()
    {
        try {
            $category = $this->category->dataset();
            return $this->sendResponse($category, 'Data Fetched Successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th);
        }
    }

    /**
     * Display dataset for sub category
     *
     * @return \Illuminate\Http\Response
     */

    public function subCategoryDataset()
    {
        try {
            $subCategory = $this->subCategory->dataset();
            return $this->sendResponse($subCategory, 'Data Fetched Successfully');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th);
        }
    }

    /**
     * Display dataset material
     *
     * @return \Illuminate\Http\Response
     */

    public function materialDataset()
    {
        try {
            return $this->sendResponse($this->material->dataset(), 'Data Fetched Successfuly');
        } catch (Throwable $th) {
            return $this->sendError('Error', $th);
        }
    }

    /**
     * Display dataset material
     *
     * @return \Illuminate\Http\Response
     */

    public function catalogueDataset()
    {
        try {
            return $this->sendResponse($this->catalogue->dataset(), 'Data Fetched Successfuly');
        } catch (Throwable $th) {
            return $this->sendError('Error', $th);
        }
    }

    /**
     * Display dataset size
     *
     * @return \Illuminate\Http\Response
     */

    public function sizeDataset()
    {
        try {
            return $this->sendResponse($this->size->dataset(), 'Data Fetched Successfuly');
        } catch (Throwable $th) {
            return $this->sendError('Error', $th);
        }
    }

    /**
     * Display dataset color
     *
     * @return \Illuminate\Http\Response
     */

    public function colorDataset()
    {
        try {
            return $this->sendResponse($this->color->dataset(), 'Data Fetched Successfuly');
        } catch (Throwable $th) {
            return $this->sendError('Error', $th);
        }
    }
}
