<?php

use Illuminate\Support\Facades\Route;

Route::post('/send-contact-infor','VCComponent\Laravel\ConfigContact\Http\Controllers\FrontEnd\ContactFormValueController@store')->name('send');
