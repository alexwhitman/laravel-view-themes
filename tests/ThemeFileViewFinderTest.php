<?php

use Mockery as m;

class ViewFinderTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}

	public function testSettingThemeLocation()
	{
		$finder = $this->getFinder();

		$finder->setThemesLocation(__DIR__);

		$this->assertEquals(__DIR__, $finder->getThemesLocation());
	}

	public function testSettingDefaultTheme()
	{
		$finder = $this->getFinder();

		$finder->setDefaultTheme('default');

		$this->assertEquals('default', $finder->getDefaultTheme());
	}

	public function testSettingCurrentTheme()
	{
		$finder = $this->getFinder();

		$finder->setCurrentTheme('foo');

		$this->assertEquals('foo', $finder->getCurrentTheme());
	}

	public function testGettingCurrentThemePath()
	{
		$finder = $this->getFinder();

		$finder->setThemesLocation(__DIR__);
		$finder->setCurrentTheme('foo');

		$this->assertEquals(__DIR__ . '/foo', $finder->getCurrentThemePath());
	}

	public function testGettingAvailableThemes()
	{
		$finder = $this->getFinder();

		$finder->setThemesLocation(__DIR__);

		$finder->getFilesystem()->shouldReceive('directories')->once()->with(__DIR__)->andReturn(array(
			__DIR__ . '/themes/default',
			__DIR__ . '/themes/empty',
			__DIR__ . '/themes/foo'
		));
		$finder->getFilesystem()->shouldReceive('isDirectory')->once()->with(__DIR__ . '/themes/default/views')->andReturn(true);
		$finder->getFilesystem()->shouldReceive('isDirectory')->once()->with(__DIR__ . '/themes/empty/views')->andReturn(false);
		$finder->getFilesystem()->shouldReceive('isDirectory')->once()->with(__DIR__ . '/themes/foo/views')->andReturn(true);

		$this->assertEquals(array('default', 'foo'), $finder->getAvailableThemes());
	}

	public function testNotThemedViewFinding()
	{
		$finder = $this->getFinder();

		$finder->setThemesLocation(__DIR__);

		$finder->setDefaultTheme('default');

		$finder->setCurrentTheme('current');

		$finder->getFilesystem()->shouldReceive('exists')->once()->with(__DIR__ . '/current/views/index.blade.php')->andReturn(false);
		$finder->getFilesystem()->shouldReceive('exists')->once()->with(__DIR__ . '/current/views/index.php')->andReturn(false);
		$finder->getFilesystem()->shouldReceive('exists')->once()->with(__DIR__ . '/default/views/index.blade.php')->andReturn(true);

		$this->assertEquals(__DIR__ . '/default/views/index.blade.php', $finder->find('index'));
	}

	public function testThemedViewFinding()
	{
		$finder = $this->getFinder();

		$finder->setThemesLocation(__DIR__);

		$finder->setDefaultTheme('default');

		$finder->setCurrentTheme('current');

		$finder->getFilesystem()->shouldReceive('exists')->once()->with(__DIR__ . '/current/views/index.blade.php')->andReturn(true);

		$this->assertEquals(__DIR__ . '/current/views/index.blade.php', $finder->find('index'));
	}

	protected function getFinder()
	{
		return new AlexWhitman\ViewThemes\ThemeFileViewFinder(m::mock('Illuminate\Filesystem\Filesystem'), array(__DIR__));
	}

}
