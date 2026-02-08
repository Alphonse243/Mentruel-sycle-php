<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BioCycleController;

Route::get('/biocycle/demo', [BioCycleController::class, 'demo']);
