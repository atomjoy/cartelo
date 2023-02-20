<?php

use Cartelo\Http\Controllers\Cart\CartController;
use Cartelo\Http\Controllers\AreaController;
use Cartelo\Http\Controllers\AddonController;
use Cartelo\Http\Controllers\AddonGroupController;
use Cartelo\Http\Controllers\CategoryController;
use Cartelo\Http\Controllers\DayController;
use Cartelo\Http\Controllers\MobileController;
use Cartelo\Http\Controllers\SocialController;
use Cartelo\Http\Controllers\RestaurantController;
use Cartelo\Http\Controllers\ProductController;
use Cartelo\Http\Controllers\CouponController;
use Cartelo\Http\Controllers\UserController;
use Cartelo\Http\Controllers\VariantController;
use Cartelo\Http\Controllers\TranslateController;
use Illuminate\Support\Facades\Route;
use Webi\Exceptions\WebiException;

Route::prefix('cartelo')->name('cartelo.')->middleware(['web', 'webi-locale', 'webi-json'])->group(function () {
	// Public cart routes
	Route::get('carts/create', [CartController::class, 'create'])->name('cart.create');
	Route::get('carts/create/{area}', [CartController::class, 'createWithArea'])->name('cart.create.area');
	// Cart content
	Route::get('carts/{cart}/show', [CartController::class, 'show'])->name('cart.show');
	Route::get('carts/{cart}/show/{delivery_method}', [CartController::class, 'show'])->name('cart.show.delivery');
	// Cart update area, coupon
	Route::get('carts/{cart}/area/{area}', [CartController::class, 'updateArea'])->name('cart.area.update');
	Route::get('carts/{cart}/coupon/{coupon}', [CartController::class, 'updateCoupon'])->name('cart.coupon.update');
	Route::get('carts/{cart}/delete/coupon', [CartController::class, 'deleteCoupon'])->name('cart.coupon.delete');
	// Cart product, product addon
	Route::get('carts/{cart}/product/{variant}/{qty}', [CartController::class, 'addProduct'])->name('cart.add.product');
	Route::get('carts/{cart}/product/{variant}/addon/{addon}/{qty}', [CartController::class, 'addAddon'])->name('cart.add.addon');
	// Cart product modifications
	Route::get('carts/{cart}/plus/{vid}', [CartController::class, 'plusProduct'])->name('cart.product.plus');
	Route::get('carts/{cart}/minus/{vid}', [CartController::class, 'minusProduct'])->name('cart.product.minus');
	Route::get('carts/{cart}/remove/{vid}', [CartController::class, 'delProduct'])->name('cart.product.remove');
	// Cart product addons modifications
	Route::get('carts/{cart}/plus/{vid}/addon/{aid}', [CartController::class, 'plusAddon'])->name('cart.addon.plus');
	Route::get('carts/{cart}/minus/{vid}/addon/{aid}', [CartController::class, 'minusAddon'])->name('cart.addon.minus');
	Route::get('carts/{cart}/remove/{vid}/addon/{aid}', [CartController::class, 'delAddon'])->name('cart.addon.remove');

	// Public
	Route::resource('restaurants', RestaurantController::class)->only(['index', 'show']);
	Route::resource('areas', AreaController::class)->only(['index', 'show']);
	Route::resource('days', DayController::class)->only(['index', 'show']);
	Route::resource('socials', SocialController::class)->only(['index', 'show']);
	Route::resource('categories', CategoryController::class)->only(['index', 'show']);
	Route::resource('products', ProductController::class)->only(['index', 'show']);
	Route::resource('variants', VariantController::class)->only(['index', 'show']);
	Route::resource('addons', AddonController::class)->only(['index', 'show']);
	Route::resource('addongroups', AddonGroupController::class)->only(['index', 'show']);
	Route::resource('translates', TranslateController::class)->only(['index', 'show']);

	// Privates
	Route::middleware(['auth', 'webi-role:admin|worker', 'webi-json'])->group(function () {
		Route::resource('users', UserController::class)->except(['create', 'edit', 'store']);
		Route::resource('mobiles', MobileController::class)->except(['create', 'edit']);
		Route::resource('coupons', CouponController::class)->except(['create', 'edit']);
		Route::resource('days', DayController::class)->except(['create', 'edit', 'index', 'show']);
		Route::resource('socials', SocialController::class)->except(['create', 'edit', 'index', 'show']);
		Route::resource('restaurants', RestaurantController::class)->except(['create', 'edit', 'index', 'show']);
		Route::resource('addons', AddonController::class)->except(['create', 'edit', 'index', 'show']);
		Route::resource('addongroups', AddonGroupController::class)->except(['create', 'edit', 'index', 'show']);
		Route::resource('areas', AreaController::class)->except(['create', 'edit', 'index', 'show']);
		Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'index', 'show']);
		Route::resource('products', ProductController::class)->except(['create', 'edit', 'index', 'show']);
		Route::resource('variants', VariantController::class)->except(['create', 'edit', 'index', 'show']);
		Route::resource('translates', TranslateController::class)->except(['create', 'edit', 'index', 'show']);

		// Addon group pivots
		Route::get('categories/{category}/attach/product/{product}', [CategoryController::class, 'attachProduct'])->name('categories.attach.product');
		Route::get('categories/{category}/detach/product/{product}', [CategoryController::class, 'detachProduct'])->name('categories.detach.product');
		Route::get('addongroups/{addongroup}/attach/addon/{addon}', [AddonGroupController::class, 'attachAddon'])->name('addongroups.attach.addon');
		Route::get('addongroups/{addongroup}/detach/addon/{addon}', [AddonGroupController::class, 'detachAddon'])->name('addongroups.detach.addon');
		Route::get('addongroups/{addongroup}/attach/variant/{variant}', [AddonGroupController::class, 'attachVariant'])->name('variants.attach.addongroup');
		Route::get('addongroups/{addongroup}/detach/variant/{variant}', [AddonGroupController::class, 'detachVariant'])->name('variants.detach.addongroup');;
	});

	// Fallback
	Route::fallback(function () {
		throw new WebiException('Invalid api route path or request method.', 400);
	})->middleware('webi-json');
});
