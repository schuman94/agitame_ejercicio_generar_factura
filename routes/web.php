<?php

use App\Generico\Carrito;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ProfileController;
use App\Models\Articulo;
use App\Models\Factura;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('departamentos', DepartamentoController::class);
Route::resource('empleados', EmpleadoController::class);
Route::resource('articulos', ArticuloController::class);

Route::get('/carrito/meter/{articulo}', function (Articulo $articulo) {
    $carrito = Carrito::carrito();
    $carrito->meter($articulo->id);
    session()->put('carrito', $carrito);
    return redirect()->route('articulos.index');
})->name('carrito.meter');

Route::get('/carrito/sacar/{articulo}', function (Articulo $articulo) {
    $carrito = Carrito::carrito();
    $carrito->sacar($articulo->id);
    session()->put('carrito', $carrito);
    return redirect()->route('articulos.index');
})->name('carrito.sacar');

Route::get('/carrito/vaciar', function () {
    session()->forget('carrito');
    return redirect()->route('articulos.index');
})->name('carrito.vaciar');

Route::get('/comprar', function () {
    return view('comprar', [
        'carrito' => Carrito::carrito(),
    ]);
})->middleware('auth')->name('comprar');

Route::post('/realizar_compra', function (Request $request) {

    $validated = $request->validate([
        'numero' => 'required|numeric|unique:facturas,numero'
    ]);

    $carrito = Carrito::carrito();
    DB::beginTransaction();
    $factura = new Factura();
    $factura->numero = $validated['numero'];
    $factura->user()->associate(Auth::user());
    $factura->save();
    $attachs = [];
    foreach ($carrito->getLineas() as $articulo_id => $linea) {
        $attachs[$articulo_id] = ['cantidad' => $linea->getCantidad()];
    }
    $factura->articulos()->attach($attachs);
    DB::commit();
    session()->forget('carrito');
    session()->flash('exito', 'Factura creada correctamente.');
    return redirect()->route('articulos.index');
})->middleware('auth')->name('realizar_comprar');



require __DIR__.'/auth.php';
