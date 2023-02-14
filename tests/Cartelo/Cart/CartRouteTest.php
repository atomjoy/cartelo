<?php

namespace Tests\Cartelo\Cart;

use App\Models\User;
use Cartelo\Models\Addon;
use Cartelo\Models\Area;
use Cartelo\Models\Cart;
use Cartelo\Models\CartVariant;
use Cartelo\Models\CartVariantAddon;
use Cartelo\Models\Coupon;
use Cartelo\Models\Variant;
use Cartelo\Models\Product;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartRouteTest extends TestCase
{
	/** @test */
	function create_cart_products()
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
	}

	/** @test */
	function create_cart()
	{
		$user = User::factory()->create(['role' => 'user', 'username' => 'testing1']);
		$this->actingAs($user);

		$this->assertTrue(Route::has('cartelo.cart.create'));
		$res = $this->getJson(route('cartelo.cart.create'));
		$res->assertStatus(200)->assertJsonStructure(['cart' => ['cart_id']]);
		$cart_id = $res['cart']['cart_id'];
		$this->assertTrue(!empty($cart_id));
	}

	/** @test */
	function create_cart_with_area1()
	{
		$area = Area::find(1);

		$user = User::factory()->create(['role' => 'user', 'username' => 'testing2']);
		$this->actingAs($user);

		$this->assertTrue(Route::has('cartelo.cart.create.area'));
		$res = $this->getJson('cartelo/carts/create/' . $area->id);
		$res->assertStatus(200)->assertJsonStructure(['cart' => ['cart_id']]);
		$cart_id = $res['cart']['cart_id'];
		$this->assertTrue(!empty($cart_id));
	}

	/** @test */
	function create_cart_with_area2()
	{
		$area = Area::find(2);
		$user = User::factory()->create(['role' => 'user', 'username' => 'testing3']);
		$this->actingAs($user);

		$this->assertTrue(Route::has('cartelo.cart.create.area'));
		$res = $this->getJson('cartelo/carts/create/' . $area->id);
		$res->assertStatus(200)->assertJsonStructure(['cart' => ['cart_id']]);
		$cart_id = $res['cart']['cart_id'];
		$this->assertTrue(!empty($cart_id));
	}

	/** @test */
	function create_cart_show()
	{
		$cart = Cart::first();

		$this->assertTrue(Route::has('cartelo.cart.show'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/show');
		$res->assertStatus(200)->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost']);
	}

	/** @test */
	function create_cart_show_delivery()
	{
		$cart = Cart::orderBy('created_at', 'desc')->first();

		$this->assertTrue(Route::has('cartelo.cart.show.delivery'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/show/pickup');
		$res->assertStatus(200)->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost']);
	}

	/** @test */
	function cart_update_area()
	{
		$cart = Cart::first();
		$area = Area::find(1);

		$this->assertTrue(Route::has('cartelo.cart.area.update'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/area/' . $area->id);
		$res->assertStatus(200)
			->assertJson(['delivery_cost' => $area->cost])
			->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost']);
	}

	/** @test */
	function cart_update_area_change()
	{
		$cart = Cart::first();
		$area = Area::find(2);

		print_r("\n Area cost: " . $area->cost);

		$this->assertTrue(Route::has('cartelo.cart.area.update'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/area/' . $area->id);
		$res->assertStatus(200)
			->assertJson(['delivery_cost' => $area->cost])
			->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost']);
	}

	/** @test */
	function cart_update_coupon()
	{
		$cart = Cart::first();
		$coupon = Coupon::factory()->create([
			'code' => 'PROMO',
			'type' => 'percent'
		]);

		// Add coupon
		$this->assertTrue(Route::has('cartelo.cart.coupon.update'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/coupon/' . $coupon->code);
		$res->assertStatus(200)->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost']);

		$cart_up = Cart::first();
		$this->assertTrue($cart_up->coupon_id == $coupon->id);
	}

	/** @test */
	function cart_delete_coupon()
	{
		$cart = Cart::first();
		// Delete coupon
		$this->assertTrue(Route::has('cartelo.cart.coupon.delete'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/coupon/delete');
		$res->assertStatus(200)->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost']);

		$cart_up = Cart::first();
		$this->assertTrue($cart_up->coupon_id == null);
	}

	/** @test */
	function cart_add_variants()
	{
		$cart = Cart::first();
		$v = Variant::find(1);
		$qty = 1;

		print_r("\n Area: " . $cart->area->cost);
		print_r("\n Variant: " . $v->price . "/" . $v->price_sale . "/" . $v->on_sale . "/" . $v->packaging);

		// Clear
		CartVariantAddon::query()->forceDelete();
		CartVariant::query()->forceDelete();

		// Variant
		$this->assertTrue(Route::has('cartelo.cart.add.product'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/product/' . $v->id . '/' . $qty);
		$res->assertStatus(200)
			->assertJson(['total_cost' => 21.49])
			->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost', 'packaging_cost']);

		// Addons
		$a = Addon::find(1);
		$aqty = 2;
		$this->assertTrue(Route::has('cartelo.cart.add.addon'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/product/1/addon/' . $a->id . '/' . $aqty);
		$res->assertStatus(200)
			->assertJson(['total_cost' => 26.49])
			->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost', 'packaging_cost']);

		// Delivery change
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/show/restaurant');
		$res->assertStatus(200)->assertJsonStructure(['cart']);

		// Addons
		$a = Addon::find(2);
		$aqty = 1;
		$this->assertTrue(Route::has('cartelo.cart.add.addon'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/product/1/addon/' . $a->id . '/' . $aqty);
		$res->assertStatus(200)
			->assertJson(['total_cost' => 27.99])
			->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost', 'packaging_cost']);

		// Plus addon
		$this->assertTrue(Route::has('cartelo.cart.addon.plus'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/plus/' . $v->id . '/addon/' . $a->id);
		$res->assertStatus(200)
			->assertJson(['total_cost' => 29.49])
			->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost', 'packaging_cost']);

		// Minus addon
		$this->assertTrue(Route::has('cartelo.cart.addon.minus'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/minus/' . $v->id . '/addon/' . $a->id);
		$res->assertStatus(200)
			->assertJson(['total_cost' => 27.99])
			->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost', 'packaging_cost']);

		// Remove addon
		$this->assertTrue(Route::has('cartelo.cart.addon.remove'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/remove/' . $v->id . '/addon/' . $a->id);
		$res->assertStatus(200)
			->assertJson(['total_cost' => 26.49])
			->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost', 'packaging_cost']);

		// Plus product
		$this->assertTrue(Route::has('cartelo.cart.product.plus'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/plus/' . $v->id);
		$res->assertStatus(200)
			->assertJson(['total_cost' => 42.99])
			->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost', 'packaging_cost']);

		// Minus product
		$this->assertTrue(Route::has('cartelo.cart.product.minus'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/minus/' . $v->id);
		$res->assertStatus(200)
			->assertJson(['total_cost' => 26.49])
			->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost', 'packaging_cost']);

		// Remove product
		$this->assertTrue(Route::has('cartelo.cart.product.remove'));
		$res = $this->getJson('cartelo/carts/' . $cart->id . '/remove/' . $v->id);
		$res->assertStatus(200)
			->assertJson(['total_cost' => 0.00])
			->assertJsonStructure(['cart', 'products_cost', 'total_cost', 'delivery_cost', 'packaging_cost']);
	}
}
