<?php

use App\Patient;
use Illuminate\Support\Facades\Input;
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

Route::get('/', function () {
    return view('/auth/login');
});
//Auth::routes();

// Authentication Routes...
   Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
   Route::post('login', 'Auth\LoginController@login');
   Route::post('logout', 'Auth\LoginController@logout')->name('logout');

   // Password Reset Routes...
   Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
   Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
   Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
   Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::any ( '/search', function () {
    $q = Input::get ( 'q' );
    $user = Patient::where ( 'name', 'LIKE', '%' . $q . '%' )->orWhere ( 'email', 'LIKE', '%' . $q . '%' )->get ();
    if (count ( $user ) > 0)
        return view ( 'search' )->withDetails ( $user )->withQuery ( $q );
    else
        return view ( 'search' )->withMessage ( 'No Details found. Try to search again !' );
} );

Route::group(['middleware' => 'can:admin'], function() {

Route::get('/report','ReceptionController@display');

Route::get('/admin','HomeController@index');

Route::resource('drug', 'DrugsController');

Route::resource('order', 'OrderController');

Route::get('orders/{id}', 'OrderController@add')->name('orders');

Route::get('drugorder/{id}', 'OrderController@create')->name('drugorder');


// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

//Route::resource('register','RegisterController');

Route::resource('employee', 'EmployeesController');

});

Route::group(['middleware' => 'can:doctor'], function() {

Route::resource('patient', 'PatientsController');

});

Route::group(['middleware' => 'can:doctor'], function() {

Route::resource('prescription', 'PrescriptionController');

});

Route::group(['middleware' => 'can:worker'], function() {

Route::resource('reception', 'ReceptionController');

});

Route::group(['middleware' => 'can:doctor'], function() {

Route::get('patient-prescription/{id}', 'PrescriptionController@add')->name('patient-prescription');

});

Route::group(['middleware' => 'can:worker'], function() {

Route::get('patient-reception/{id}', 'ReceptionController@add')->name('patient-reception');

});

Route::group(['middleware' => 'can:doctor'], function() {

Route::get('patientprescription/{id}', 'PrescriptionController@create')->name('patientprescription');
});
