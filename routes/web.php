<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');
Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::post('subscribe', 'subscribe')->name('subscribe');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');
    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});

Route::controller('CampaignController')->prefix('campaign')->name('campaign.')->group(function () {
    Route::get('index', 'index')->name('index');
    Route::get('campaign', 'filterCampaign')->name('filter');
    Route::get('campaigns', 'getCampaignsByName')->name('getCampaignsByName');
    Route::get('details/{slug}/{id}', 'details')->name('details');
    // Comment
    Route::post('comment', 'comment')->name('comment');
});

Route::controller('DonationController')->prefix('campaign/donation')->name('campaign.donation.')->group(function () {
    Route::post('/{slug?}/{id?}', 'donation')->name('process');
});

// Payment
Route::prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
    Route::any('/index', 'deposit')->name('index');
    Route::post('insert', 'depositInsert')->name('insert');
    Route::get('confirm', 'depositConfirm')->name('confirm');
    Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
    Route::post('manual', 'manualDepositUpdate')->name('manual.update');
});


Route::controller('VolunteerController')->prefix('volunteer')->name('volunteer.')->group(function () {
    Route::get('join-as/volunteer', 'form')->name('form');
    Route::post('store', 'store')->name('store');
    Route::get('index', 'index')->name('index');
    Route::get('search/filter', 'filter')->name('filter');
});

Route::controller('SuccessStoryController')->prefix('success-story')->name('success.story.')->group(function () {
    Route::get('stories', 'index')->name('archive');
    Route::get('details/{slug}/{id}', 'details')->name('details');
    Route::post('comment/{storyId}', 'comment')->name('comment');
});
