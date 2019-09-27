<?php

Route::group([
    'name' => 'admin',
    'middleware' => ['tpadmin.admin', 'tpadmin.admin.role'],
], function () {
    // 用户管理
    Route::get('/user', 'User@index')->name('admin.user.index');
    Route::delete('/user/<id>', 'user/delete')->name('admin.user.delete');
    // 话题管理
    Route::get('/topic', 'Topic@index')->name('admin.topic.index');
    Route::delete('/topic/<id>', 'topic/delete')->name('admin.topic.delete');
})->prefix('\\app\\admin\\controller\\');
