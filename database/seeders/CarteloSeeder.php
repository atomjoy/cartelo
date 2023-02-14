<?php

namespace Database\Seeders;

use App\Models\User;
use Cartelo\Models\Addon;
use Cartelo\Models\AddonGroup;
use Cartelo\Models\Area;
use Cartelo\Models\Category;
use Cartelo\Models\Coupon;
use Cartelo\Models\Day;
use Cartelo\Models\Mobile;
use Cartelo\Models\Order;
use Cartelo\Models\Product;
use Cartelo\Models\Social;
use Cartelo\Models\Restaurant;
use Cartelo\Models\Variant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarteloSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// User
		User::factory()->count(2)->create();

		// Restaurant
		$list = Restaurant::factory(1)->create();

		// Coupons
		Coupon::factory()->count(5)->sequence(fn ($sequence) => [
			'code' => "XXX$sequence->index",
			'type' => $sequence->index > 2 ? 'percent' : 'amount',
			'user_id' => $sequence->index > 2 ? rand(1, 2) : null,
		])->create();

		// Categories
		$name = ['Pizza', 'Kebab', 'Zupy', 'Napoje', 'Sosy', 'Pierogi'];
		$categories = Category::factory(5)->sequence(fn ($sequence) => [
			'name' => $name[$sequence->index],
		])->create();
		$categories->each(function ($cat, $index) use ($list) {
			// Products
			$p = Product::factory()->count(3)->sequence(fn ($sequence) => [
				'name' => 'Product ' . $sequence->index . $index,
			])->create();

			// Addon Groups
			$size = ['S', 'M', 'L', "XL", "XXL", "XXXL"];
			$name = ['Sos', 'Mięso', 'Warzywa', "Ser", "Napoje", "Owoce"];
			$groups = AddonGroup::factory()->count(6)->sequence(fn ($sequence) => [
				'name' => $name[$sequence->index] . '-' . $index,
				'size' => $size[$sequence->index],
			])->create();
			$groups->each(function ($g) use ($list) {
				// Addons
				$name = ['Sos', 'Ser', 'Salami', 'Ananas', 'Szynka'];
				$price = [
					$g->id . '.10',
					$g->id . '.11',
					$g->id . '.22',
					$g->id . '.33',
					$g->id . '.44',
					$g->id . '.55',
					$g->id . '.66',
				];
				$a = Addon::factory()->count(5)->sequence(fn ($sequence) => [
					'name' => $name[$sequence->index],
					'price' => $price[$sequence->index],
					// 'restaurant_id' => $list->first()->id
				])->create();
				$g->addons()->saveMany($a);
			});

			// Variants
			$p->each(function ($i) use ($cat, $groups, $list) {
				$size = ['S', 'M', "XL", "XXL", "XXXL"];
				$a = Variant::factory()->count(3)->sequence(fn ($sequence) => [
					'on_sale' => rand(0, 1),
					'size' => $size[$sequence->index],
					// 'restaurant_id' => $list->first()->id,
				])->make();
				$i->variants()->saveMany($a);
				$cat->products()->save($i);

				// Variants AddonGroup
				$groups->each(function ($g) use ($a) {
					$g->variants()->saveMany($a);
				});
			});
		});

		// Restaurant
		$list->each(function ($item) {
			$a = Area::factory()->count(3)->make();
			$item->areas()->saveMany($a);

			$m = Mobile::factory()->count(3)->make();
			$item->mobiles()->saveMany($m);

			$s = Social::factory()->count(3)->make();
			$item->socials()->saveMany($s);

			$d = Day::factory()->count(8)->sequence(fn ($sequence) => [
				'number' => "$sequence->index",
				'closed' => $sequence->index > 5 ? 1 : 0,
			])->make();
			$item->days()->saveMany($d->where('number', '<>', '0'));

			// Orders
			$o = Order::factory(3)->create([
				'restaurant_id' => $item->id
			]);
		});

		$this->call([
			// AddonGroupSeeder::class,
		]);
	}
}
