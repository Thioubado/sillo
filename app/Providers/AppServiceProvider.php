<?php

/**
 * (ɔ) LARAVEL.Sillo.org - 2015-2024
 */

namespace App\Providers;

use App\Models\Menu;
use App\Models\Setting;
use Illuminate\View\View;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		Facades\View::composer(['components.layouts.app'], function (View $view) {
			$view->with(
				'menus',
				Menu::with(['submenus' => function ($query) {
					$query->orderBy('order');
				}])->orderBy('order')->get()
			);
		});

		if (!$this->app->runningInConsole()) {
			// Vérifiez si la table 'settings' existe
			if (Schema::hasTable('settings')) {
				$settings = Setting::all();
				foreach ($settings as $setting) {
					config(['app.' . $setting->key => $setting->value]);
				}
			}
		}
	}
}
