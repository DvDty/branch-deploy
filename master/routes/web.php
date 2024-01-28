<?php

use App\Github;
use App\Kubectl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request, Kubectl $kubectl, Github $github) {
    if ($request->has('branch') && $request->has('time')) {
        $kubectl->createInstance($request->input('branch'), (int) $request->input('time'));
    }

    return view('welcome', [
        'deployments' => $kubectl->getDeployments(),
        'branches' => $github->fetchBranches()->pluck('name'),
    ]);
});
