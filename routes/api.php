<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LayoutController;



Route::get('/test-api', function () {

    return response()->json([
        'message' => 'API aktif'
    ]);

});



Route::get(
    '/ruangan/{ruangan}/layouts',
    [
        LayoutController::class,
        'index'
    ]
);