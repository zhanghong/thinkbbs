<?php

use think\facade\Route;

// 首页
Route::get('/', 'index/index')->name('page.root');

// 注册
Route::post('signup/check_unique', 'register/check_unique')->name('signup.check_unique');
Route::get('signup', 'register/create')->name('page.signup');
Route::post('signup', 'register/save')->name('page.signup.save');
