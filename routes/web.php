<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BitacoraController;
use App\Http\Livewire\ShowMembresias;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request; //para invoice
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('can:Ver dashboard')->name('dashboard');
});

//Para los roles
Route::resource('roles', RoleController::class)->names('admin.roles');

//Para los usuarios
//only en este caso solo creara las rutas index, edit, update
//Route::resource('users',UserController::class)->only(['index', 'edit', 'update'])->names('admin.users');
Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
Route::get('users/{user}', [UserController::class, 'show'])->name('admin.users.show');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::get('users/{user}/rol', [UserController::class, 'rol'])->name('admin.users.rol');
Route::put('rol/{user}', [UserController::class, 'updateRol'])->name('admin.users.updateRol');
Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

//PARA LA BITACORA
Route::get('bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');

//PARA LA MEMBRESIA
Route::get('membresia', ShowMembresias::class)->name('membresia.index');

//Metodo de pago
Route::get('/billings', [BillingController::class, 'index'])->middleware('auth')->name('billings.index');

//Invoice
Route::get('/user/invoice/{invoice}', function (Request $request, string $invoiceId) {
    return $request->user()->downloadInvoice($invoiceId);
});

// Route::resource('products', ProductController::class)->names('products');
Route::get('products', [ProductController::class,'index'])->name('products.index');
Route::get('providers', [ProviderController::class,'index'])->name('providers.index');
Route::get('categories', [CategoryController::class,'index'])->name('categories.index');
