<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Swagger UI
Route::get('/api/docs', function () {
    return view('swagger');
});

Route::get('/api/docs/openapi.yaml', function () {
    $path = base_path('openapi.yaml');
    abort_unless(file_exists($path), 404);
    return response(file_get_contents($path), 200)
        ->header('Content-Type', 'application/yaml')
        ->header('Access-Control-Allow-Origin', '*');
});
