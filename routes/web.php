<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::get('/home', 'HomeController@index');


Route::get('/', function () {
    return view('home.index');
});


// Route::get('/cidades', 'CidadesController@getIndex');
// Route::get('/cidades/edit/{$id}', 'CidadesController@getEdit');
// Route::get('/cidades/view/{$id}', 'CidadesController@getView');
// Route::post('/cidades/edit', 'CidadesController@postEdit');
// Route::post('/cidades/delete', 'CidadesController@postDelete');

Route::get('usuarios/{id}/delete', 'UsuariosController@delete')->name('usuarios.delete');
Route::resource('usuarios', 'UsuariosController');


Route::get('cidades/{id}/delete', 'CidadesController@delete')->name('cidades.delete');
Route::resource('cidades', 'CidadesController');


Route::get('bairros/{id}/delete', 'BairrosController@delete')->name('bairros.delete');
//Route::get('bairros', 'BairrosController@index')->middleware('route:bairros');
Route::resource('bairros', 'BairrosController');

Route::post('contatos/ligar', 'ContatosController@ligar')->name('contatos.ligar');
Route::get('contatos/imprimir', 'ContatosController@imprimir')->name('contatos.imprimir');
Route::get('contatos/{id}/delete', 'ContatosController@delete')->name('contatos.delete');
Route::resource('contatos', 'ContatosController');

Route::resource('home', 'HomeController', ['only' => [
    'index'
]]);

// GET	/photos	index	photos.index
// GET	/photos/create	create	photos.create
// POST	/photos	store	photos.store
// --- GET	/photos/{photo}	show	photos.show
// GET	/photos/{photo}/edit	edit	photos.edit
// --- PUT/PATCH	/photos/{photo}	update	photos.update
// DELETE	/photos/{photo}	destroy	photos.destroy
