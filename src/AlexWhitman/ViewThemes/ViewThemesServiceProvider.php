<?php namespace AlexWhitman\ViewThemes;

use AlexWhitman\ViewThemes\ThemeFileViewFinder;
use Illuminate\View\ViewServiceProvider;

class ViewThemesServiceProvider extends ViewServiceProvider {

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('alexwhitman/view-themes');
		parent::boot();
	}

	/**
	* Register the view finder implementation.
	*
	* @return void
	*/
	public function registerViewFinder()
	{
		$this->app['view.finder'] = $this->app->share(function($app)
		{
			$finder = new ThemeFileViewFinder($app['files'], array(app_path() . '/themes'));

			$finder->setThemesLocation($app['config']->get('view-themes::path'));

			$finder->setDefaultTheme($app['config']->get('view-themes::default'));

			return $finder;
		});
	}

	/**
	 * Overriding this as laravel doesn't correctly guess the package
	 * path when the service provider is extending another.
	 * See https://github.com/laravel/framework/issues/2457
	 *
	 * @return string Path to package
	 */
	public function guessPackagePath()
	{
		return realpath(__DIR__ . '/../../');
	}

}
