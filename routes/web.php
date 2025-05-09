<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/calendar', function () {
    return view('examples.calendar');
});

Route::get('/profile', function () {
    return view('examples.profile');
});

Route::get('/form-elements', function () {
    return view('examples.form-elements');
});

Route::get('/basic-tables', function () {
    return view('examples.basic-tables');
});

Route::get('/blank', function () {
    return view('examples.blank');
});

Route::get('/404', function () {
    return view('examples.404');
});

Route::get('/line-chart', function () {
    return view('examples.line-chart');
});

Route::get('/bar-chart', function () {
    return view('examples.bar-chart');
});
Route::group(['prefix' => '/ui'], function () {

    Route::get('/alerts', function () {
        return view('examples.alerts');
    });

    Route::get('/avatars', function () {
        return view('examples.avatars');
    });

    Route::get('/badges', function () {
        return view('examples.badges');
    });

    Route::get('/buttons', function () {
        return view('examples.buttons');
    });

    Route::get('/images', function () {
        return view('examples.images');
    });

    Route::get('/videos', function () {
        return view('examples.videos');
    });
});

Route::get('/signin', function () {
    return view('examples.signin');
});

Route::get('/signup', function () {
    return view('examples.signup');
});

Route::get('/map/{map_name?}', function ($map_name = 'aed') {
    return view('map', ['map_name' => $map_name]);
});
