<?php
use Illuminate\Support\Facades\Route;
Route::middleware('api')->prefix('api/admin')->namespace('VCComponent\Laravel\ConfigContact\Http\Controllers\Admin')->group(function () {
    Route::resources(['contact-form' => 'ContactFormController']);

    Route::put('contact-form/{id}/change-status', 'ContactFormController@changeStatus');

    Route::get('contact-forms/list', 'ContactFormController@list');

    Route::resources(['contact-form-input' => 'ContactFormInputController'], ['only' => ['show', 'store', 'update', 'destroy']]);

    Route::resources(['contact-form-value' => 'ContactFormValueAdminController'], ['only' => ['index', 'show', 'update']]);
    
    Route::delete('contact-form-value/bulk', 'ContactFormValueAdminController@bulkDelete');
    
    Route::delete('contact-form-value/{id}', 'ContactFormValueAdminController@destroy');

    Route::put('contact-form-value/{id}/status', 'ContactFormValueAdminController@changeStatus');

    Route::get('contact-form/{id}/contact-form-value', 'ContactFormValueAdminController@getPayload');

    Route::get('get-page-list', function () {
        $page_list = [];

        foreach (config('dynamic-contact-form.page') as $key => $value) {
            $page_list[$key] = $value['label'];
        }

        return $page_list;
    });

    Route::get('get-position-list/{slug}', function ($slug) {
        $list_position = config('dynamic-contact-form.page.' . $slug . '.position');

        return $list_position;
    });
});
