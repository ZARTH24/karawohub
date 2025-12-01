<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\VendorProductController;
use App\Http\Controllers\Api\ShipmentController;
use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ShippingController;

// Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/vendors', [VendorController::class, 'index']);
Route::get('/vendors/{id}', [VendorController::class, 'show']);

// Payment webhook (public)
Route::post('/payments/notify', [PaymentController::class, 'notify']);

// Protected
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/test-vendor', function () {
        $user = Auth::user(); // <-- ganti di sini
        return [
            'user_id' => $user->id ?? null,
            'role' => $user->role ?? null,
            'can_isVendor' => $user ? $user->can('isVendor') : null,
        ];
    });


    Route::get('/test-courier', function () {
        $user = Auth::user(); // <-- ganti di sini
        return [
            'user_id' => $user->id ?? null,
            'role' => $user->role ?? null,
            'can_isCourier' => $user ? $user->can('isCourier') : null,
        ];
    });



    Route::apiResource('payments', PaymentController::class)
        ->only(['index', 'show', 'store']);

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Cart
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartController::class, 'store']);       // add item
    Route::put('/cart/items/{id}', [CartController::class, 'update']); // update qty
    Route::delete('/cart/items/{id}', [CartController::class, 'destroy']); // remove item

    // Checkout
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
    
    // Maps ongkir
    Route::post('/ongkir', [ShipmentController::class, 'calculate']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    
    

    // Membership
    Route::get('/memberships', [MembershipController::class, 'index']); // list packages
    Route::post('/memberships/subscribe', [MembershipController::class, 'subscribe']);
    Route::get('/memberships/me', [MembershipController::class, 'me']); // check active membership

    // register vendor (user biasa boleh akses)
    Route::post('/vendor/register', [VendorController::class, 'register']);

    // Vendor endpoints (for users who are vendor owners)
    Route::middleware('can:isVendor')->group(function () {
        Route::get('/vendor/me', [VendorController::class, 'me']);
        Route::apiResource('/vendor/products', VendorProductController::class);
        Route::get('/vendor/orders', [VendorController::class, 'orders']);
        Route::post('/vendor/orders/{order}/mark_ready', [VendorController::class, 'markReady']);
        Route::get('/vendor/couriers', [VendorController::class, 'couriers']);
    });

    // Courier actions (if users have courier role)
    Route::middleware('can:isCourier')->group(function () {
        Route::get('/courier/assignments', [ShipmentController::class, 'assignments']);
        Route::post('/courier/shipments/{id}/update_status', [ShipmentController::class, 'updateStatus']);
    });



    // Admin area
    Route::middleware('can:isAdmin')->group(function () {
        Route::get('/admin/vendors/pending', [AdminController::class, 'pendingVendors']);
        Route::post('/admin/vendors/{id}/validate', [AdminController::class, 'validateVendor']);
        Route::get('/admin/products/pending', [AdminController::class, 'pendingProducts']);
        Route::post('/admin/products/{id}/validate', [AdminController::class, 'validateProduct']);
        Route::get('/admin/orders', [AdminController::class, 'orders']);
    });
});
