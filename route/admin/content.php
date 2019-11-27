<?php

use think\facade\Route;

Route::group(function(){
    // 用户管理
    Route::get('/user', 'User@index')->name('admin.user.index');
    Route::delete('/user/<id>', 'user/delete')->name('admin.user.delete');
})->middleware(['tpadmin.admin', 'tpadmin.admin.role'])->prefix('\\app\\admin\\controller\\');
