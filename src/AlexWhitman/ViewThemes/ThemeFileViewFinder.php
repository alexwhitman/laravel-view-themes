<?php namespace AlexWhitman\ViewThemes;

use Illuminate\View\FileViewFinder;
use Illuminate\Filesystem\Filesystem;

class ThemeFileViewFinder extends FileViewFinder {

	/**
	 * Location of themes directory
	 * @var string
	 */
	protected $themesLocation;

	/**
	 * Name of default theme
	 * @var string
	 */
	protected $defaultTheme;

	/**
	 * Name of current theme
	 * @var string
	 */
	protected $currentTheme;

	/**
	 * Create a new file view loader instance.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem  $files
	 * @param  array  $paths
	 * @param  array  $extensions
	 * @return void
	 */
	public function __construct(Filesystem $files, array $paths, array $extensions = null)
	{
		parent::__construct($files, $paths, $extensions);

		$this->themesLocation = null;
		$this->defaultTheme   = null;
		$this->currentTheme   = null;
		$this->paths          = array();
	}

	/**
	 * Add a location to the finder.
	 *
	 * @param  string  $location
	 * @return void
	 */
	public function addLocation($location)
	{
		array_unshift($this->paths, $location);
	}

	/**
	 * Set the location of the themes directory.
	 *
	 * @param string $location Path to themes directory
	 * @return void
	 */
	public function setThemesLocation($location)
	{
		$this->themesLocation = $location;
	}

	/**
	 * Get the location of the themes directory.
	 *
	 * @return string Path to themes directory
	 */
	public function getThemesLocation()
	{
		return $this->themesLocation;
	}

	/**
	 * Set the default theme to use.
	 *
	 * @param string $theme Name of default theme
	 * @return void
	 */
	public function setDefaultTheme($theme)
	{
		$this->defaultTheme = $theme;

		$this->addLocation($this->themesLocation . '/' . $this->defaultTheme . '/views');
	}

	/**
	 * Get the name of the default theme in use.
	 *
	 * @return string Name of default theme
	 */
	public function getDefaultTheme()
	{
		return $this->defaultTheme;
	}

	/**
	 * Set the theme to be used over the default theme.
	 *
	 * @param string $theme Name of theme to use
	 * @return void
	 */
	public function setCurrentTheme($theme)
	{
		$this->currentTheme = $theme;

		$this->addLocation($this->themesLocation . '/' . $this->currentTheme . '/views');
	}

	/**
	 * Get the name of the theme currently in use.
	 *
	 * @return string Name of the current theme
	 */
	public function getCurrentTheme()
	{
		return $this->currentTheme;
	}

	/**
	 * Gets a list of available themes in the theme directory.
	 *
	 * @return array Names of available themes
	 */
	public function getAvailableThemes()
	{
		$paths = $this->files->directories($this->themesLocation);

		$themes = array();
		foreach ($paths as $path)
		{
			if ($this->files->isDirectory($path . '/views'))
			{
				$themes[] = substr($path, strrpos($path, '/') + 1);
			}
		}

		sort($themes, SORT_STRING);

		return $themes;
	}

}
