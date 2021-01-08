<?php
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api/admin')->namespace('VCComponent\Laravel\ConfigContact\Http\Controllers\Admin')->group(function () {
    Route::resources(['contact-form' => 'ContactFormController']);
    Route::get('contact-forms/list', 'ContactFormController@list');

    Route::resources(['contact-form-input' => 'ContactFormInputController'], ['only' => ['show', 'store', 'update', 'destroy']]);

    Route::resources(['contact-form-value' => 'ContactFormValueAdminController'], ['only' => ['index', 'show', 'update', 'destroy']]);

    Route::put('contact-form-value/{id}/status', 'ContactFormValueAdminController@changeStatus');

    Route::get('contact-form/{id}/contact-form-value', 'ContactFormValueAdminController@getPayload');

    Route::get('payload/search', 'ContactFormValueAdminController@searchPayload');
});
