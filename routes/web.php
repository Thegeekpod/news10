<?php

use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/news/{slug}', [PostController::class, 'show'])->name('post.show');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/api/stocks', function () {
    return \Illuminate\Support\Facades\Cache::remember('market_stocks_v2', 15, function () {
        try {
            $symbols = '^NSEI,^BSESN,RELIANCE.NS,TCS.NS,HDFCBANK.NS,BHARTIARTL.NS,ICICIBANK.NS,INFY.NS,ITC.NS,SBI.NS,L&TFH.NS';
            
            // Cache crumb and cookies for 1 hour to prevent IP block
            $yahooAuth = \Illuminate\Support\Facades\Cache::remember('yahoo_auth', 3600, function() {
                $cookieJar = new \GuzzleHttp\Cookie\CookieJar();
                $client = new \GuzzleHttp\Client(['cookies' => $cookieJar, 'verify' => false]);
                
                $client->get('https://fc.yahoo.com', [
                    'headers' => ['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'],
                    'http_errors' => false
                ]);
                
                $crumbRes = $client->get('https://query1.finance.yahoo.com/v1/test/getcrumb', [
                    'headers' => ['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)']
                ]);
                
                // Extract cookie string
                $cookieString = '';
                foreach ($cookieJar->toArray() as $cookie) {
                    $cookieString .= $cookie['Name'] . '=' . $cookie['Value'] . '; ';
                }
                
                return [
                    'crumb' => (string) $crumbRes->getBody(),
                    'cookie' => $cookieString
                ];
            });
            
            $client = new \GuzzleHttp\Client(['verify' => false]);
            $encodedSymbols = urlencode($symbols);
            
            $quoteRes = $client->get("https://query1.finance.yahoo.com/v7/finance/quote?symbols={$encodedSymbols}&crumb={$yahooAuth['crumb']}", [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'Cookie' => $yahooAuth['cookie']
                ]
            ]);
            
            return json_decode((string)$quoteRes->getBody(), true);
        } catch (\Exception $e) {}
        return ['quoteResponse' => ['result' => []]];
    });
})->name('api.stocks');


/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Protected Panel Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Posts Management
    Route::post('posts/{post}/toggle-status', [AdminPostController::class, 'toggleStatus'])->name('posts.toggleStatus');
    Route::post('posts/{post}/toggle-breaking', [AdminPostController::class, 'toggleBreaking'])->name('posts.toggleBreaking');
    Route::resource('posts', AdminPostController::class);

    // Categories Management
    Route::resource('categories', AdminCategoryController::class)->except(['create', 'show', 'edit']);

    // Tags Management
    Route::resource('tags', TagController::class)->except(['create', 'show', 'edit']);

    // Advertisements Management
    Route::post('ads/{ad}/toggle-status', [AdController::class, 'toggleStatus'])->name('ads.toggleStatus');
    Route::resource('ads', AdController::class)->except(['show']);

    // Breaking Tickers
    Route::post('tickers/{ticker}/toggle-status', [\App\Http\Controllers\Admin\BreakingTickerController::class, 'toggleStatus'])->name('tickers.toggleStatus');
    Route::resource('tickers', \App\Http\Controllers\Admin\BreakingTickerController::class)->except(['create', 'show', 'edit', 'update']);

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    // Subscribers
    Route::get('subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::delete('subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');

    // Admin Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});
