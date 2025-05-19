<?php
use App\Http\Controllers\CaseActsController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FeeDescriptionController;
use App\Http\Controllers\PaymentDescriptionController;
use App\Http\Controllers\StateController;


Route::middleware('auth')->group(function () {
    // Country Routes.....
    Route::get('countries', [CountryController::class, 'index'])->name('countries');
    Route::get('country/list', [CountryController::class, 'list'])->name('country.list');
    Route::post('store/country', [CountryController::class, 'store'])->name('store.country');
    Route::post('update/country/{id}', [CountryController::class, 'update'])->name('update.country');
    Route::post('update/status/country', [CountryController::class, 'updateStatus'])->name('update.country.status');
    Route::delete('delete/country/{id}', [CountryController::class, 'delete'])->name('delete.country');
    Route::get('search/country', [CountryController::class, 'searchCountry'])->name('search.country');

    // State Routes.....
    Route::get('states', [StateController::class, 'index'])->name('states');
    Route::get('state/list', [StateController::class, 'list'])->name('state.list');
    Route::post('store/state', [StateController::class, 'store'])->name('store.state');
    Route::post('update/state/{id}', [StateController::class, 'update'])->name('update.state');
    Route::post('update/status/state', [StateController::class, 'updateStatus'])->name('update.state.status');
    Route::delete('delete/state/{id}', [StateController::class, 'delete'])->name('delete.state');
    Route::get('search/state', [StateController::class, 'searchState'])->name('search.state');

    // City Routes.....
    Route::get('cities', [CityController::class, 'index'])->name('cities');
    Route::get('city/list', [CityController::class, 'list'])->name('city.list');
    Route::post('store/city', [CityController::class, 'store'])->name('store.city');
    Route::post('update/city/{id}', [CityController::class, 'update'])->name('update.city');
    Route::post('update/status/city', [CityController::class, 'updateStatus'])->name('update.city.status');
    Route::delete('delete/city/{id}', [CityController::class, 'delete'])->name('delete.city');
    Route::get('search/city', [CityController::class, 'searchCity'])->name('search.city');

    // Fee Description Routes.....
    Route::get('fee/description', [FeeDescriptionController::class, 'index'])->name('fee.description');
    Route::get('fee/description/list', [FeeDescriptionController::class, 'list'])->name('fee.description.list');
    Route::post('store/fee/description', [FeeDescriptionController::class, 'store'])->name('store.fee.description');
    Route::post('update/fee/description/{id}', [FeeDescriptionController::class, 'update'])->name('update.fee.description');
    Route::post('update/status/fee/description', [FeeDescriptionController::class, 'updateStatus'])->name('update.fee.description.status');
    Route::delete('delete/fee/description/{id}', [FeeDescriptionController::class, 'delete'])->name('delete.fee.description');

    // Case Acts Routes.....
    Route::get('case/acts', [CaseActsController::class, 'index'])->name('case.acts');
    Route::get('case/act/list', [CaseActsController::class, 'list'])->name('case.act.list');
    Route::post('store/case/act', [CaseActsController::class, 'store'])->name('store.case.act');
    Route::post('update/case/act/{id}', [CaseActsController::class, 'update'])->name('update.case.act');
    Route::post('update/status/case/act', [CaseActsController::class, 'updateStatus'])->name('update.case.act.status');
    Route::delete('delete/case/act/{id}', [CaseActsController::class, 'delete'])->name('delete.case.act');

});
