<?php

namespace Drupal\Tests\weather\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests configuration of weather displays.
 *
 * @group Weather
 */
class ConfigurationTest extends BrowserTestBase {

  /**
   * Set to TRUE to strict check all configuration saved.
   *
   * @var bool
   *
   * @see \Drupal\Core\Config\Development\ConfigSchemaChecker
   */
  protected $strictConfigSchema = FALSE;

  /**
   * An user with permissions to administer website.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $adminUser;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['weather', 'block'];

  use WeatherCommonTestTrait;

  /**
   * Setups all required dependencies.
   */
  public function setUp() {
    parent::setUp();
    // This user may setup a system-wide weather block.
    $this->adminUser = $this->drupalCreateUser([
      'access content',
      'administer site configuration',
      'administer blocks',
    ]);
    // Test with admin user.
    $this->drupalLogin($this->adminUser);
    \Drupal::service('theme_handler')->install(['bartik']);
    \Drupal::service('theme_handler')->setDefault('bartik');
  }

  /**
   * Tests configuration of weather block.
   *
   * @throws \ReflectionException
   */
  public function testConfiguration() {
    // First case.
    // Set a fixed time for testing to 2013-10-07 20:00:00 UTC.
    $config = \Drupal::configFactory()->getEditable('weather.settings');
    $config->set('weather_time_for_testing', 1381176000)->save();

    // Second case.
    // Enable a system-wide weather block.
    $this->drupalPostForm('admin/config/user-interface/weather/system-wide/add',
      [], t('Save'));
    $this->drupalGet('admin/config/user-interface/weather/system-wide/1/add');
    $this->assertText('You do not have any weather places in system.');

    // Third case.
    // Configure the default place.
    $this->weatherFillWeatherSchema('geonames_703448.xml');
    $this->drupalPostForm('admin/config/user-interface/weather/system-wide/1/add',
      [], t('Save'));

    // Clear site cache to add block.
    \Drupal::cache()->invalidateAll();

    // Fourth case - enable & place block.
    $this->drupalGet('admin/structure/block/add/weather_system_display_block:1/bartik');
    $this->drupalPostForm('admin/structure/block/add/weather_system_display_block:1/bartik',
      ['region' => 'sidebar_first'], t('Save block'));

    // Check block existing in blocks list.
    $this->drupalGet('admin/structure/block/list/bartik');
    $this->assertText('Weather: system-wide display (#1)');

    // Make sure that the weather block is displayed
    // with correct forecast data.
    $this->drupalGet('weather/Ukraine/Kiev/Kyiv/1');
    $this->assertRaw('<div class="weather">');
    $this->assertText('00:00-06:00');
    $this->assertRaw('&thinsp;°C');
    $this->assertText('18:00-00:00');
    $this->assertRaw('&thinsp;°C');

    // Change temperature units to Fahrenheit.
    $edit = ['config[temperature]' => 'fahrenheit'];
    $this->drupalPostForm('admin/config/user-interface/weather/system-wide/1/edit', $edit, t('Save'));

    // Clear site cache to add block.
    \Drupal::cache()->invalidateAll();
    // Make sure that the weather block now shows different temperatures.
    $this->drupalGet('weather/Ukraine/Kiev/Kyiv/1');
    $this->assertRaw('&thinsp;°F');
    $this->assertRaw('&thinsp;°F');
    // Logout current user.
    $this->drupalLogout();
  }

}
