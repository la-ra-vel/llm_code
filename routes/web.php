<?php


use App\Http\Controllers\ClientController;
use App\Http\Controllers\GeneralSettingsController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationDesController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('auth.login');
})->middleware('guest')->name('user.login');
Route::get('/cache-clear', function () {
    Artisan::call('optimize:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return response()->json(['success' => true]);
});

// Route::get('/dashboard', function () {
//     $pageTitle = "Dashboard";
//     return view('dashboard', compact('pageTitle'));
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('court-custom-search', [HomeController::class, 'courtCustomSearch'])->name('court.custom.search');
    Route::get('dashboard-widget-data', [HomeController::class, 'dashboardWidgetData']);
    Route::post('update/theme/mode', [HomeController::class, 'updateThemeMode']);


    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Routes...
    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('users/list', [UserController::class, 'usersList'])->name('users.list');
    Route::post('update/status/user', [UserController::class, 'updateUsertatus'])->name('update.user.status');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user/store', [UserController::class, 'store'])->name('user.store');
    Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    Route::get('/profile/{username?}', [UserController::class, 'profile'])->name('profile');
    Route::post('/update/profile/picture', [UserController::class, 'updateProfilePicture'])->name('update.profile.pic');
    Route::post('/change/password', [UserController::class, 'changePassword'])->name('change.password');
    Route::get('/user/activity', [UserController::class, 'userActivity'])->name('user.activity');

    // Roles Routes...
    Route::get('roles', [GroupController::class, 'index'])->name('roles');
    Route::get('roles/list', [GroupController::class, 'rolesList'])->name('roles.list');
    Route::get('role/create', [GroupController::class, 'create'])->name('role.create');
    Route::post('role/store', [GroupController::class, 'store'])->name('role.store');
    Route::post('role/update/{id}', [GroupController::class, 'update'])->name('role.update');
    Route::delete('role/delete/{id}', [GroupController::class, 'delete'])->name('role.delete');

    // Settings Routs ...
    Route::get('general/settings', [GeneralSettingsController::class, 'index'])->name('general.settings');
    Route::post('general/settings', [GeneralSettingsController::class, 'generalSettingUpdate'])->name('save.general.settings');

    Route::get('email/config', [GeneralSettingsController::class, 'emailConfig'])->name('email.config');

    Route::post('email/config', [GeneralSettingsController::class, 'emailConfigUpdate'])->name('save.email.config');

    // Client Routes.....
    Route::get('clients', [ClientController::class, 'index'])->name('clients');
    Route::get('client/list', [ClientController::class, 'list'])->name('client.list');
    Route::get('create/client', [ClientController::class, 'create'])->name('create.client');
    Route::post('store/client', [ClientController::class, 'store'])->name('store.client');
    Route::post('update/client/{id}', [ClientController::class, 'update'])->name('update.client');
    Route::post('update/status/client', [ClientController::class, 'updateClientStatus'])->name('update.client.status');
    Route::delete('delete/client/{id}', [ClientController::class, 'delete'])->name('delete.client');
    Route::get('search/clients', [ClientController::class, 'searchClients'])->name('search.clients');

    // Invoice Routes.....
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices');
    Route::get('invoice/list', [InvoiceController::class, 'list'])->name('invoice.list');
    Route::post('invoice/generate', [InvoiceController::class, 'invoiceGenerate'])->name('invoice.generate');
    Route::post('send/email/{id}', [InvoiceController::class, 'sendEmail'])->name('send.email');

    // Quotation Routes...
    Route::resource('/quotations', QuotationController::class);
    Route::get('quotation/list', [QuotationController::class, 'list'])->name('quotation.list');
    Route::post('quotation/generate', [QuotationController::class, 'quotationGenerate'])->name('quotation.generate');
    Route::post('update/status/quotation', [QuotationController::class, 'updateQuotationStatus'])->name('update.quotation.status');

    // Quotation Description ....
    Route::post('quotation/description/{id?}', [QuotationDesController::class, 'storeQuotationDescription'])->name('store.quotation.description');
    Route::delete('delete/quotation/description/{id}', [QuotationDesController::class, 'deleteQuotationDescription'])->name('delete.quotation.description');

    // Todo Routes....
    Route::resource('/todos', TodoController::class);




});

Route::get('/reset-password', [HomeController::class, 'forgotPassword'])->name('reset.password');
Route::post('/email-verify', [HomeController::class, 'emailVerify'])->name('email.verify');

// Route::get('/install', [InstallController::class, 'showInstallForm'])->name('install.show');
// Route::post('/install', [InstallController::class, 'install'])->name('install');

// Route::get('image',function(){
//     return view('image');
// });

// Route::get('/verify_images', [ImageController::class, 'index']);
// Route::post('/upload', [ImageController::class, 'upload']);
// Route::post('/verify', [ImageController::class, 'verify']);
// Route::get('/user-images', [ImageController::class, 'getUserImages']);

require __DIR__ . '/auth.php';
require base_path('resources/views/master_data/routes/country.php');
require base_path('resources/views/court/routes/court.php');
require base_path('resources/views/case/routes/case.php');
