<?php
use App\Http\Controllers\CaseController;
use App\Http\Controllers\NewCase\ActionsController;
use App\Http\Controllers\NewCase\CourtDetailsController;
use App\Http\Controllers\NewCase\DocumentController;
use App\Http\Controllers\NewCase\FeeDetailsController;
use App\Http\Controllers\NewCase\PaymentDetailsController;

Route::middleware('auth')->group(function () {

    // Case Routes.....
    Route::get('cases', [CaseController::class, 'index'])->name('case.index');
    Route::get('case/list', [CaseController::class, 'caseList'])->name('case.list');
    Route::get('create/case', [CaseController::class, 'create'])->name('create.case');
    Route::get('edit/case/{id}', [CaseController::class, 'edit'])->name('edit.case');
    Route::post('update/case/status', [CaseController::class, 'updateCaseStatus'])->name('update.case.status');
    Route::delete('delete/case/{id}', [CaseController::class, 'delete'])->name('delete.case');
    Route::post('/check-case-pending-amount',[CaseController::class,'checkCasePendingAmount']);

    // Tab-Court Details Routs....
    Route::post('store/court-details/{id?}', [CourtDetailsController::class, 'storeCourtDetails'])->name('store.court.details');
    Route::put('update/court-details/{id}', [CourtDetailsController::class, 'updateCourtDetails'])->name('update.court.details');

    // Tab-Fee Details Routs....
    Route::get('tabs/court/fee-details/{id?}', [FeeDetailsController::class, 'feeDetails'])->name('tabs.fee-details');
    Route::post('store/court-fee-details/{id?}', [FeeDetailsController::class, 'storeCourtFeeDetails'])->name('store.court.fee.details');
    Route::delete('delete/court-fee-details/{id}', [FeeDetailsController::class, 'deleteCourtFeeDetails'])->name('delete.court.fee.details');

    // Tab-Actions Details Routs....
    Route::get('tabs/court/action-details/{id?}', [ActionsController::class, 'actionDetails'])->name('tabs.action-details');
    Route::post('store/court-action-details/{id?}', [ActionsController::class, 'storeCourtActionDetails'])->name('store.court.actions.details');
    Route::delete('delete/court-action-details/{id}', [ActionsController::class, 'deleteCourtActionDetails'])->name('delete.court.action.details');

    // Tab-Payment Details Routs....
    Route::get('tabs/court/payment-details/{id?}', [PaymentDetailsController::class, 'paymentDetails'])->name('tabs.payment-details');
    Route::post('store/court-payment-details/{id?}', [PaymentDetailsController::class, 'storeCourtPaymentDetails'])->name('store.court.payment.details');
    Route::delete('delete/court-payment-details/{id}', [PaymentDetailsController::class, 'deleteCourtPaymentDetails'])->name('delete.court.payment.details');

    // Tab-Payment Details Routs....
    Route::get('tabs/court/document-details/{id?}', [DocumentController::class, 'documentDetails'])->name('tabs.document-details');
    Route::post('store/court-document-details/{id?}', [DocumentController::class, 'storeCourtDocumentDetails'])->name('store.court.document.details');
    Route::delete('delete/court-document-details/{id}', [DocumentController::class, 'deleteCourtDocumentDetails'])->name('delete.court.document.details');


});
