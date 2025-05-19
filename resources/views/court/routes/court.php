<?php
use App\Http\Controllers\CourtCategoryController;
use App\Http\Controllers\CourtController;

Route::middleware('auth')->group(function () {

    Route::resource('court_category',CourtCategoryController::class);
    Route::get('court/category/list', [CourtCategoryController::class, 'list'])->name('court.category.list');
    Route::post('update/status/court/category', [CourtCategoryController::class, 'updateStatus'])->name('update.court.category.status');
    Route::get('search/court/category', [CourtCategoryController::class, 'searchCourtCategory'])->name('search.court.category');

    Route::resource('courts',CourtController::class);
    Route::get('court/list', [CourtController::class, 'list'])->name('courts.list');
    Route::post('update/status/court', [CourtController::class, 'updateStatus'])->name('update.court.status');
    Route::get('search/court/address', [CourtController::class, 'searchCourtAddress'])->name('search.court.address');
    Route::get('search/court/caseID', [CourtController::class, 'searchCourtCaseID'])->name('search.court.caseid');
});
