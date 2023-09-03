<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('order_payment_webview', [\App\Http\Controllers\API\HomeController::class, 'order_webview'])->name('order_webview');
Route::post('order_callback', [\App\Http\Controllers\API\HomeController::class, 'order_callback']);
Route::get('reservation_payment_webview', [\App\Http\Controllers\API\HomeController::class, 'reservation_webview'])->name('reservation_webview');
Route::post('reservation_callback', [\App\Http\Controllers\API\HomeController::class, 'reservation_callback']);
Route::get('add_balance_webview', [\App\Http\Controllers\API\ProfileController::class, 'add_balance_webview'])->name('add_balance_webview');
Route::post('add_balance_callback', [\App\Http\Controllers\API\ProfileController::class, 'add_balance_callback'])->name('add_balance_callback');
Route::get('success', [\App\Http\Controllers\API\HomeController::class, 'success'])->name('success');
Route::get('fail', [\App\Http\Controllers\API\HomeController::class, 'fail'])->name('fail');

Route::get('intros', [\App\Http\Controllers\API\HomeController::class, 'intros']);

Route::get('countries', [\App\Http\Controllers\API\HomeController::class, 'countries']);
Route::get('country_cities/{id}', [\App\Http\Controllers\API\HomeController::class, 'country_cities']);

Route::post('register', [\App\Http\Controllers\API\Auth\RegisterController::class, 'register']);
Route::post('verify_code', [\App\Http\Controllers\API\Auth\RegisterController::class, 'verifyCode']);
Route::post('login', [\App\Http\Controllers\API\Auth\LoginController::class, 'login']);
Route::get('logout', [\App\Http\Controllers\API\Auth\LoginController::class, 'logout']);

Route::post('social_login', [\App\Http\Controllers\API\Auth\LoginController::class, 'social_login']);

Route::post('forgot_password', [\App\Http\Controllers\API\Auth\ForgotPasswordController::class, 'forget_password']);
Route::post('verify_reset_password_code', [\App\Http\Controllers\API\Auth\ForgotPasswordController::class, 'verify_code']);
Route::post('reset_password', [\App\Http\Controllers\API\Auth\ForgotPasswordController::class, 'reset_password']);

Route::get('home', [\App\Http\Controllers\API\HomeController::class, 'home']);
Route::get('home_search', [\App\Http\Controllers\API\HomeController::class, 'home_search']);

Route::get('categories', [\App\Http\Controllers\API\HomeController::class, 'categories']);

Route::get('salons', [\App\Http\Controllers\API\HomeController::class, 'salons']);
Route::get('nearest_salons', [\App\Http\Controllers\API\HomeController::class, 'nearest_salons']);
Route::get('map_search', [\App\Http\Controllers\API\HomeController::class, 'map_search']);
Route::get('salon_details', [\App\Http\Controllers\API\HomeController::class, 'salon_details']);

Route::get('offers', [\App\Http\Controllers\API\HomeController::class, 'offers']);
Route::get('offer_details', [\App\Http\Controllers\API\HomeController::class, 'offer_details']);

Route::get('artists', [\App\Http\Controllers\API\HomeController::class, 'artists']);
Route::get('artist_details', [\App\Http\Controllers\API\HomeController::class, 'artist_details']);

Route::get('home_services', [\App\Http\Controllers\API\HomeController::class, 'home_services']);
Route::get('home_service_details', [\App\Http\Controllers\API\HomeController::class, 'home_service_details']);

Route::get('store', [\App\Http\Controllers\API\HomeController::class, 'store']);
Route::get('store_search', [\App\Http\Controllers\API\HomeController::class, 'store_search']);
Route::get('products', [\App\Http\Controllers\API\HomeController::class, 'products']);

Route::get('product_details', [\App\Http\Controllers\API\HomeController::class, 'product_details']);
Route::get('salon_products', [\App\Http\Controllers\API\HomeController::class, 'salon_products']);

Route::get('privacy_policy', [\App\Http\Controllers\API\HomeController::class, 'privacy_policy']);
Route::get('terms', [\App\Http\Controllers\API\HomeController::class, 'terms']);
Route::get('about_us', [\App\Http\Controllers\API\HomeController::class, 'about_us']);
Route::post('contact_us', [\App\Http\Controllers\API\HomeController::class, 'contact_us']);

Route::get('payment_methods', [\App\Http\Controllers\API\HomeController::class, 'payment_methods']);
Route::get('charge_wallet_payment_methods', [\App\Http\Controllers\API\HomeController::class, 'charge_wallet_payment_methods']);
Route::get('app_config', [\App\Http\Controllers\API\HomeController::class, 'app_config']);
Route::get('social_media_links', [\App\Http\Controllers\API\HomeController::class, 'social_media_links']);

Route::group(['middleware' => 'auth_check'], function () {
    Route::get('profile', [\App\Http\Controllers\API\ProfileController::class, 'get_profile']);
    Route::post('update_profile', [\App\Http\Controllers\API\ProfileController::class, 'update_profile']);
    Route::post('update_password', [\App\Http\Controllers\API\ProfileController::class, 'update_password']);
    Route::delete('delete_account', [\App\Http\Controllers\API\ProfileController::class, 'delete_account']);
    Route::put('update_notification_status', [\App\Http\Controllers\API\ProfileController::class, 'update_notification_status']);
    Route::post('add_address', [\App\Http\Controllers\API\ProfileController::class, 'add_address']);
    Route::get('addresses', [\App\Http\Controllers\API\ProfileController::class, 'addresses']);
    Route::delete('delete_address', [\App\Http\Controllers\API\ProfileController::class, 'delete_address']);

    Route::get('wallet', [\App\Http\Controllers\API\ProfileController::class, 'wallet']);
    Route::post('add_balance', [\App\Http\Controllers\API\ProfileController::class, 'add_balance']);

    Route::get('check_promo_code', [\App\Http\Controllers\API\HomeController::class, 'check_promo_code']);

    Route::get('days', [\App\Http\Controllers\API\HomeController::class, 'days']);
    Route::get('booking_times', [\App\Http\Controllers\API\HomeController::class, 'booking_times']);
    Route::post('reservation', [\App\Http\Controllers\API\HomeController::class, 'reservation']);
    Route::post('offer_reservation', [\App\Http\Controllers\API\HomeController::class, 'offer_reservation']);
    Route::post('determine_reservation_booking_time', [\App\Http\Controllers\API\HomeController::class, 'determine_reservation_booking_time']);
    Route::post('pay_for_reservation', [\App\Http\Controllers\API\HomeController::class, 'pay_for_reservation']);
    Route::get('user_reservations', [\App\Http\Controllers\API\HomeController::class, 'user_reservations']);
    Route::get('reservation_details', [\App\Http\Controllers\API\HomeController::class, 'reservation_details']);
    Route::put('cancel_reservation', [\App\Http\Controllers\API\HomeController::class, 'cancel_reservation']);

    Route::get('generate_token', function (){
        return mainResponse_2(true, __('ok'), json_decode(generate_token()), [], 200);
    });

    Route::post('add_to_cart', [\App\Http\Controllers\API\HomeController::class, 'add_to_cart']);
    Route::get('get_cart', [\App\Http\Controllers\API\HomeController::class, 'cart']);
    Route::put('increment_decrement_product', [\App\Http\Controllers\API\HomeController::class, 'increment_decrement_product']);
    Route::delete('delete_cart_item', [\App\Http\Controllers\API\HomeController::class, 'delete_cart_item']);
    Route::post('order', [\App\Http\Controllers\API\HomeController::class, 'order']);
    Route::get('orders', [\App\Http\Controllers\API\HomeController::class, 'get_orders']);
    Route::get('order_details', [\App\Http\Controllers\API\HomeController::class, 'order_details']);
    Route::put('cancel_order', [\App\Http\Controllers\API\HomeController::class, 'cancel_order']);
    Route::put('complete_order', [\App\Http\Controllers\API\HomeController::class, 'complete_order']);

    Route::post('create_chat', [\App\Http\Controllers\API\HomeController::class, 'create_chat']);
    Route::get('chats', [\App\Http\Controllers\API\HomeController::class, 'chats']);
    Route::get('read_chat', [\App\Http\Controllers\API\HomeController::class, 'read_chat']);
    Route::get('translate_chat_message', [\App\Http\Controllers\API\HomeController::class, 'translate_chat_message']);

    Route::post('create_support_chat', [\App\Http\Controllers\API\HomeController::class, 'create_support_chat']);
    Route::get('read_support_chat', [\App\Http\Controllers\API\HomeController::class, 'read_support_chat']);

    Route::post('favorite', [\App\Http\Controllers\API\HomeController::class, 'favorite']);
    Route::get('get_favorites', [\App\Http\Controllers\API\HomeController::class, 'get_favorites']);

    Route::get('notifications', [\App\Http\Controllers\API\HomeController::class, 'notifications']);
    Route::delete('notifications/delete', [\App\Http\Controllers\API\HomeController::class, 'delete_notification']);
});
