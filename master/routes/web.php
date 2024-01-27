<?php

use App\Github;
use App\Kubectl;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function (Kubectl $kubectl, Github $github) {
    return view('welcome', [
        'pods' => $kubectl->getPods(),
        'branches' => $github->fetchBranches()->pluck('name'),
    ]);
});
