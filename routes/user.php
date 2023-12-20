<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->name('logout');
    });

    Route::get('/onboard', function () {
        // Check if the user is logged in
        if (Auth::check()) {
            // User is logged in, load a view for authenticated users
            return view('templates.basic.user.user-onboard');
        } else {
            // User is not logged in, load a view for guests
            return view('templates.basic.partials.onboard-create-page');
        }
    })->name('onboard');

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register')->middleware('registration.status');
        Route::post('check-mail', 'checkUser')->name('checkUser');
       
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });
    
    Route::controller('ForgotPasswordController')->group(function () {
         Route::post('store-page/{id?}', 'storePage')->name('storePage');
    });
    
    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
    
    // on board for user
    Route::controller('UserController')->group(function () {
                Route::post('storeOnboardData', 'storeOnboardData')->name('storeOnboardData');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    //authorization
    Route::namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });

    Route::middleware(['check.status'])->group(function () {

        Route::get('user-data', 'User\UserController@userData')->name('data');
        Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

        Route::middleware('registration.complete')->namespace('User')->group(function () {



            //Campaign Fundraise
            Route::controller('CampaignController')->prefix('campaign')->name('campaign.fundrise.')->group(function () {

                Route::get('fundrise', 'create')->name('create');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('store/{id?}', 'storeCampaign')->name('store');
               
 
                Route::get('approved', 'approvedCampaign')->name('approved');
                Route::get('pending', 'pendingCampaign')->name('pending');
                Route::get('success', 'completeCampaign')->name('complete');
                Route::get('expired', 'expiredCampaign')->name('expired');
                Route::get('rejected', 'rejectedCampaign')->name('rejected');
                Route::get('all', 'allCampaign')->name('all');

                Route::get('details/{slug}/{id}', 'campaignDetails')->name('view');
                Route::post('complete/{id}', 'complete')->name('make.complete');
                Route::post('stop/{id}', 'runAndStop')->name('stop');
                Route::post('delete/{id}', 'delete')->name('delete');
                Route::post('extended/{id}', 'extended')->name('extended');
            });

            Route::controller('DonationController')->prefix('campaign/donation')->name('campaign.donation.')->group(function () {
                Route::get('my/{campaignId?}', 'myDonation')->name('my');
                Route::get('received/{campaignId?}', 'receivedDonation')->name('received');
                Route::get('details/{id}', 'details')->name('details');
            });


            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');

                Route::get('attachment-download/{fil_hash}', 'attachmentDownload')->name('attachment.download');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                Route::middleware('kyc')->group(function () {
                    Route::get('/', 'withdrawMoney');
                    Route::post('/', 'withdrawStore')->name('.money');
                    Route::get('preview', 'withdrawPreview')->name('.preview');
                    Route::post('preview', 'withdrawSubmit')->name('.submit');
                });
                Route::get('history', 'withdrawLog')->name('.history');
            });
        });
    });
});
