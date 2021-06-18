<?php

namespace App\Providers;

use App\Repositories\Backend\Catalogue\CatalogueInterface;
use App\Repositories\Backend\Catalogue\CatalogueRepository;
use App\Repositories\Backend\Category\CategoryProductInterface;
use App\Repositories\Backend\Category\CategoryProductRepository;
use App\Repositories\Backend\ColorProduct\ColorProductInterface;
use App\Repositories\Backend\ColorProduct\ColorProductRepository;
use App\Repositories\Backend\Material\MaterialInterface;
use App\Repositories\Backend\Material\MaterialRepository;
use App\Repositories\Backend\SizeProduct\SizeProductInterface;
use App\Repositories\Backend\SizeProduct\SizeProductRepository;
use App\Repositories\Backend\SubCategory\SubCategoryInterface;
use App\Repositories\Backend\SubCategory\SubCategoryRepository;
use App\Repositories\Core\Product\ProductInterface;
use App\Repositories\Core\Product\ProductRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->bind(AdminSettingInterface::class, AdminSettingRepository::class);
        $this->app->bind(CategoryProductInterface::class, CategoryProductRepository::class);
        $this->app->bind(SubCategoryInterface::class, SubCategoryRepository::class);
        $this->app->bind(CatalogueInterface::class, CatalogueRepository::class);
        $this->app->bind(ColorProductInterface::class, ColorProductRepository::class);
        $this->app->bind(SizeProductInterface::class, SizeProductRepository::class);
        $this->app->bind(MaterialInterface::class, MaterialRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
