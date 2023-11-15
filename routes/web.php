<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndicesController;
use App\Http\Controllers\IndicesJsonController;

Route::get('/', [IndicesController::class, 'index']);

Route::get('/tjsp', [IndicesController::class, 'indiceTjsp'])->name('tjsp');
Route::get('/ortn', [IndicesController::class, 'indiceOrtn'])->name('ortn');
Route::get('/ufir', [IndicesController::class, 'indiceUfir'])->name('ufir');
Route::get('/caderneta', [IndicesController::class, 'indiceCadernetaPoupanca'])->name('caderneta');
Route::get('/igpdi', [IndicesController::class, 'indiceIgpdi'])->name('igpdi');
Route::get('/igpm', [IndicesController::class, 'indiceIgpm'])->name('igpm');
Route::get('/inpc', [IndicesController::class, 'indiceInpc'])->name('inpc');
Route::get('/ipca', [IndicesController::class, 'indiceIpca'])->name('ipca');
Route::get('/selic', [IndicesController::class, 'indiceSelic'])->name('selic');
Route::get('/ipc', [IndicesController::class, 'indiceIpcFipe'])->name('ipc');
Route::get('/ipcfgv', [IndicesController::class, 'indiceIpcFgv'])->name('ipcfgv');
Route::get('/tr', [IndicesController::class, 'indiceTr'])->name('tr');
Route::get('/tjmg', [IndicesController::class, 'indiceTjmg'])->name('tjmg');
Route::get('/cubsp', [IndicesController::class, 'indiceCubsp'])->name('cubsp');

Route::get('/tjsp/json', [IndicesJsonController::class, 'indiceTjsp'])->name('tjsp/json');
Route::get('/ortn/json', [IndicesJsonController::class, 'indiceOrtn'])->name('ortn/json');
Route::get('/ufir/json', [IndicesJsonController::class, 'indiceUfir'])->name('ufir/json');
Route::get('/caderneta/json', [IndicesJsonController::class, 'indiceCadernetaPoupanca'])->name('caderneta/json');
Route::get('/igpdi/json', [IndicesJsonController::class, 'indiceIgpdi'])->name('igpdi/json');
Route::get('/igpm/json', [IndicesJsonController::class, 'indiceIgpm'])->name('igpm/json');
Route::get('/inpc/json', [IndicesJsonController::class, 'indiceInpc'])->name('inpc/json');
Route::get('/ipca/json', [IndicesJsonController::class, 'indiceIpca'])->name('ipca/json');
Route::get('/selic/json', [IndicesJsonController::class, 'indiceSelic'])->name('selic/json');
Route::get('/ipc/json', [IndicesJsonController::class, 'indiceIpcFipe'])->name('ipc/json');
Route::get('/ipcfgv/json', [IndicesJsonController::class, 'indiceIpcFgv'])->name('ipcfgv/json');
Route::get('/tr/json', [IndicesJsonController::class, 'indiceTr'])->name('tr/json');
Route::get('/tjmg/json', [IndicesJsonController::class, 'indiceTjmg'])->name('tjmg/json');
Route::get('/indicesdisponiveis/json', [IndicesJsonController::class, 'indicesDisponiveis'])->name('indicesdisponiveis/json');

Route::get('/ajustar/json', [IndicesJsonController::class, 'ajustar'])->name('ajustar/json');
