<?php

use App\Http\Controllers\AgclienteController;
use App\Http\Controllers\AlberoController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FormatController;
use App\Http\Controllers\LadoController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MiscelaneoController;
use App\Http\Controllers\OlivoController;
use App\Http\Controllers\OnidexController;
use App\Http\Controllers\ParentescoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TFileController;
use App\Http\Controllers\TreeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ServicioController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Vista inicio
Route::get('/', [Controller::class, 'index'])->name('inicio')->middleware(['auth', 'verified']);

// Grupo de rutas CRUD
Route::group(['middleware' => ['auth'], 'as' => 'crud.'], function(){
    Route::resource('permissions', PermissionController::class)->names('permissions')
			->middleware('can:crud.permissions.index');
    Route::resource('roles', RoleController::class)->names('roles')
			->middleware('can:crud.roles.index');
    Route::resource('users', UserController::class)->names('users')
			->middleware('can:crud.users.index');
    Route::resource('countries', CountryController::class)->names('countries')
            ->middleware('can:crud.countries.index');
    Route::resource('agclientes', AgclienteController::class)->names('agclientes')
            ->middleware('can:crud.agclientes.index');
    Route::resource('parentescos', ParentescoController::class)->names('parentescos')
            ->middleware('can:crud.parentescos.index');
    Route::resource('lados', LadoController::class)->names('lados')
            ->middleware('can:crud.lados.index');
    Route::resource('connections', ConnectionController::class)->names('connections')
            ->middleware('can:crud.connections.index');
    Route::resource('families', FamilyController::class)->names('families')
            ->middleware('can:crud.families.index');
    Route::resource('t_files', TFileController::class)->names('t_files')
            ->middleware('can:crud.t_files.index');
    Route::resource('files', FileController::class)->names('files')
            ->middleware('can:crud.files.index');
    Route::resource('libraries', LibraryController::class)->names('libraries')
            ->middleware('can:crud.libraries.index');
    Route::resource('formats', FormatController::class)->names('formats')
            ->middleware('can:crud.formats.index');
    Route::resource('books', BookController::class)->names('books')
            ->middleware('can:crud.books.index');
    Route::resource('miscelaneos', MiscelaneoController::class)->names('miscelaneos')
            ->middleware('can:crud.miscelaneos.index');
    Route::resource('coupons', CouponController::class)->names('coupons')
            ->middleware('can:crud.coupons.index');
    Route::resource('servicios', ServicioController::class)->names('servicios')
            ->middleware('can:crud.servicios.index');
});

//AJAX para activar y desactivar cupones

Route::post('cuponenable',[CouponController::class, 'enable'])->name('cuponenable');

//Rutas para Stripe:
Route::get('stripeverify', [StripeController::class, 'stripeverify'])->name('stripeverify')
        ->middleware('can:crud.stripeverify.index');
Route::post('stripefind', [StripeController::class, 'stripefind'])->name('stripefind');
Route::post('stripegetidpago', [StripeController::class, 'stripegetidpago'])->name('stripegetidpago');
Route::post('stripeupdatedata',[StripeController::class, 'stripeupdatedata'])->name('stripeupdatedata');

Route::get('/cuponaplicado', function(){
    return redirect()->route('clientes.gracias')->with("status","exito");
})->name('cuponaplicado');

//Rutas para Stripe:

// Grupo de rutas para Consultas a base de datos
Route::group(['middleware' => ['auth'], 'as' => 'consultas.'], function(){
    Route::get('consultaodx', [OnidexController::class, 'index'])->name('onidex.index')
        ->middleware('can:consultas.onidex.index');
    Route::post('consultaodx', [OnidexController::class, 'show'])->name('onidex.show')
        ->middleware('can:consultas.onidex.show');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Grupo de rutas para vistas de árboles genealógicos
Route::group(['middleware' => ['auth'], 'as' => 'arboles.'], function(){
    Route::get('albero/{IDCliente}', [AlberoController::class, 'arbelo'])->name('albero.index')
        ->middleware('can:genealogista');
    Route::get('tree/{IDCliente}', [TreeController::class, 'tree'])->name('tree.index')
        ->middleware('can:genealogista');
    Route::get('olivo/{IDCliente}', [OlivoController::class, 'olivo'])->name('olivo.index')
        ->middleware('can:genealogista');
});

// Grupo de rutas para vistas de clientes
Route::group(['middleware' => ['auth'], 'as' => 'clientes.'], function(){
    Route::get('tree', [ClienteController::class, 'tree'])->name('tree')
        ->middleware('can:cliente');
    Route::get('salir', [ClienteController::class, 'salir'])->name('salir')
        ->middleware('can:cliente');
    Route::post('procesar', [ClienteController::class, 'procesar'])->name('procesar');
    Route::get('getinfo', [ClienteController::class, 'getinfo'])->name('getinfo')
        ->middleware('can:cliente');
    Route::get('pay', [ClienteController::class, 'pay'])->name('pay')
        ->middleware('can:cliente');
    Route::get('gracias', [ClienteController::class, 'gracias'])->name('gracias')
        ->middleware('can:cliente');
    
});

Route::post('getinfo', [ClienteController::class, 'procesargetinfo'])->name('procesargetinfo')
        ->middleware('can:cliente');

Route::post('pay', [ClienteController::class, 'procesarpay'])->name('procesarpay')
        ->middleware('can:cliente');

Route::get('/revisarcupon', [ClienteController::class, 'revisarcupon'])->name('revisarcupon')
        ->middleware('can:cliente');

// Grupo de rutas para realizar pruebas
Route::group(['middleware' => ['auth'], 'as' => 'test.'], function(){
    // Pruebas con Flex de Tailwind
    Route::get('flex', function (){
        return view('pruebas.flex');
    })->name('flex')->middleware('can:administrador');

    // Pruebas MVC Agcliente
    Route::get('agclientesp', function (){
        $agclientes = App\Models\Agcliente::all();
        return view('pruebas.agclientes', compact('agclientes'));
    })->name('agclientesp')->middleware('can:administrador');

    // Pruebas con ventanas modal
    Route::get('vmodal', function (){
        return view('pruebas.vmodal');
    })->name('vmodal')->middleware('can:administrador');

    // Generar enlaces para registrar clientes
    Route::get('registro', [App\Http\Controllers\GetController::class, 'registro'])->name('registro')->middleware('can:administrador');

    // Capturar parámetros get
    Route::get('capturar_parametros_get', [App\Http\Controllers\GetController::class, 'capturar_parametros_get'])->name('capturar_parametros_get')->middleware('can:administrador');
});


// RUTAS PARA EL MANTENIMIENTO DE LA APLICACIÓN EN PRODUCCIÓN
// Ruta para ejecutar en producción: $ php artisan key:generate
Route::get('key-generate', function(){
    Artisan::call('key:generate');
});

// Ruta para ejecutar en producción: $ php artisan storage:link
Route::get('storage-link', function(){
    Artisan::call('storage:link');
});

// Ruta para ejecutar en producción: $ php artisan config:cache
Route::get('config-cache', function(){
    Artisan::call('config:cache');
});

// Ruta para ejecutar en producción: $ php artisan cache:clear
Route::get('cache-clear', function(){
    Artisan::call('cache:clear');
});

// Ruta para ejecutar en producción: $ php artisan route:clear
Route::get('route-clear', function(){
    Artisan::call('route:clear');
});

// Ruta para ejecutar en producción: $ php artisan config:clear
Route::get('config-clear', function(){
    Artisan::call('config:clear');
});

// Ruta para ejecutar en producción: $ php artisan view:clear
Route::get('view-clear', function(){
    Artisan::call('view:clear');
});
