<?php

namespace Tests\Cartelo\Route;

use App\Models\User;
use Cartelo\Models\Addon;
use Cartelo\Models\AddonGroup;
use Cartelo\Models\Area;
use Cartelo\Models\Category;
use Cartelo\Models\Day;
use Cartelo\Models\Mobile;
use Cartelo\Models\Product;
use Cartelo\Models\Restaurant;
use Cartelo\Models\Social;
use Cartelo\Models\Variant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RouteAdminTest extends TestCase
{
	// use RefreshDatabase;

	/** @test */
	function seed_database()
	{
		Artisan::call('migrate:fresh');

		$user = User::factory()->create(['role' => 'admin', 'username' => 'user1']);
		// Restaurant
		$this->create_restaurant($user);
		$restaurant = Restaurant::latest()->first();
		// Area
		$this->create_area($user, $restaurant);
		$this->create_area($user, $restaurant, 12.50, 25, 1);
		$areas = Area::all()->pluck('id')->toArray();
		// Mobile
		$this->create_restaurant_mobile($user, $restaurant);
		$this->create_restaurant_mobile($user, $restaurant);
		$mobiles = Mobile::all()->pluck('id')->toArray();
		// Days
		$this->create_restaurant_day($user, $restaurant);
		$days = Day::all()->pluck('id')->toArray();
		// Social
		$this->create_restaurant_social($user, $restaurant);
		$this->create_restaurant_social($user, $restaurant);
		$socials = Social::all()->pluck('id')->toArray();
		// Categories
		$this->create_category($user, 'Pizza');
		$this->create_category($user, 'Kebab');
		$this->create_category($user, 'Pasta');
		$this->create_category($user, 'Promotions');
		$categories = Category::all()->pluck('id')->toArray();
		// Addons
		$this->create_addon($user, 'Szynka', 1.50); // 1
		$this->create_addon($user, 'Szynka', 2.50);
		$this->create_addon($user, 'Szynka', 3.50);
		$this->create_addon($user, 'Ser', 1.55); // 4
		$this->create_addon($user, 'Ser', 2.55);
		$this->create_addon($user, 'Ser', 3.55);
		$this->create_addon($user, 'Ananas', 1.53); // 7
		$this->create_addon($user, 'Ananas', 2.53);
		$this->create_addon($user, 'Ananas', 3.53);
		$this->create_addon($user, 'Kurczak', 1.50); // 10
		$this->create_addon($user, 'Kurczak', 2.50);
		$this->create_addon($user, 'Kurczak', 3.50);
		$this->create_addon($user, 'Baranina', 1.50); // 13
		$this->create_addon($user, 'Baranina', 2.50);
		$this->create_addon($user, 'Baranina', 3.50);
		$this->create_addon($user, 'Sos 1', 0.00); // 16
		$this->create_addon($user, 'Sos 2', 0.00);
		$this->create_addon($user, 'Sos 3', 0.00);
		$addons = Addon::all()->pluck('id')->toArray();
		// Addon Groups
		$this->create_addongroup($user, 'Addons', 'S'); // Multiple addons
		$this->create_addongroup($user, 'Addons', 'M');
		$this->create_addongroup($user, 'Addons', 'L');
		$this->create_addongroup($user, 'Meat', 'S', 0, 1); // Single addon
		$this->create_addongroup($user, 'Meat', 'M', 0, 1);
		$this->create_addongroup($user, 'Meat', 'L', 0, 1);
		$this->create_addongroup($user, 'Sauce', 'S', 0, 1); // Single
		$this->create_addongroup($user, 'Sauce', 'M', 0, 1);
		$this->create_addongroup($user, 'Sauce', 'L', 0, 1);
		$addongroups = AddonGroup::all()->pluck('id')->toArray();
		// Product
		$this->create_product($user, 'Pizza Vege', [1, 4]);
		$this->create_product($user, 'Kebab Chicken', [2, 4]);
		$this->create_product($user, 'Spaghetti Bolognese', [3]);
		$products = Product::all()->pluck('id')->toArray();
		// Variants Pizza
		$this->create_variant($user, 1, 'Small', 11.35);
		$this->create_variant($user, 1, 'Medium', 21.35);
		$this->create_variant($user, 1, 'Big', 31.35, 30.35, 1);
		// Variants Kebab
		$this->create_variant($user, 2, 'Small', 12.44);
		$this->create_variant($user, 2, 'Big', 32.44, 30.44, 1);
		// Variants Pasta
		$this->create_variant($user, 3, 'Small', 13.33);
		$this->create_variant($user, 3, 'Medium', 23.33);
		$variants = Variant::all()->pluck('id')->toArray();
		// Variants AddonGroups Pizza
		$this->create_variant_add_addongroup($user, 1, 7); // Small
		$this->create_variant_add_addongroup($user, 2, 8); // Medium
		$this->create_variant_add_addongroup($user, 3, 9); // Big
		// Variants AddonGroups Kebab
		$this->create_variant_add_addongroup($user, 4, 1); // Small
		$this->create_variant_add_addongroup($user, 4, 3);
		$this->create_variant_add_addongroup($user, 4, 7);
		$this->create_variant_add_addongroup($user, 5, 3); // Big
		$this->create_variant_add_addongroup($user, 5, 6);
		$this->create_variant_add_addongroup($user, 5, 9);
		// AddomGroups Addons
		$this->create_addongroup_add_addon($user, 1, 1); // S
		$this->create_addongroup_add_addon($user, 1, 4);
		$this->create_addongroup_add_addon($user, 1, 7);
		$this->create_addongroup_add_addon($user, 2, 2); // M
		$this->create_addongroup_add_addon($user, 2, 5);
		$this->create_addongroup_add_addon($user, 2, 8);
		$this->create_addongroup_add_addon($user, 3, 3); // L
		$this->create_addongroup_add_addon($user, 3, 6);
		$this->create_addongroup_add_addon($user, 3, 9);
		// AddomGroups Addons Meat
		$this->create_addongroup_add_addon($user, 4, 10); // S
		$this->create_addongroup_add_addon($user, 4, 13);
		$this->create_addongroup_add_addon($user, 5, 11); // M
		$this->create_addongroup_add_addon($user, 5, 14);
		$this->create_addongroup_add_addon($user, 6, 12); // L
		$this->create_addongroup_add_addon($user, 6, 15);
		// AddomGroups Addons Sauce
		$this->create_addongroup_add_addon($user, 7, 16); // S
		$this->create_addongroup_add_addon($user, 7, 17);
		$this->create_addongroup_add_addon($user, 8, 16); // M
		$this->create_addongroup_add_addon($user, 8, 17);
		$this->create_addongroup_add_addon($user, 9, 16); // L
		$this->create_addongroup_add_addon($user, 9, 17);
		// Category Products
		$this->create_category_add_product($user, 4, 2);
		$this->create_category_add_product($user, 4, 3);

		// print_r("\n  USER-ID: " . $user->id);
		// print_r("\n  RESTAURANT-ID: " . $restaurant->id);
		// print_r("\n  AREAS-ID: " . join(', ', $areas));
		// print_r("\n  MOBILES-ID: " . join(', ', $mobiles));
		// print_r("\n  SOCIALS-ID: " . join(', ', $socials));
		// print_r("\n  DAYS-ID: " . join(', ', $days));
		// print_r("\n  CATEGORIES-ID: " . join(', ', $categories));
		// print_r("\n  ADDONS-ID: " . join(', ', $addons));
		// print_r("\n  ADDONGROUPS-ID: " . join(', ', $addongroups));
		// print_r("\n  PRODUCTS-ID: " . join(', ', $products));
		// print_r("\n  VARIANTS-ID: " . join(', ', $variants));
	}

	function create_restaurant($user)
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.restaurants.store'));
		$data = Restaurant::factory()->make()->toArray();
		$res = $this->postJson(route('cartelo.restaurants.store'), $data);
		$res->assertStatus(200)->assertJson([
			'message' => 'The restaurant has been created'
		]);
	}

	function create_area($user, $restaurant, $cost = 9.99, $min_order_cost = 19.99, $on_free_from = 0)
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.areas.store'));
		$data = Area::factory()->make([
			'restaurant_id' => $restaurant->id,
			'name' => 'Area ' . uniqid(),
			'about' => 'Delivery area description.',
			'cost' => $cost,
			'min_order_cost' => $min_order_cost,
			'on_free_from' => $on_free_from,
			'free_from' => 100.00,
		])->toArray();
		$data['polygon'] = Area::geoJsonPolygonSample();
		$res = $this->postJson(route('cartelo.areas.store'), $data);
		$res->assertStatus(200)->assertJson([
			'message' => 'The area has been created'
		]);
	}

	function create_category($user, $name = 'Pizza')
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.categories.store'));
		$data = Category::factory()->make([
			'name' => $name,
			'slug' => Str::slug($name),
		])->toArray();
		$res = $this->postJson(route('cartelo.categories.store'), $data);
		$res->assertStatus(200)->assertJson([
			'message' => 'The category has been created'
		]);
	}

	function create_addon($user, $name = 'Szynka', $price = 1.50)
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.addons.store'));
		$data = Addon::factory()->make([
			'name' => $name,
			'price' => $price
		])->toArray();
		$res = $this->postJson(route('cartelo.addons.store'), $data);
		$res->assertStatus(200)->assertJson([
			'message' => 'The addon has been created'
		]);
	}

	function create_addongroup($user, $name = 'Meat', $size = 'S', $multiple = 1, $required = 0)
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.addongroups.store'));
		$data = Addon::factory()->make([
			'name' => $name,
			'size' => $size,
			'multiple' => $multiple,
			'required' => $required,
			'about' => 'Addons group'
		])->toArray();
		$res = $this->postJson(route('cartelo.addongroups.store'), $data);
		$res->assertStatus(200)->assertJson([
			'message' => 'The addongroup has been created'
		]);
	}

	function create_product($user, $name = 'Product', $categories = [])
	{
		Storage::fake('public');
		$image = UploadedFile::fake()->image(Str::slug($name) . '_product.png', 256, 256);

		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.products.store'));
		$data = Product::factory()->make([
			'image' => $image,
			'name' => $name,
			'slug' => Str::slug($name),
			'about' => ucfirst($name) . ' product description.',
		])->toArray();
		$res = $this->postJson(route('cartelo.products.store'), $data);
		$res->assertStatus(200)->assertJson([
			'message' => 'The product has been created'
		])->assertJsonStructure(['message', 'product']);

		// Upload
		$path = $res['product']['image'];
		Storage::disk('public')->assertExists($path);
		$this->assertDatabaseHas('products', ['image' => $path]);

		// Categories
		$product = Product::where('slug', Str::slug($name))->first();
		$product->categories()->sync($categories);
	}

	function create_variant($user, $product_id = 1, $size = 'Small', $price = 11.50, $price_sale = 10.50, $on_sale = 0)
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.variants.store'));
		$data = Variant::factory()->make([
			'product_id' => $product_id,
			'size' => $size,
			'price' => $price,
			'price_sale' => $price_sale,
			'on_sale' => $on_sale,
			'packaging' => 1.25,
			'cashback' => 0.33
		])->toArray();
		$res = $this->postJson(route('cartelo.variants.store'), $data);
		$res->assertStatus(200)->assertJson([
			'message' => 'The variant has been created'
		]);
	}

	function create_variant_add_addongroup($user, $vid = 1, $gid = 1)
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.variants.attach.addongroup'));
		$res = $this->getJson('cartelo/addongroups/' . $gid . '/attach/variant/' . $vid);
		$res->assertStatus(200)->assertJson([
			'message' => 'The variant group has been created'
		]);
	}

	function create_addongroup_add_addon($user, $gid = 1, $aid = 1)
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.addongroups.attach.addon'));
		$res = $this->getJson('cartelo/addongroups/' . $gid . '/attach/addon/' . $aid);
		$res->assertStatus(200)->assertJson([
			'message' => 'The addongroup addon has been created'
		]);
	}

	function create_category_add_product($user, $cid = 1, $pid = 1)
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.categories.attach.product'));
		$res = $this->getJson('cartelo/categories/' . $cid . '/attach/product/' . $pid);
		$res->assertStatus(200)->assertJson([
			'message' => 'The category product has been created'
		]);
	}

	function create_restaurant_mobile($user, $restaurant)
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.mobiles.store'));
		$data = Mobile::factory()->make([
			'restaurant_id' => $restaurant->id,
			'name' => 'User ' . uniqid(),
		])->toArray();
		$res = $this->postJson(route('cartelo.mobiles.store'), $data);
		$res->assertStatus(200)->assertJson([
			'message' => 'The mobile has been created'
		]);
	}

	function create_restaurant_social($user, $restaurant)
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.socials.store'));
		$data = Social::factory()->make([
			'restaurant_id' => $restaurant->id,
			'name' => 'Social ' . uniqid(),
		])->toArray();
		$res = $this->postJson(route('cartelo.socials.store'), $data);
		$res->assertStatus(200)->assertJson([
			'message' => 'The social link has been created'
		]);
	}

	function create_restaurant_day($user, $restaurant)
	{
		$this->actingAs($user);
		$this->assertTrue(Route::has('cartelo.days.store'));
		for ($i = 1; $i <= 7; $i++) {
			$data = Day::factory()->make([
				'restaurant_id' => $restaurant->id,
				'number' => $i,
			])->toArray();
			if ($i > 5) {
				$data = Day::factory()->make([
					'restaurant_id' => $restaurant->id,
					'number' => $i,
					'closed' => 1
				])->toArray();
			}
			$res = $this->postJson(route('cartelo.days.store'), $data);
			$res->assertStatus(200)->assertJson([
				'message' => 'The day has been created'
			]);
		}
	}
}
