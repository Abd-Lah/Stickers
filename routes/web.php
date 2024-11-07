<?php

    use App\Http\Controllers\Admin\DashboardController;
    use App\Http\Controllers\Admin\ManageCategory;
    use App\Http\Controllers\Admin\ManageOrder;
    use App\Http\Controllers\Admin\ManageSticker;
    use App\Http\Controllers\Auth\AdminLoginController;
    use App\Http\Controllers\CategoryProductController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;


    // web.php

    // web.php
    Route::get('/', [CategoryProductController::class, 'index'])->name('index');
    Route::post('/update-cart-session', function (Request $request) {
        $cart = $request->input('cart');
        if (!empty($cart) && count($cart) > 0) {
            session(['cart' => true]); // Set session variable
            return response()->json(['success' => true, 'message' => 'Cart updated successfully.']);
        }
        session()->forget('cart');
        return response()->json(['success' => false, 'message' => 'Cart is empty.'], 400);
    });

    Route::get('/cart', [CategoryProductController::class, 'cart'])->name('cart');
    Route::post('/order', [CategoryProductController::class, 'order'])->name('order')->middleware(['throttle:100,60', 'pending-order']);;
    Route::get('/api/products', [CategoryProductController::class, 'filter'])->name('api.products');

    Route::prefix('admin')->group(function () {
        // Public login route
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');

        // Auth admin routes - requires 'is-admin' middleware
        Route::middleware('is-admin')->group(function () {
            //logout
            Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
            // Dashboard routes
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/dashboard/load', [DashboardController::class, 'load'])->name('load-data-dashboard');

            // Category routes
            Route::get('/category-list', [ManageCategory::class, 'index'])->name('category-list');
            Route::get('/category-create', [ManageCategory::class, 'create'])->name('new-category');
            Route::post('/category-store', [ManageCategory::class, 'store'])->name('store-category');
            Route::get('/category-show/{id}', [ManageCategory::class, 'show'])->name('show-category');
            Route::put('/category-update/{category}', [ManageCategory::class, 'update'])->name('update-category');
            Route::delete('/category-delete/{id}', [ManageCategory::class, 'destroy'])->name('delete-category');

            // Product routes
            Route::get('/product-list', [ManageSticker::class, 'index'])->name('product-list');
            Route::get('/product-create', [ManageSticker::class, 'create'])->name('new-product');
            Route::post('/product-store', [ManageSticker::class, 'store'])->name('store-product');
            Route::get('/product-show/{id}', [ManageSticker::class, 'show'])->name('show-product');
            Route::post('/product-update/{id}', [ManageSticker::class, 'update'])->name('update-product');
            Route::delete('/product-delete/{id}', [ManageSticker::class, 'destroy'])->name('delete-product');

            // Orders routes
            Route::get('/orders', [ManageOrder::class, 'index'])->name('index-orders'); // return the blade file
            Route::get('/orders/load', [ManageOrder::class, 'load_orders'])->name('load-orders'); // orders filter json response
            Route::get('/orders/{id}', [ManageOrder::class, 'show'])->name('show-order');
            Route::patch('/orders/payment/{id}', [ManageOrder::class, 'update'])->name('payment-order');
            Route::post('/orders/confirm/{id}', [ManageOrder::class, 'confirm'])->name('confirm-order'); // confirm order json response
            Route::delete('/orders/delete/{id}', [ManageOrder::class, 'delete'])->name('delete-order'); // confirm order json response

        });
    });

