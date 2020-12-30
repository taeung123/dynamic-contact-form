<?php
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api/admin')->namespace('VCComponent\Laravel\ConfigContact\Http\Controllers\Admin')->group(function () {
    Route::resources(['contact-form' => 'ContactFormController']);

    Route::post('contact-form-input', 'ContactFormInputController@createInput');
    Route::put('contact-form-input/{id}', 'ContactFormInputController@updateInput');
    Route::delete('contact-form-input/{id}', 'ContactFormInputController@deleteInput');

    Route::resources(['contact-form-value' => 'ContactFormValueAdminController'], ['only' => ['index', 'show']]);
});
