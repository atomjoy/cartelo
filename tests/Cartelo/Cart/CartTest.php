<?php

namespace Tests\Cartelo\Cart;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Cartelo\Models\Addon;
use Cartelo\Models\Area;
use Cartelo\Models\Cart;
use Cartelo\Models\Coupon;
use Cartelo\Models\Product;
use Cartelo\Models\Variant;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CartTest extends TestCase
{

	function test_cart()
	{
		Artisan::call('migrate:fresh');

		$area = Area::factory()->create([
			'restaurant_id' => null,
			'min_order_cost' => 100,
			'cost' => 9.99,
			'free_from' => 200,
			'on_free_from' => 0
		]);

		$area2 = Area::factory()->create([
			'restaurant_id' => null,
			'min_order_cost' => 20,
			'cost' => 9.99,
			'free_from' => 50,
			'on_free_from' => 1
		]);

		$coupon = Coupon::factory()->create([
			'code' => 'PROMO',
			'type' => 'amount',
			'discount' => 3.49,
			'max_order_percent' => 50
		]);

		$coupon2 = Coupon::factory()->create([
			'code' => 'PROMOXXX',
			'type' => 'percent',
			'discount' => 30,
			'max_order_percent' => 50
		]);

		$a1 = Addon::factory()->create([
			'name' => 'Szynka',
			'price' => 2.50
		]);
		$a2 = Addon::factory()->create([
			'name' => 'Ser',
			'price' => 1.50
		]);
		$a3 = Addon::factory()->create([
			'name' => 'Sos',
			'price' => 1.00
		]);

		$p1 = Product::factory()->create([
			'name' => 'Pizza',
			'slug' => 'pizza',
			'visible' => 1,
			'on_stock' => 1,
			'sorting' => 0,
		]);
		$p2 = Product::factory()->create([
			'name' => 'Kebab',
			'slug' => 'kebab',
			'visible' => 1,
			'on_stock' => 1,
			'sorting' => 0,
		]);

		$v1 = Variant::factory()->create([
			'product_id' => $p1->id,
			'size' => 'Small',
			'price' => 10.00,
			'price_sale' => 9.00,
			'packaging' => 1.50,
			'on_sale' => 0,
			'cashback' => 0.50
		]);
		$v2 = Variant::factory()->create([
			'product_id' => $p1->id,
			'size' => 'Medium',
			'price' => 15.00,
			'price_sale' => 14.00,
			'packaging' => 2.55,
			'on_sale' => 1,
			'cashback' => 0.50
		]);

		$v3 = Variant::factory()->create([
			'product_id' => $p2->id,
			'size' => 'Small',
			'price' => 11.00,
			'price_sale' => 10.00,
			'packaging' => 1.00,
			'on_sale' => 0,
			'cashback' => 0.50
		]);
		$v4 = Variant::factory()->create([
			'product_id' => $p2->id,
			'size' => 'Medium',
			'price' => 16.00,
			'price_sale' => 15.00,
			'packaging' => 2.55,
			'on_sale' => 1,
			'cashback' => 0.50
		]);

		$this->assertTrue(true);

		// Create Cart
		$cart = Cart::create(['id' => uniqid(), 'area_id' => NULL, 'ip' => '127.0.0.1', 'active' => 1]);
		$this->assertTrue($cart->active == 1);
		$this->assertTrue($cart->area_id == null);
		$this->assertTrue($cart->delivery_method == 'home');

		$res = $cart->drawCart();
		//echo "\n Cost " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 0);

		// Add products
		$cart->addProduct($v1->id, 3);
		$cart->addAddon(1, 1, 1);
		$cart->addAddon(1, 2, 2);
		$cart->addAddon(1, 3, 3);

		$cart->addProduct($v3->id, 1);
		$cart->addAddon(2, 2, 1);
		$cart->addAddon(2, 3, 2);

		$res = $cart->drawCart();
		//echo "\n Cost " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 75.50);

		$cart->addDeliveryArea($area);

		$res = $cart->drawCart();
		//echo "\n Cost " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 85.49);

		$cart->delivery_method = 'pickup';

		$res = $cart->drawCart();
		//echo "\n Cost " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 75.50);

		$cart->delivery_method = 'restaurant';

		$res = $cart->drawCart();
		//echo "\n Cost " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 70);

		$cart->addDeliveryArea($area2);
		$cart->delivery_method = 'home';

		$res = $cart->drawCart();
		//echo "\n Cost " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 75.50);

		// Copupon
		$cart->addCoupon($coupon->code);

		$res = $cart->drawCart();
		//echo "\n Cost " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 72.01);
		$this->assertTrue($res['cashback_reward'] == 2.00);

		$cart->delProduct($v2->id);

		$res = $cart->drawCart();
		//echo "\n Cost " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 56.51);
		$this->assertTrue($res['cashback_reward'] == 1.50);

		// Copupon 2 - 30%
		$cart->addCoupon($coupon2->code);

		$res = $cart->drawCart();
		//echo "\n Cost coupon " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 43.35);
		$this->assertTrue($res['cashback_reward'] == 1.50);
		$this->assertTrue($res['discount_cost'] == 16.65);

		// Delete coupon
		$cart->delCoupon();

		$res = $cart->drawCart();
		//echo "\n Cost coupon " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 60);
		$this->assertTrue($res['cashback_reward'] == 1.50);
		$this->assertTrue($res['discount_cost'] == 0);

		// Price sale
		$cart_variant = $cart->addProduct($v4->id, 1);
		$cart_variant_addon = $cart->addAddon($cart_variant->id, 3, 2);

		$res = $cart->drawCart();
		//echo "\n Cost sale " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 79.55);
		$this->assertTrue($res['cashback_reward'] == 2.00);
		$this->assertTrue($res['discount_cost'] == 0);

		// Count
		$this->assertTrue($cart->count() == 2);

		// Plus addon
		$cart->plusAddon($cart_variant->id, $cart_variant_addon->id);

		$res = $cart->drawCart();
		//echo "\n Cost sale " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 80.55);
		$this->assertTrue($res['cashback_reward'] == 2.00);
		$this->assertTrue($res['discount_cost'] == 0);

		// Minus addon
		$cart->minusAddon($cart_variant->id, $cart_variant_addon->id);

		$res = $cart->drawCart();
		//echo "\n Cost sale " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 79.55);
		$this->assertTrue($res['cashback_reward'] == 2.00);
		$this->assertTrue($res['discount_cost'] == 0);

		// Plus product
		$cart->plusProduct($cart_variant->id);

		$res = $cart->drawCart();
		//echo "\n Cost " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 99.10);
		$this->assertTrue($res['cashback_reward'] == 2.50);
		$this->assertTrue($res['discount_cost'] == 0);

		// Minus product
		$cart->minusProduct($cart_variant->id);

		$res = $cart->drawCart();
		//echo "\n Cost " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 79.55);
		$this->assertTrue($res['cashback_reward'] == 2.00);
		$this->assertTrue($res['discount_cost'] == 0);

		// Remove addon
		$cart->delAddon($cart_variant->id, $cart_variant_addon->id);

		$res = $cart->drawCart();
		//echo "\n Cost sale " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 77.55);
		$this->assertTrue($res['cashback_reward'] == 2.00);
		$this->assertTrue($res['discount_cost'] == 0);

		// Remove product
		$cart->delProduct($cart_variant->id);

		$res = $cart->drawCart();
		//echo "\n Cost " . $res['total_cost'];
		$this->assertTrue($res['total_cost'] == 60);
		$this->assertTrue($res['cashback_reward'] == 1.50);
		$this->assertTrue($res['discount_cost'] == 0);

		// Count
		$this->assertTrue($cart->count() == 1);

		// Show class
		// print_r("\n");
		// print_r($res);
	}
}
