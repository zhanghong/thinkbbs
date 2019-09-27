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
    // 回复管理
    Route::get('/reply', 'Reply@index')->name('admin.reply.index');
    Route::delete('/reply/<id>', 'reply/delete')->name('admin.reply.delete');
    // 资源管理
    Route::post('link', 'Link@save')->name('admin.link.save');
    Route::get('link/create', 'Link@create')->name('admin.link.create');
    Route::get('link/<id>/edit', 'Link@edit')->name('admin.link.edit');
    Route::put('link/<id>', 'Link@update')->name('admin.link.update');
    Route::delete('link/<id>', 'Link@delete')->name('admin.link.delete');
    Route::get('link', 'Link@index')->name('admin.link.index');
})->prefix('\\app\\admin\\controller\\');
