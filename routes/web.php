<?php

use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Site\DashboardController;
use App\Http\Controllers\Site\FileController as SiteFileController;
use App\Http\Controllers\Site\OrganizationController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\Site\SiteTokenController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'index'])->name('home');
Route::get('/login', [SiteController::class, 'login'])->name('login');
Route::get('/logout', [SiteController::class, 'logout'])->name('logout');
Route::get('/merge', [SiteController::class, 'mergeWithAd'])->name('mergeWithAd');
Route::get('/register', [SiteController::class, 'register'])->name('register');
Route::post('/createUser', [SiteController::class, 'createUser']);
Route::post('/loginAuthentication', [SiteController::class, 'loginAuthentication']);
Route::middleware(['auth:web'])->group(function () {
    //dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
    Route::get('/dashboard/createOrganization', [OrganizationController::class, 'createOrganization']);
    Route::post('/dashboard/storeOrganization', [OrganizationController::class, 'store']);
    Route::get('/dashboard/editOrganization/{id}', [OrganizationController::class, 'edit']);
    Route::get('/dashboard/deleteOrganization/{id}', [OrganizationController::class, 'delete']);

    //site token
    Route::get('/dashboard/siteTokens', [SiteTokenController::class, 'index'])->name('site_token.index');
    Route::get('/dashboard/createSiteToken', [SiteTokenController::class, 'create']);
    Route::get('/dashboard/editSiteToken/{id}', [SiteTokenController::class, 'edit']);
    Route::post('/dashboard/storeSiteToken', [SiteTokenController::class, 'store']);
    Route::get('/dashboard/deleteSiteToken/{id}', [SiteTokenController::class, 'delete']);

    //all files
    Route::get('/dashboard/allFiles',[SiteFileController::class,'list'])->name('allFiles');
    Route::get('/dashboard/deleteFile/{id}', [SiteFileController::class,'deleteFile']);
});
