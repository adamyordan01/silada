<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\ChangePasswordController;

Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login/authenticate', [LoginController::class, 'login'])->name('login.authenticate');

});

Route::group(['middleware' => ['auth']], function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/archive-by-month', [DashboardController::class, 'getArchiveByMonth'])->name('dashboard.archive-by-month');
    });

    Route::group(['prefix' => 'positions'], function () {
        Route::get('/', [PositionController::class, 'index'])->name('position.index');
        Route::get('/get-position', [PositionController::class, 'getPosition'])->name('position.get-position');
        Route::post('/store', [PositionController::class, 'store'])->name('position.store');
        Route::post('/get-position-detail', [PositionController::class, 'getPositionDetail'])->name('position.get-position-detail');
        Route::patch('/update', [PositionController::class, 'update'])->name('position.update');
        Route::post('/delete', [PositionController::class, 'destroy'])->name('position.destroy');
    });

    Route::group(['prefix' => 'role'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('role.index');
        Route::get('/get-role', [RoleController::class, 'getRoles'])->name('role.get-role');
        Route::post('/store', [RoleController::class, 'store'])->name('role.store');
        Route::post('/get-role-detail', [RoleController::class, 'getRoleDetail'])->name('role.get-role-detail');
        Route::patch('/update', [RoleController::class, 'update'])->name('role.update');
        Route::post('/destroy', [RoleController::class, 'destroy'])->name('role.destroy');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/get-users', [UserController::class, 'getUsers'])->name('user.get-users');
        Route::post('/get-user-detail', [UserController::class, 'getUserDetail'])->name('user.get-user-detail');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('user.edit');
        Route::patch('/edit/{user}', [UserController::class, 'update'])->name('user.update');
    });

    Route::group(['prefix' => 'document-type'], function() {
        Route::get('/', [DocumentTypeController::class, 'index'])->name('document-type.index');
        Route::post('/store', [DocumentTypeController::class, 'store'])->name('document-type.store');
        Route::get('/get-document-type', [DocumentTypeController::class, 'getDocumentType'])->name('document-type.get-document-type');
        Route::post('/get-document-type-detail', [DocumentTypeController::class, 'getDocumentDetail'])->name('document-type.get-document-type-detail');
        Route::patch('/update', [DocumentTypeController::class, 'update'])->name('document-type.update');
    });

    Route::group(['prefix' => 'archive'], function() {
        Route::get('/', [ArchiveController::class, 'index'])->name('archive.index');
        Route::post('/store', [ArchiveController::class, 'store'])->name('archive.store');
        Route::get('/get-archives', [ArchiveController::class, 'getArchives'])->name('archive.get-archives');
        Route::get('/create', [ArchiveController::class, 'create'])->name('archive.create');
        Route::post('share', [ArchiveController::class, 'share'])->name('archive.share');

        Route::get('/get-incoming-letters', [ArchiveController::class, 'getIncomingLetter'])->name('archive.get-incoming-letters');
        Route::get('/incoming-letter', [ArchiveController::class, 'incomingLetter'])->name('archive.incoming-letter');

        Route::get('/get-outgoing-letters', [ArchiveController::class, 'getOutgoingLetter'])->name('archive.get-outgoing-letters');
        Route::get('/outgoing-letter', [ArchiveController::class, 'outgoingLetter'])->name('archive.outgoing-letter');

        Route::get('/get-ajb', [ArchiveController::class, 'getAjb'])->name('archive.get-ajb');
        Route::get('/ajb', [ArchiveController::class, 'ajb'])->name('archive.ajb');

        Route::get('/get-aphgb', [ArchiveController::class, 'getAphgb'])->name('archive.get-aphgb');
        Route::get('/aphgb', [ArchiveController::class, 'aphgb'])->name('archive.aphgb');

        Route::get('/get-aphb', [ArchiveController::class, 'getAphb'])->name('archive.get-aphb');
        Route::get('/aphb', [ArchiveController::class, 'aphb'])->name('archive.aphb');

        Route::get('/get-akta-hibah', [ArchiveController::class, 'getAktaHibah'])->name('archive.get-akta-hibah');
        Route::get('/akta-hibah', [ArchiveController::class, 'aktaHibah'])->name('archive.akta-hibah');
    });

    Route::group(['prefix' => 'change-password'], function () {
        Route::get('/index', [ChangePasswordController::class, 'index'])->name('change-password.index');
        Route::patch('/update', [ChangePasswordController::class, 'update'])->name('change-password.update');
    });

    Route::get('/profile/index', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
