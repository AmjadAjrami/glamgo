<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/clear_cache', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    return 'Ok';
});

Route::get('/', function (){
    return view('landing_page.index');
})->name('landing_page.index');

Route::group(['prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

    Route::group(['prefix' => 'tmg'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.showLoginForm');
        Route::get('/login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.showLoginForm');
        Route::post('/login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('admin.login');
        Route::get('logout', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('admin.logout');

        Route::get('/dashboard', [\App\Http\Controllers\Admin\HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('get_notifications', [\App\Http\Controllers\Admin\HomeController::class, 'get_notifications']);
        Route::get('read_notifications', [\App\Http\Controllers\Admin\HomeController::class, 'read_notifications']);

        Route::controller(\App\Http\Controllers\Admin\ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('profile.index');
            Route::put('/profile/update', 'update')->name('profile.update');
            Route::put('/profile/update_password', 'update_password')->name('profile.update_password');
        });

        Route::controller(\App\Http\Controllers\Admin\AdminController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_admin']], function () {
                Route::get('/admins', 'index')->name('admins.index');
                Route::get('/admins/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_admin']], function () {
                Route::post('/admins', 'store')->name('admins.store');
            });
            Route::group(['middleware' => ['permission:edit_admin']], function () {
                Route::get('/admins/{id}/edit', 'edit')->name('admins.edit');
                Route::put('/admins/{id}/update', 'update')->name('admins.update');
            });
            Route::group(['middleware' => ['permission:delete_admin']], function () {
                Route::delete('/admins/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_admin_status']], function () {
                Route::put('/admins/update_status/{id}', 'update_status');
                Route::put('/admins/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\IntroController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_intro']], function () {
                Route::get('/intros', 'index')->name('intros.index');
                Route::get('/intros/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_intro']], function () {
                Route::post('/intros', 'store')->name('intros.store');
            });
            Route::group(['middleware' => ['permission:edit_intro']], function () {
                Route::put('/intros/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_intro']], function () {
                Route::delete('/intros/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_intro_status']], function () {
                Route::put('/intros/update_status/{id}', 'update_status');
                Route::put('/intros/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\CountryController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_country']], function () {
                Route::get('/countries', 'index')->name('countries.index');
                Route::get('/countries/indexTable', 'indexTable');
                Route::post('/countries/reorder', 'reorder')->name('countries.reorder');
            });
            Route::group(['middleware' => ['permission:add_country']], function () {
                Route::post('/countries', 'store')->name('countries.store');
            });
            Route::group(['middleware' => ['permission:edit_country']], function () {
                Route::put('/countries/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_country']], function () {
                Route::delete('/countries/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_country_status']], function () {
                Route::put('/countries/update_status/{id}', 'update_status');
                Route::put('/countries/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\CityController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_city']], function () {
                Route::get('/cities', 'index')->name('cities.index');
                Route::get('/cities/indexTable', 'indexTable');
                Route::post('/cities/reorder', 'reorder')->name('cities.reorder');
            });
            Route::group(['middleware' => ['permission:add_city']], function () {
                Route::post('/cities', 'store')->name('cities.store');
            });
            Route::group(['middleware' => ['permission:edit_city']], function () {
                Route::put('/cities/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_city']], function () {
                Route::delete('/cities/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_city_status']], function () {
                Route::put('/cities/update_status/{id}', 'update_status');
                Route::put('/cities/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\BannerController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_banner']], function () {
                Route::get('/banners', 'index')->name('banners.index');
                Route::get('/banners/{type}/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_banner']], function () {
                Route::post('/banners', 'store')->name('banners.store');
            });
            Route::group(['middleware' => ['permission:edit_banner']], function () {
                Route::put('/banners/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_banner']], function () {
                Route::delete('/banners/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_banner_status']], function () {
                Route::put('/banners/update_status/{id}', 'update_status');
                Route::put('/banners/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\CategoryController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_category']], function () {
                Route::get('/categories', 'index')->name('categories.index');
                Route::get('/categories/{type}/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_category']], function () {
                Route::post('/categories', 'store')->name('categories.store');
            });
            Route::group(['middleware' => ['permission:edit_category']], function () {
                Route::put('/categories/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_category']], function () {
                Route::delete('/categories/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_category_status']], function () {
                Route::put('/categories/update_status/{id}', 'update_status');
                Route::put('/categories/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\SalonController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_salon']], function () {
                Route::get('/salons', 'index')->name('salons.index');
                Route::get('/salons/{type}/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_salon']], function () {
                Route::post('/salons', 'store')->name('salons.store');
            });
            Route::group(['middleware' => ['permission:edit_salon']], function () {
                Route::put('/salons/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_salon']], function () {
                Route::delete('/salons/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_salon_status']], function () {
                Route::put('/salons/update_status/{id}', 'update_status');
                Route::put('/salons/update_all_status', 'update_all_status');
                Route::get('/salons/video/{id}', 'get_salon_video');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\MakeupArtistController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_artist']], function () {
                Route::get('/artists', 'index')->name('artists.index');
                Route::get('/artists/{type}/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_artist']], function () {
                Route::post('/artists', 'store')->name('artists.store');
            });
            Route::group(['middleware' => ['permission:edit_artist']], function () {
                Route::put('/artists/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_artist']], function () {
                Route::delete('/artists/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_artist_status']], function () {
                Route::put('/artists/update_status/{id}', 'update_status');
                Route::put('/artists/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\ProductController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_product']], function () {
                Route::get('/products', 'index')->name('products.index');
                Route::get('/products/indexTable', 'indexTable');
                Route::get('/products/{type}/categories_and_salons', 'get_categories_and_salons');
            });
            Route::group(['middleware' => ['permission:add_product']], function () {
                Route::post('/products', 'store')->name('products.store');
            });
            Route::group(['middleware' => ['permission:edit_product']], function () {
                Route::put('/products/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_product']], function () {
                Route::delete('/products/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_product_status']], function () {
                Route::put('/products/update_status/{id}', 'update_status');
                Route::put('/products/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\ReservationController::class)->group(function () {
            Route::group(['middleware' => ['permission:reservation']], function () {
                Route::get('/reservations', 'index')->name('reservations.index');
                Route::get('/reservations/indexTable', 'indexTable');
                Route::get('/reservations/reservation_details', 'reservation_details');
                Route::get('/reservations/ticket/{id}', 'ticket_template')->name('reservation.ticket_template');
            });
            Route::group(['middleware' => ['permission:edit_reservation_status']], function () {
                Route::put('/reservations/update_status', 'update_reservation_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\OrderController::class)->group(function () {
            Route::group(['middleware' => ['permission:order']], function () {
                Route::get('/orders', 'index')->name('orders.index');
                Route::get('/orders/indexTable', 'indexTable');
                Route::get('/orders/order_details', 'order_details');
                Route::get('/orders/invoice/{id}', 'order_invoice');
                Route::group(['middleware' => ['permission:edit_order_status']], function () {
                    Route::put('/orders/update_status', 'update_order_status');
                });
            });
        });

        Route::controller(\App\Http\Controllers\Admin\OfferController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_offer']], function () {
                Route::get('/offers', 'index')->name('offers.index');
                Route::get('/offers/indexTable', 'indexTable');
                Route::get('/offers/categories/{type}', 'categories');
            });
            Route::group(['middleware' => ['permission:add_offer']], function () {
                Route::post('/offers', 'store')->name('offers.store');
            });
            Route::group(['middleware' => ['permission:edit_offer']], function () {
                Route::put('/offers/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_offer']], function () {
                Route::delete('/offers/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_offer_status']], function () {
                Route::put('/offers/update_status/{id}', 'update_status');
                Route::put('/offers/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\PromoCodeController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_promo_code']], function () {
                Route::get('/promo_codes', 'index')->name('promo_codes.index');
                Route::get('/promo_codes/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_promo_code']], function () {
                Route::post('/promo_codes', 'store')->name('promo_codes.store');
            });
            Route::group(['middleware' => ['permission:edit_promo_code']], function () {
                Route::put('/promo_codes/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_promo_code']], function () {
                Route::delete('/promo_codes/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_promo_code_status']], function () {
                Route::put('/promo_codes/update_status/{id}', 'update_status');
                Route::put('/promo_codes/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\PaymentMethodController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_payment_method']], function () {
                Route::get('/payment_methods', 'index')->name('payment_methods.index');
                Route::get('/payment_methods/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_payment_method']], function () {
                Route::post('/payment_methods', 'store')->name('payment_methods.store');
            });
            Route::group(['middleware' => ['permission:edit_payment_method']], function () {
                Route::put('/payment_methods/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_payment_method']], function () {
                Route::delete('/payment_methods/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_payment_method_status']], function () {
                Route::put('/payment_methods/update_status/{id}', 'update_status');
                Route::put('/payment_methods/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\AboutController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_about_us']], function () {
                Route::get('/about_us', 'index')->name('about_us.index');
                Route::get('/about_us/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_about_us']], function () {
                Route::post('/about_us', 'store')->name('about_us.store');
            });
            Route::group(['middleware' => ['permission:edit_about_us']], function () {
                Route::put('/about_us/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_about_us']], function () {
                Route::delete('/about_us/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_about_us_status']], function () {
                Route::put('/about_us/update_status/{id}', 'update_status');
                Route::put('/about_us/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\TermsController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_terms']], function () {
                Route::get('/terms', 'index')->name('terms.index');
                Route::get('/terms/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_terms']], function () {
                Route::post('/terms', 'store')->name('terms.store');
            });
            Route::group(['middleware' => ['permission:edit_terms']], function () {
                Route::put('/terms/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_terms']], function () {
                Route::delete('/terms/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_terms_status']], function () {
                Route::put('/terms/update_status/{id}', 'update_status');
                Route::put('/terms/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\PrivacyController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_privacy']], function () {
                Route::get('/privacy', 'index')->name('privacy.index');
                Route::get('/privacy/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_privacy']], function () {
                Route::post('/privacy', 'store')->name('privacy.store');
            });
            Route::group(['middleware' => ['permission:edit_privacy']], function () {
                Route::put('/privacy/{id}/update', 'update');
            });
            Route::group(['middleware' => ['permission:delete_privacy']], function () {
                Route::delete('/privacy/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_privacy_status']], function () {
                Route::put('/privacy/update_status/{id}', 'update_status');
                Route::put('/privacy/update_all_status', 'update_all_status');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\UsersController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_user']], function () {
                Route::get('/users', 'index')->name('users.index');
                Route::get('/users/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:add_user']], function () {
                Route::post('/users', 'store')->name('users.store');
            });
            Route::group(['middleware' => ['permission:edit_user']], function () {
                Route::put('/users/{id}/update', 'update');
                Route::put('/users/{id}/update_balance', 'update_balance')->name('users.update_balance');
            });
            Route::group(['middleware' => ['permission:delete_user']], function () {
                Route::delete('/users/{id}/destroy', 'destroy');
            });
            Route::group(['middleware' => ['permission:edit_user_status']], function () {
                Route::put('/users/update_status/{id}', 'update_status');
                Route::put('/users/update_all_status', 'update_all_status');
                Route::get('/users/wallet/{id}', 'wallet')->name('users.wallet');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\NotificationController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_notification']], function () {
                Route::get('/notifications', 'index')->name('notifications.index');
                Route::get('/notifications/indexTable', 'indexTable')->name('notifications.indexTable');
                Route::get('/notifications/{id}/details', 'show');
            });
            Route::group(['middleware' => ['permission:add_notification']], function () {
                Route::post('/notifications', 'store')->name('notifications.store');
            });
            Route::group(['middleware' => ['permission:delete_notification']], function () {
                Route::delete('/notifications/{id}/destroy', 'destroy');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\SupportController::class)->group(function () {
            Route::group(['middleware' => ['permission:support']], function () {
                Route::get('/support', 'index')->name('support.index');
                Route::get('/support/user_details/{id}', 'user_details');
                Route::put('/support/update_message_read/{key}', 'update_message_read');
                Route::get('/support/send_message_notification', 'send_message_notification');
                Route::get('/support/translation_messages', 'translation_messages');
                Route::get('/support/translation_message', 'translate_message');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\ContactUsController::class)->group(function () {
            Route::group(['middleware' => ['permission:show_contact']], function () {
                Route::get('/contact_us', 'index')->name('contact_us.index');
                Route::get('/contact_us/indexTable', 'indexTable');
            });
            Route::group(['middleware' => ['permission:reply_contact']], function () {
                Route::put('/contact_us/{id}/reply', 'reply');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\SettingController::class)->group(function () {
            Route::group(['middleware' => ['permission:settings']], function () {
                Route::get('/settings', 'index')->name('settings.index');
                Route::put('/settings/update', 'update')->name('settings.update');
            });
        });

        Route::controller(\App\Http\Controllers\Admin\FinancialAccountsController::class)->group(function () {
            Route::group(['middleware' => ['permission:financial_accounts']], function () {
                Route::get('/financial_accounts', 'index')->name('financial_accounts.index');
                Route::get('/financial_accounts/salons/men', 'men_salons')->name('men_salons_financial_accounts.index');
                Route::get('/financial_accounts/salons/women', 'women_salons')->name('women_salons_financial_accounts.index');
                Route::get('/financial_accounts/makeup_artists', 'makeup_artists')->name('makeup_artists_financial_accounts.index');
                Route::post('/financial_accounts/make_withdraw', 'make_withdraw')->name('financial_accounts.make_withdraw');
                Route::get('/financial_accounts/withdrawals', 'withdrawals')->name('financial_accounts.withdrawals');
                Route::get('/financial_accounts/cash_transfers', 'get_cash_transfers')->name('cash_transfers.index');
                Route::get('/financial_accounts/update_status', 'update_status')->name('financial_accounts.update_status');
            });
        });
    });
});

/////////////////////// Salon Routes
Route::group(['prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {
    Route::group(['prefix' => 'salon'], function () {
        Route::get('/', [\App\Http\Controllers\Salon\Auth\LoginController::class, 'showLoginForm'])->name('salon.showLoginForm');
        Route::get('/login', [\App\Http\Controllers\Salon\Auth\LoginController::class, 'showLoginForm'])->name('salon.showLoginForm');
        Route::post('/login', [\App\Http\Controllers\Salon\Auth\LoginController::class, 'login'])->name('salon.login');
        Route::get('logout', [\App\Http\Controllers\Salon\Auth\LoginController::class, 'logout'])->name('salon.logout');

        Route::get('/dashboard', [\App\Http\Controllers\Salon\HomeController::class, 'index'])->name('salon.dashboard');
        Route::get('get_notifications', [\App\Http\Controllers\Salon\HomeController::class, 'get_notifications']);
        Route::get('read_notifications', [\App\Http\Controllers\Salon\HomeController::class, 'read_notifications']);

        Route::controller(\App\Http\Controllers\Salon\SalonInfoController::class)->group(function () {
            Route::get('/info', 'index')->name('salon_info.index');
            Route::put('/update', 'update')->name('salon_info.update');
        });

        Route::controller(\App\Http\Controllers\Salon\ServiceTypeController::class)->group(function () {
            Route::get('/salon_service_types', 'index')->name('salon_service_types.index');
            Route::get('/salon_service_types/indexTable', 'indexTable');
            Route::post('/salon_service_types', 'store')->name('salon_service_types.store');
            Route::put('/salon_service_types/{id}/update', 'update');
            Route::delete('/salon_service_types/{id}/destroy', 'destroy');
            Route::put('/salon_service_types/update_status/{id}', 'update_status');
            Route::put('/salon_service_types/update_all_status', 'update_all_status');
        });

        Route::controller(\App\Http\Controllers\Salon\ServiceController::class)->group(function () {
            Route::get('/salon_services', 'index')->name('salon_services.index');
            Route::get('/salon_services/indexTable', 'indexTable');
            Route::post('/salon_services', 'store')->name('salon_services.store');
            Route::put('/salon_services/{id}/update', 'update');
            Route::delete('/salon_services/{id}/destroy', 'destroy');
            Route::put('/salon_services/update_status/{id}', 'update_status');
            Route::put('/salon_services/update_all_status', 'update_all_status');
            Route::put('/salon_services/update_is_completed_status', 'update_is_completed_status');
        });

        Route::controller(\App\Http\Controllers\Salon\BookingTimesController::class)->group(function () {
            Route::get('/booking_times', 'index')->name('salon_booking_times.index');
            Route::post('/booking_times/update', 'update');
            Route::get('/booking_times/day', 'salon_day_booking_time');
            Route::post('/edit/time_settings', 'time_settings')->name('salon_booking_times.time_settings');
            Route::get('/add_times/{type}', 'add_times')->name('salon_booking_times.add_times');
        });

        Route::controller(\App\Http\Controllers\Salon\PromoCodeController::class)->group(function () {
            Route::get('/salon_promo_codes', 'index')->name('salon_promo_codes.index');
            Route::get('/salon_promo_codes/indexTable', 'indexTable');
            Route::post('/salon_promo_codes', 'store')->name('salon_promo_codes.store');
            Route::put('/salon_promo_codes/{id}/update', 'update');
            Route::delete('/salon_promo_codes/{id}/destroy', 'destroy');
            Route::put('/salon_promo_codes/update_status/{id}', 'update_status');
            Route::put('/salon_promo_codes/update_all_status', 'update_all_status');
        });

        Route::controller(\App\Http\Controllers\Salon\ReservationController::class)->group(function () {
            Route::get('/reservations', 'index')->name('salon_reservations.index');
            Route::get('/reservations/indexTable', 'indexTable');
            Route::put('/reservations/update_status', 'update_reservation_status');
            Route::get('/reservations/reservation_details', 'reservation_details');
            Route::get('/reservations/ticket/{id}', 'ticket_template');
            Route::get('/reservations/user_chat/{user_id}', 'user_chat');
        });

        Route::controller(\App\Http\Controllers\Salon\ChatController::class)->group(function () {
            Route::get('/chats', 'index')->name('salon_chats.index');
            Route::get('/chats/user_details/{id}', 'user_details');
            Route::put('/chats/update_message_read/{key}', 'update_message_read');
            Route::get('/chats/send_message_notification', 'send_message_notification');
            Route::get('/chats/translation_messages', 'translation_messages');
            Route::get('/chats/translation_message', 'translate_message');
        });

        Route::controller(\App\Http\Controllers\Salon\FinancialAccountsController::class)->group(function () {
            Route::get('/financial_accounts', 'index')->name('salon_financial_accounts.index');
            Route::get('/financial_accounts/update_status', 'update_status')->name('salon_financial_accounts.update_status');
            Route::post('/financial_accounts/make_withdraw', 'make_withdraw')->name('salon_financial_accounts.make_withdraw');
        });
    });
});

/////////////////////// Artist Routes
Route::group(['prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {
    Route::group(['prefix' => 'artist'], function () {
        Route::get('/', [\App\Http\Controllers\Artist\Auth\LoginController::class, 'showLoginForm'])->name('artist.showLoginForm');
        Route::get('/login', [\App\Http\Controllers\Artist\Auth\LoginController::class, 'showLoginForm'])->name('artist.showLoginForm');
        Route::post('/login', [\App\Http\Controllers\Artist\Auth\LoginController::class, 'login'])->name('artist.login');
        Route::get('logout', [\App\Http\Controllers\Artist\Auth\LoginController::class, 'logout'])->name('artist.logout');

        Route::get('/dashboard', [\App\Http\Controllers\Artist\HomeController::class, 'index'])->name('artist.dashboard');
        Route::get('get_notifications', [\App\Http\Controllers\Artist\HomeController::class, 'get_notifications']);
        Route::get('read_notifications', [\App\Http\Controllers\Artist\HomeController::class, 'read_notifications']);

        Route::controller(\App\Http\Controllers\Artist\ArtistInfoController::class)->group(function () {
            Route::get('/info', 'index')->name('artist_info.index');
            Route::put('/update', 'update')->name('artist_info.update');
        });

        Route::controller(\App\Http\Controllers\Artist\ServiceTypeController::class)->group(function () {
            Route::get('/artist_service_types', 'index')->name('artist_service_types.index');
            Route::get('/artist_service_types/indexTable', 'indexTable');
            Route::post('/artist_service_types', 'store')->name('artist_service_types.store');
            Route::put('/artist_service_types/{id}/update', 'update');
            Route::delete('/artist_service_types/{id}/destroy', 'destroy');
            Route::put('/artist_service_types/update_status/{id}', 'update_status');
            Route::put('/artist_service_types/update_all_status', 'update_all_status');
        });

        Route::controller(\App\Http\Controllers\Artist\ServiceController::class)->group(function () {
            Route::get('/artist_services', 'index')->name('artist_services.index');
            Route::get('/artist_services/indexTable', 'indexTable');
            Route::post('/artist_services', 'store')->name('artist_services.store');
            Route::put('/artist_services/{id}/update', 'update');
            Route::delete('/artist_services/{id}/destroy', 'destroy');
            Route::put('/artist_services/update_status/{id}', 'update_status');
            Route::put('/artist_services/update_all_status', 'update_all_status');
            Route::put('/artist_services/update_is_completed_status', 'update_is_completed_status');
        });

        Route::controller(\App\Http\Controllers\Artist\BookingTimesController::class)->group(function () {
            Route::get('/booking_times', 'index')->name('artist_booking_times.index');
            Route::post('/booking_times/update', 'update');
            Route::get('/booking_times/day', 'artist_day_booking_time');
            Route::post('/edit/time_settings', 'time_settings')->name('artist_booking_times.time_settings');
            Route::get('/add_times/{type}', 'add_times')->name('artist_booking_times.add_times');
        });

        Route::controller(\App\Http\Controllers\Artist\PromoCodeController::class)->group(function () {
            Route::get('/artist_promo_codes', 'index')->name('artist_promo_codes.index');
            Route::get('/artist_promo_codes/indexTable', 'indexTable');
            Route::post('/artist_promo_codes', 'store')->name('artist_promo_codes.store');
            Route::put('/artist_promo_codes/{id}/update', 'update');
            Route::delete('/artist_promo_codes/{id}/destroy', 'destroy');
            Route::put('/artist_promo_codes/update_status/{id}', 'update_status');
            Route::put('/artist_promo_codes/update_all_status', 'update_all_status');
        });

        Route::controller(\App\Http\Controllers\Artist\ReservationController::class)->group(function () {
            Route::get('/reservations', 'index')->name('artist_reservations.index');
            Route::get('/reservations/indexTable', 'indexTable');
            Route::put('/reservations/update_status', 'update_reservation_status');
            Route::get('/reservations/reservation_details', 'reservation_details');
            Route::get('/reservations/ticket/{id}', 'ticket_template');
            Route::get('/reservations/user_chat/{user_id}', 'user_chat');
        });

        Route::controller(\App\Http\Controllers\Artist\ChatController::class)->group(function () {
            Route::get('/chats', 'index')->name('artist_chats.index');
            Route::get('/chats/user_details/{id}', 'user_details');
            Route::put('/chats/update_message_read/{key}', 'update_message_read');
            Route::get('/chats/send_message_notification', 'send_message_notification');
            Route::get('/chats/translation_messages', 'translation_messages');
            Route::get('/chats/translation_message', 'translate_message');
        });

        Route::controller(\App\Http\Controllers\Artist\FinancialAccountsController::class)->group(function () {
            Route::get('/financial_accounts', 'index')->name('artist_financial_accounts.index');
            Route::get('/financial_accounts/update_status', 'update_status')->name('artist_financial_accounts.update_status');
            Route::post('/financial_accounts/make_withdraw', 'make_withdraw')->name('artist_financial_accounts.make_withdraw');
        });
    });
});


Route::get('/d', function () {
    $exitCode = \Illuminate\Support\Facades\Artisan::call('cache:clear');
    $exitCode = \Illuminate\Support\Facades\Artisan::call('config:clear');

    \Spatie\Permission\Models\Permission::query()->insert([
        ['name' => 'add_admin', 'guard_name' => 'admin', 'type' => 1],
        ['name' => 'edit_admin', 'guard_name' => 'admin', 'type' => 1],
        ['name' => 'delete_admin', 'guard_name' => 'admin', 'type' => 1],
        ['name' => 'show_admin', 'guard_name' => 'admin', 'type' => 1],
        ['name' => 'edit_admin_status', 'guard_name' => 'admin', 'type' => 1],

        ['name' => 'add_country', 'guard_name' => 'admin', 'type' => 2],
        ['name' => 'edit_country', 'guard_name' => 'admin', 'type' => 2],
        ['name' => 'delete_country', 'guard_name' => 'admin', 'type' => 2],
        ['name' => 'show_country', 'guard_name' => 'admin', 'type' => 2],
        ['name' => 'edit_country_status', 'guard_name' => 'admin', 'type' => 2],

        ['name' => 'add_city', 'guard_name' => 'admin', 'type' => 3],
        ['name' => 'edit_city', 'guard_name' => 'admin', 'type' => 3],
        ['name' => 'delete_city', 'guard_name' => 'admin', 'type' => 3],
        ['name' => 'show_city', 'guard_name' => 'admin', 'type' => 3],
        ['name' => 'edit_city_status', 'guard_name' => 'admin', 'type' => 3],

        ['name' => 'add_category', 'guard_name' => 'admin', 'type' => 4],
        ['name' => 'edit_category', 'guard_name' => 'admin', 'type' => 4],
        ['name' => 'delete_category', 'guard_name' => 'admin', 'type' => 4],
        ['name' => 'show_category', 'guard_name' => 'admin', 'type' => 4],
        ['name' => 'edit_category_status', 'guard_name' => 'admin', 'type' => 4],

        ['name' => 'add_banner', 'guard_name' => 'admin', 'type' => 5],
        ['name' => 'edit_banner', 'guard_name' => 'admin', 'type' => 5],
        ['name' => 'delete_banner', 'guard_name' => 'admin', 'type' => 5],
        ['name' => 'show_banner', 'guard_name' => 'admin', 'type' => 5],
        ['name' => 'edit_banner_status', 'guard_name' => 'admin', 'type' => 5],

        ['name' => 'add_salon', 'guard_name' => 'admin', 'type' => 6],
        ['name' => 'edit_salon', 'guard_name' => 'admin', 'type' => 6],
        ['name' => 'delete_salon', 'guard_name' => 'admin', 'type' => 6],
        ['name' => 'show_salon', 'guard_name' => 'admin', 'type' => 6],
        ['name' => 'edit_salon_status', 'guard_name' => 'admin', 'type' => 6],

        ['name' => 'add_artist', 'guard_name' => 'admin', 'type' => 7],
        ['name' => 'edit_artist', 'guard_name' => 'admin', 'type' => 7],
        ['name' => 'delete_artist', 'guard_name' => 'admin', 'type' => 7],
        ['name' => 'show_artist', 'guard_name' => 'admin', 'type' => 7],
        ['name' => 'edit_artist_status', 'guard_name' => 'admin', 'type' => 7],

        ['name' => 'add_product', 'guard_name' => 'admin', 'type' => 8],
        ['name' => 'edit_product', 'guard_name' => 'admin', 'type' => 8],
        ['name' => 'delete_product', 'guard_name' => 'admin', 'type' => 8],
        ['name' => 'show_product', 'guard_name' => 'admin', 'type' => 8],
        ['name' => 'edit_product_status', 'guard_name' => 'admin', 'type' => 8],

        ['name' => 'add_promo_code', 'guard_name' => 'admin', 'type' => 9],
        ['name' => 'edit_promo_code', 'guard_name' => 'admin', 'type' => 9],
        ['name' => 'delete_promo_code', 'guard_name' => 'admin', 'type' => 9],
        ['name' => 'show_promo_code', 'guard_name' => 'admin', 'type' => 9],
        ['name' => 'edit_promo_code_status', 'guard_name' => 'admin', 'type' => 9],

        ['name' => 'add_user', 'guard_name' => 'admin', 'type' => 10],
        ['name' => 'edit_user', 'guard_name' => 'admin', 'type' => 10],
        ['name' => 'delete_user', 'guard_name' => 'admin', 'type' => 10],
        ['name' => 'show_user', 'guard_name' => 'admin', 'type' => 10],
        ['name' => 'edit_user_status', 'guard_name' => 'admin', 'type' => 10],

        ['name' => 'reservation', 'guard_name' => 'admin', 'type' => 11],
        ['name' => 'edit_reservation_status', 'guard_name' => 'admin', 'type' => 11],

        ['name' => 'add_offer', 'guard_name' => 'admin', 'type' => 12],
        ['name' => 'edit_offer', 'guard_name' => 'admin', 'type' => 12],
        ['name' => 'delete_offer', 'guard_name' => 'admin', 'type' => 12],
        ['name' => 'show_offer', 'guard_name' => 'admin', 'type' => 12],
        ['name' => 'edit_offer_status', 'guard_name' => 'admin', 'type' => 12],

        ['name' => 'order', 'guard_name' => 'admin', 'type' => 13],
        ['name' => 'edit_order_status', 'guard_name' => 'admin', 'type' => 13],

        ['name' => 'add_terms', 'guard_name' => 'admin', 'type' => 14],
        ['name' => 'edit_terms', 'guard_name' => 'admin', 'type' => 14],
        ['name' => 'show_terms', 'guard_name' => 'admin', 'type' => 14],

        ['name' => 'add_about_us', 'guard_name' => 'admin', 'type' => 15],
        ['name' => 'edit_about_us', 'guard_name' => 'admin', 'type' => 15],
        ['name' => 'show_about_us', 'guard_name' => 'admin', 'type' => 15],

        ['name' => 'add_privacy', 'guard_name' => 'admin', 'type' => 16],
        ['name' => 'edit_privacy', 'guard_name' => 'admin', 'type' => 16],
        ['name' => 'show_privacy', 'guard_name' => 'admin', 'type' => 16],

        ['name' => 'add_intro', 'guard_name' => 'admin', 'type' => 17],
        ['name' => 'edit_intro', 'guard_name' => 'admin', 'type' => 17],
        ['name' => 'delete_intro', 'guard_name' => 'admin', 'type' => 17],
        ['name' => 'show_intro', 'guard_name' => 'admin', 'type' => 17],
        ['name' => 'edit_intro_status', 'guard_name' => 'admin', 'type' => 17],

        ['name' => 'reply_contact', 'guard_name' => 'admin', 'type' => 18],
        ['name' => 'show_contact', 'guard_name' => 'admin', 'type' => 18],

        ['name' => 'settings', 'guard_name' => 'admin', 'type' => 19],

        ['name' => 'support', 'guard_name' => 'admin', 'type' => 20],

        ['name' => 'add_payment_method', 'guard_name' => 'admin', 'type' => 21],
        ['name' => 'edit_payment_method', 'guard_name' => 'admin', 'type' => 21],
        ['name' => 'delete_payment_method', 'guard_name' => 'admin', 'type' => 21],
        ['name' => 'show_payment_method', 'guard_name' => 'admin', 'type' => 21],
        ['name' => 'edit_payment_method_status', 'guard_name' => 'admin', 'type' => 21],

        ['name' => 'add_notification', 'guard_name' => 'admin', 'type' => 22],
        ['name' => 'edit_notification', 'guard_name' => 'admin', 'type' => 22],
        ['name' => 'delete_notification', 'guard_name' => 'admin', 'type' => 22],
        ['name' => 'show_notification', 'guard_name' => 'admin', 'type' => 22],
        ['name' => 'edit_notification_status', 'guard_name' => 'admin', 'type' => 22],

        ['name' => 'financial_accounts', 'guard_name' => 'admin', 'type' => 23],
    ]);

    $admin = \App\Models\Admin::query()->findOrFail(1);
    $admin->givePermissionTo([
        'add_admin',
        'edit_admin',
        'delete_admin',
        'show_admin',
        'edit_admin_status',

        'add_country',
        'edit_country',
        'delete_country',
        'show_country',
        'edit_country_status',

        'add_city',
        'edit_city',
        'delete_city',
        'show_city',
        'edit_city_status',

        'add_category',
        'edit_category',
        'delete_category',
        'show_category',
        'edit_category_status',

        'add_banner',
        'edit_banner',
        'delete_banner',
        'show_banner',
        'edit_banner_status',

        'add_salon',
        'edit_salon',
        'delete_salon',
        'show_salon',
        'edit_salon_status',

        'add_artist',
        'edit_artist',
        'delete_artist',
        'show_artist',
        'edit_artist_status',

        'add_product',
        'edit_product',
        'delete_product',
        'show_product',
        'edit_product_status',

        'add_promo_code',
        'edit_promo_code',
        'delete_promo_code',
        'show_promo_code',
        'edit_promo_code_status',

        'add_user',
        'edit_user',
        'delete_user',
        'show_user',
        'edit_user_status',

        'reservation',
        'edit_reservation_status',

        'add_offer',
        'edit_offer',
        'delete_offer',
        'show_offer',
        'edit_offer_status',

        'order',
        'edit_order_status',

        'add_terms',
        'edit_terms',
        'show_terms',

        'add_about_us',
        'edit_about_us',
        'show_about_us',

        'add_privacy',
        'edit_privacy',
        'show_privacy',

        'add_intro',
        'edit_intro',
        'delete_intro',
        'show_intro',
        'edit_intro_status',

        'reply_contact',
        'show_contact',

        'settings',

        'support',

        'add_payment_method',
        'edit_payment_method',
        'delete_payment_method',
        'show_payment_method',
        'edit_payment_method_status',

        'add_notification',
        'edit_notification',
        'delete_notification',
        'show_notification',
        'edit_notification_status',

        'financial_accounts'
    ]);
});
