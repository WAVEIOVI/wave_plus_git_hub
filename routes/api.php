<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

use App\Http\Controllers\Api\BusinessPartner\Clients\ClientsController;
use App\Http\Controllers\Api\BusinessPartner\Clients\MetaClientsController;
use App\Http\Controllers\Api\BusinessPartner\Clients\ClContactsController;
use App\Http\Controllers\Api\BusinessPartner\Clients\ClAddressesController;

use App\Http\Controllers\Api\BusinessPartner\Suppliers\SuppliersController;
use App\Http\Controllers\Api\BusinessPartner\Suppliers\MetaSuppliersController;
use App\Http\Controllers\Api\BusinessPartner\Suppliers\SuContactsController;
use App\Http\Controllers\Api\BusinessPartner\Suppliers\SuAddressesController;

use App\Http\Controllers\Api\ProductAssetSuite\Categories\CategoriesController;
use App\Http\Controllers\Api\ProductAssetSuite\Categories\SubcategoriesController;

use App\Http\Controllers\Api\ProductAssetSuite\Products\ProductsController;
use App\Http\Controllers\Api\ProductAssetSuite\Products\MetaProductsController;

use App\Http\Controllers\Api\ProductAssetSuite\Consumables\ConsumablesController;
use App\Http\Controllers\Api\ProductAssetSuite\Consumables\MetaConsumablesController;

use App\Http\Controllers\Api\ProductAssetSuite\EqServicings\EqServicingsController;
use App\Http\Controllers\Api\ProductAssetSuite\EqServicings\MetaEqServicingsController;

use App\Http\Controllers\Api\ProductAssetSuite\EqBlueprints\EqBlueprintsController;
use App\Http\Controllers\Api\ProductAssetSuite\EqBlueprints\MetaEqBlueprintsController;
use App\Http\Controllers\Api\ProductAssetSuite\EqBlueprints\EqBlueprintsWeightsController;
use App\Http\Controllers\Api\ProductAssetSuite\EqBlueprints\EqBlueprintsPressuresController;
use App\Http\Controllers\Api\ProductAssetSuite\EqBlueprints\EqBlueprintsEqServicingsController;
use App\Http\Controllers\Api\ProductAssetSuite\EqBlueprints\EqBlueprintsConsumablesController;

use App\Http\Controllers\Api\ProductAssetSuite\Settings\PressuresController;
use App\Http\Controllers\Api\ProductAssetSuite\Settings\BrandsController;
use App\Http\Controllers\Api\ProductAssetSuite\Settings\WeightsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->load('roles');
});


Route::group(['as' => 'api.', 'middleware' => 'auth:sanctum'], function () {
    Orion::resource('clients', ClientsController::class)->withSoftDeletes();
    Orion::resource('meta-clients', MetaClientsController::class);
    Orion::morphManyResource('clients', 'contacts', ClContactsController::class);
    Orion::morphManyResource('clients', 'addresses', ClAddressesController::class);

    Orion::resource('suppliers', SuppliersController::class)->withSoftDeletes();
    Orion::resource('meta-suppliers', MetaSuppliersController::class);
    Orion::morphManyResource('suppliers', 'contacts', SuContactsController::class);
    Orion::morphManyResource('suppliers', 'addresses', SuAddressesController::class);


    Orion::resource('categories', CategoriesController::class)->withSoftDeletes();
    Orion::hasManyResource('categories', 'subcategories', SubcategoriesController::class)->withSoftDeletes();
    
    Orion::resource('products', ProductsController::class)->withSoftDeletes();
    Orion::resource('meta-products', MetaProductsController::class);

    Orion::resource('consumables', ConsumablesController::class)->withSoftDeletes();
    Orion::resource('meta-consumables', MetaConsumablesController::class);

    Orion::resource('equipment-blueprints', EqBlueprintsController::class)->withSoftDeletes();
    Orion::resource('meta-equipment-blueprints', MetaEqBlueprintsController::class);
    Orion::belongsToManyResource('equipment-blueprints' , 'capacities', EqBlueprintsWeightsController::class);
    Orion::belongsToManyResource('equipment-blueprints' , 'pressures', EqBlueprintsPressuresController::class);

    Orion::belongsToManyResource('equipment-blueprints' , 'consumables', EqBlueprintsConsumablesController::class);
    Orion::belongsToManyResource('equipment-blueprints' , 'equipment-servicings', EqBlueprintsEqServicingsController::class);

    Orion::resource('equipment-servicings', EqServicingsController::class)->withSoftDeletes();
    Orion::resource('meta-equipment-servicings', MetaEqServicingsController::class);

    Orion::resource('pressures', PressuresController::class);
    Orion::resource('brands', BrandsController::class);
    Orion::resource('weights', WeightsController::class);

    // PDF
    Route::post('/generate-clients-list-pdf', [ClientsController::class, 'generateClientsListPdf']);
    Route::post('/generate-client-pdf', [ClientsController::class, 'generateClientPdf']);

    Route::post('/generate-suppliers-list-pdf', [SuppliersController::class, 'generateSuppliersListPdf']);
    Route::post('/generate-supplier-pdf', [SuppliersController::class, 'generateSupplierPdf']);

    Route::post('/generate-products-list-pdf', [ProductsController::class, 'generateProductsListPdf']);
    Route::post('/generate-product-pdf', [ProductsController::class, 'generateProductPdf']);

    Route::post('/generate-consumables-list-pdf', [ConsumablesController::class, 'generateConsumablesListPdf']);
    Route::post('/generate-consumable-pdf', [ConsumablesController::class, 'generateConsumablePdf']);
    
    Route::post('/generate-equipment-servicing-list-pdf', [EqServicingsController::class, 'generateEqServicingsListPdf']);
    Route::post('/generate-equipment-servicing-pdf', [EqServicingsController::class, 'generateEqServicingPdf']);

    Route::post('/generate-equipment-blueprint-list-pdf', [EqBlueprintsController::class, 'generateEqBlueprintsListPdf']);
    Route::post('/generate-equipment-blueprint-pdf', [EqBlueprintsController::class, 'generateEqBlueprintPdf']);
});
