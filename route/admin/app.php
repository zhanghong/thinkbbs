<?php

use think\facade\Route;

Route::group(function(){
    Route::group('auth', function(){
        Route::get('passport/login', 'auth\\Passport@create')->name('admin.auth.passport.login');
        Route::post('passport/login', 'auth\\Passport@save');
        Route::post('auth/passport/login', 'auth\\Passport@save');
    });

    Route::group(function(){
        Route::get('/', 'Index@index')->name('admin.index');
        Route::get('/dashboard', 'Index@index')->name('admin.dashboard');
        Route::get('passport/logout', 'Auth\\Passport@delete')->name('admin.auth.passport.logout');
    })->middleware('tpadmin.admin');

    Route::group(function(){
        Route::any('/config/site', 'Config@site')->name('admin.config.site');

        Route::get('/passport/user', 'auth\\Passport@user')->name('admin.auth.passport.user');

        Route::get('/adminer/create', 'auth\\Adminer@create')->name('admin.auth.adminer.create');
        Route::get('/adminer/:id/edit', 'auth\\Adminer@edit')->name('admin.auth.adminer.edit');
        Route::get('/adminer/:id', 'auth\\Adminer@read')->name('admin.auth.adminer.read');
        Route::put('/adminer/:id', 'auth\\Adminer@update')->name('admin.auth.adminer.update');
        Route::delete('/adminer/:id', 'auth\\Adminer@delete')->name('admin.auth.adminer.delete');
        Route::get('/adminer', 'auth\\Adminer@index')->name('admin.auth.adminer.index');
        Route::post('/adminer', 'auth\\Adminer@save')->name('admin.auth.adminer.save');

        Route::get('/rule/create', 'auth\\Rule@create')->name('admin.auth.rule.create');
        Route::get('/rule/:id/edit', 'auth\\Rule@edit')->name('admin.auth.rule.edit');
        Route::get('/rule/:id', 'auth\\Rule@read')->name('admin.auth.rule.read');
        Route::put('/rule/:id', 'auth\\Rule@update')->name('admin.auth.rule.update');
        Route::delete('/rule/:id', 'auth\\Rule@delete')->name('admin.auth.rule.delete');
        Route::get('/rule', 'auth\\Rule@index')->name('admin.auth.rule.index');
        Route::post('/rule.resort', 'auth\\Rule@resort')->name('admin.auth.rule.resort');
        Route::post('/rule', 'auth\\Rule@save')->name('admin.auth.rule.save');

        Route::get('/role/create', 'auth\\Role@create')->name('admin.auth.role.create');
        Route::get('/role/:id/edit', 'auth\\Role@edit')->name('admin.auth.role.edit');
        Route::get('/role/:id', 'auth\\Role@read')->name('admin.auth.role.read');
        Route::put('/role/:id', 'auth\\Role@update')->name('admin.auth.role.update');
        Route::delete('/role/:id', 'auth\\Role@delete')->name('admin.auth.role.delete');
        Route::get('/role', 'auth\\Role@index')->name('admin.auth.role.index');
        Route::post('/role', 'auth\\Role@save')->name('admin.auth.role.save');
    })->middleware(['tpadmin.admin', 'tpadmin.admin.role']);
})->prefix('\\tpadmin\\controller\\');
