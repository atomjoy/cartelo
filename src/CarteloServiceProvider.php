<?php

namespace Cartelo;

use Illuminate\Support\ServiceProvider;
use Cartelo\Providers\CarteloAuthServiceProvider;
use Cartelo\Http\Middleware\CarteloMiddleware;
use Cartelo\Services\CarteloService;
use Cartelo\Cartelo;

class CarteloServiceProvider extends ServiceProvider
{
	public function register()
	{
		// Config
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'cartelo');

		if (config('cartelo.enable') == true) {
			// Routes
			$this->app['router']->aliasMiddleware('cartelo', CarteloMiddleware::class);

			// Facade
			$this->app->bind('cartelo', function ($app) {
				return new Cartelo();
			});

			// Service
			$this->app->bind(CarteloService::class, function ($app) {
				return new CarteloService();
			});

			// Auth Polices
			$this->app->register(CarteloAuthServiceProvider::class);
		}
	}

	public function boot()
	{
		if (config('cartelo.enable') == true) {

			$this->loadViewsFrom(__DIR__ . '/../resources/views', 'cartelo');
			$this->loadTranslationsFrom(__DIR__ . '/../lang', 'cartelo');
			$this->loadJsonTranslationsFrom(__DIR__ . '/../lang');

			if (config('cartelo.migrations') == true) {
				$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
			}

			if (config('cartelo.routes') == true) {
				$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
			}
		}

		if ($this->app->runningInConsole()) {

			$this->publishes([
				__DIR__ . '/../config/config.php' => config_path('cartelo.php'),
			], 'cartelo-config');

			$this->publishes([
				__DIR__ . '/../resources/views' => resource_path('views/vendor/cartelo'),
				__DIR__ . '/../lang' => $this->app->langPath('vendor/cartelo'),
			], 'cartelo-pages');

			$this->publishes([
				__DIR__ . '/../database/migrations' => database_path('/migrations'),
			], 'cartelo-migrations');

			$this->publishes([
				__DIR__ . '/../public' => public_path('vendor/cartelo'),
			], 'cartelo-public');

			$this->publishes([
				__DIR__ . '/../tests/Cartelo' => base_path('tests/Cartelo')
			], 'cartelo-tests');
		}
	}
}
