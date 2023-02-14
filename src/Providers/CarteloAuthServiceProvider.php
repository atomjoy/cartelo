<?php

namespace Cartelo\Providers;

use Cartelo\Models\Area;
use Cartelo\Models\Category;
use Cartelo\Models\Day;
use Cartelo\Models\Mobile;
use Cartelo\Models\Social;
use Cartelo\Models\Restaurant;
use Cartelo\Models\Addon;
use Cartelo\Models\AddonGroup;
use Cartelo\Models\Product;
use Cartelo\Models\Variant;
use Cartelo\Models\Order;

use Cartelo\Policies\AreaPolicy;
use Cartelo\Policies\CategoryPolicy;
use Cartelo\Policies\DayPolicy;
use Cartelo\Policies\MobilePolicy;
use Cartelo\Policies\SocialPolicy;
use Cartelo\Policies\RestaurantPolicy;
use Cartelo\Policies\AddonPolicy;
use Cartelo\Policies\AddonGroupPolicy;
use Cartelo\Policies\ProductPolicy;
use Cartelo\Policies\VariantPolicy;
use Cartelo\Policies\OrderPolicy;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class CarteloAuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
		Area::class => AreaPolicy::class,
		Category::class => CategoryPolicy::class,
		Day::class => DayPolicy::class,
		Mobile::class => MobilePolicy::class,
		Social::class => SocialPolicy::class,
		Restaurant::class => RestaurantPolicy::class,
		AddonGroup::class => AddonGroupPolicy::class,
		Addon::class => AddonPolicy::class,
		Product::class => ProductPolicy::class,
		Variant::class => VariantPolicy::class,
		Order::class => OrderPolicy::class,

		// ::class => Policy::class,
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		//
	}
}
