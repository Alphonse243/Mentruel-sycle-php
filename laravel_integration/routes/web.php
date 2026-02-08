<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BioCycleController;

Route::match(['get','post'], '/biocycle/demo', [BioCycleController::class, 'demo']);
