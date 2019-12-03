<?php

// 首页
Route::get('/', 'index/index')->name('page.root');

// 注册
Route::post('signup/send_code', 'register/send_code')->name('signup.send_code');
Route::post('signup/check_unique', 'register/check_unique')->name('signup.check_unique');
Route::get('signup', 'register/create')->name('page.signup');
Route::post('signup', 'register/save')->name('page.signup.save');

// 验证填写的手机验证码是否正确
Route::post('verify/valid_code', 'verify/valid_code')->name('verify.valid_code');

// 用户登录与退出
Route::get('login', 'login/create')->name('page.login');
Route::post('login', 'login/save')->name('page.login.save');
Route::post('logout', 'login/delete')->name('page.logout');
