<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home / Produtos
Route::view('/', 'app');

// Login
Route::view('/login', 'app');

// Produtos: listagem, criação e edição
Route::view('/products', 'app');
Route::view('/products/new', 'app');
Route::view('/products/{id}', 'app')->whereNumber('id');

// (opcional) Categorias, Perfil, etc.
Route::view('/categories', 'app');
Route::view('/profile', 'app');

// Fallback opcional para qualquer outra rota do front (exceto /api/*)
Route::fallback(function () {
    // Evita capturar /api/*
    if (request()->is('api/*')) {
        abort(404);
    }
    return view('app');
});

require __DIR__.'/auth.php';
