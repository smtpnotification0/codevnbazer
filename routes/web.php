<?php  
  
use App\Http\Controllers\UserController;  
use App\Http\Controllers\Auth\GoogleController;  
use App\Http\Controllers\HomeController;  
use App\Http\Controllers\PWAController;  
use App\Http\Controllers\OrdersController;  
use App\Http\Controllers\Gateway\PaymentController;  
use App\Http\Controllers\ReferralController;  
use App\Http\Controllers\TransactionsController;  
use App\Http\Controllers\Admin\AdminController;  
use App\Http\Controllers\Admin\AdminTransactionController;  
use App\Http\Middleware\AuthUser;  
use App\Http\Middleware\CheckUser;  
use App\Http\Middleware\AuthAdmin;  
use Illuminate\Support\Facades\Artisan;  
use Illuminate\Support\Facades\Route;  
use Illuminate\Support\Facades\Http;  
use Illuminate\Http\Request;  
  
Route::get('/clear', function () {  
    $output = new \Symfony\Component\Console\Output\BufferedOutput();  
    Artisan::call('optimize:clear', array(), $output);  
    return $output->fetch();  
})->name('/clear');  
  
Route::get('schedule-run', function () {  
    return Illuminate\Support\Facades\Artisan::call('schedule:run');  
})->name('cron');  
  
Route::get('/manifest.json', [PWAController::class, 'manifestJson'])->name('manifest');  
Route::get('/offline.html', [PWAController::class, 'offline']);  
  
Route::get('/', [HomeController::class, 'home'])->name('home');  
Route::get('topup/{slug}', [HomeController::class, 'topup'])->name('topup');  
Route::get('/get-popup', [HomeController::class, 'getPopups'])->name('popup');  
Route::post('uid-checker/check', function (Request $request) {  
    $response = Http::get('https://faas-sgp1-18bc02ac.doserverless.co/api/v1/web/fn-d48311ea-349c-4d0b-b4ea-bab4a937cbf8/default/FreeFire', [  
        'id' => $request->id,  
    ]);  
    return response()->json($response->json());  
})->name('uidcheck');

Route::post('player-name/check', [HomeController::class, 'checkPlayerName'])->name('player.name.check');  
Route::middleware(CheckUser::class)->group(function() {  
  Route::get('login', [UserController::class, 'login'])->name('login');  
  Route::get('register', [UserController::class, 'register'])->name('register');  
  Route::get('forget-password', [UserController::class, 'forget'])->name('forget');  
  Route::get('reset-password/{token}', [UserController::class, 'resetpassword'])->name('password.reset');  
  Route::post('signin', [UserController::class, 'signin'])->name('signin');  
  Route::post('signup', [UserController::class, 'signup'])->name('signup');  
  Route::post('forget-password', [UserController::class, 'forget_password'])->name('password.email');  
  Route::post('reset-password', [UserController::class, 'reset_password'])->name('password.update');  
  Route::get('auth/redirect', [GoogleController::class, 'redirectToGoogle']);  
  Route::get('google-callback', [GoogleController::class, 'handleGoogleCallback']);  
});  
  
Route::middleware(AuthUser::class)->group(function(){  
  Route::get('account', [UserController::class, 'account'])->name('account');  
  Route::get('add-funds', [HomeController::class, 'addfunds'])->name('addfunds');  
   // User Transactions Page  
    Route::get('transactions', [TransactionsController::class, 'index'])->name('transactions');  
  Route::get('orders', [HomeController::class, 'orders'])->name('orders');  
  Route::get('codes', [HomeController::class, 'codes'])->name('codes');  
    
  // Payment Gateway Routes
  Route::post('add-funds/deposit', [PaymentController::class, 'deposit'])->name('deposit'); // Initiate Deposit
  Route::get('payment', [PaymentController::class, 'payment'])->name('payment'); // Deposit Success/Verification Callback
  Route::get('payment/cancel', [PaymentController::class, 'payment_cancel'])->name('cancel.payment'); // General Cancel Callback
    
  // Order Routes
  Route::get('order/success/{order}', [OrdersController::class, 'success'])->name('order.success'); // Order Success Page
  Route::get('payment/success', [OrdersController::class, 'paymentSuccess'])->name('payment.success'); // Order Success/Verification Callback
  Route::post('topup/buynow', [OrdersController::class, 'buynow'])->name('topup.buynow'); // Initiate Order
    
  Route::get('logout', [UserController::class, 'logout'])->name('logout');  
});