<?php

// admin**************************************

Auth::routes(['register' => false]);

    Route::get('/', 'HomeController@index')->name('admin');
    Route::post('/save', 'HomeController@save');
    Route::get('delete/{id}','HomeController@destroy');
    Route::get('edit/{id}','HomeController@show');
    Route::post('edit/{id}','HomeController@edit');
    Route::get('/hide/{id}','HomeController@hide');
    Route::get('/unhide/{id}','HomeController@unhide');
    Route::post('/email_sendig', 'HomeController@mail_send');

    Route::get('/email_template/{id}', 'HomeController@create_email');
    Route::post('/email_template/{id}', 'HomeController@adminSendEmail');
    Route::get('/email_bcc_loading', 'HomeController@email_bcc_loading');

    
// useful links
    Route::get('/useful_links','HomeController@useful_links');
    Route::post('/savelinks','HomeController@savelinks');
    Route::get('/useful_links/hidelinks/{id}','HomeController@hidelinks');
    Route::get('useful_links/showlinks/{id}','HomeController@showlinks');
    Route::get('/useful_links/delete/{id}','HomeController@destroylinks');
    Route::get('/useful_links/edit/{id}','HomeController@edit_popup_link');
    Route::post('/useful_links/edit/{id}','HomeController@edit_link');


// upload newsletter
    Route::get('/newsletter', 'HomeController@newsletter');
    Route::post('/savenewsletter', 'HomeController@savenewsletter')->name('file.upload.post');
    Route::get('/newsletter/hidenewsletter/{id}','HomeController@hidenewsletter');
    Route::get('/newsletter/shownewsletter/{id}','HomeController@shownewsletter');
    Route::get('/newsletter/delete/{id}','HomeController@destroynews');
    Route::get('/newsletter/edit/{id}','HomeController@edit_popup_news');
    Route::post('/newsletter/edit/{id}','HomeController@edit_news');
    


// manage email controller

    Route::get('/manageEmails/', 'Admin\ManageEmailsController@index')->name('search');;
    Route::get('/donotsend/on/{id}', 'Admin\ManageEmailsController@donotsendOn');
    Route::get('/donotsend/off/{id}', 'Admin\ManageEmailsController@donotsendOff');
    Route::get('/fetchData', 'Admin\ManageEmailsController@fetchData')->name('fetchData');

    Route::post('save_email', 'Admin\ManageEmailsController@create');
    Route::get('edit_email/{id}','Admin\ManageEmailsController@show');
    Route::post('edit_email/{id}','Admin\ManageEmailsController@edit');

// send email feedbak


 Route::post('laravel-send-email', 'EmailController@sendEmail');

//autocomplete field 
Route::post('/autocomplete/fetch', 'HomeController@fetch')->name('autocomplete.fetch');

Route::post('/getprice', 'HomeController@getPrice')->name('autocomplete.getprice');