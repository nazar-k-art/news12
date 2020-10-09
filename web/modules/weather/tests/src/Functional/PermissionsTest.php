<?php

namespace Drupal\Tests\weather\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests permissions and access settings for different users.
 *
 * @group Weather
 *
 * @requires module weather
 * @requires module block
 */
class PermissionsTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['weather', 'block', 'node'];

  use WeatherCommonTestTrait;

  /**
   * Set to TRUE to strict check all configuration saved.
   *
   * @var bool
   *
   * @see \Drupal\Core\Config\Development\ConfigSchemaChecker
   */
  protected $strictConfigSchema = FALSE;

  /**
   * Setups all required dependencies.
   */
  public function setUp() {
    parent::setUp();
    \Drupal::service('theme_handler')->install(['bartik']);
    \Drupal::service('theme_handler')->setDefault('bartik');
  }

  /**
   * Permissions of weather block.
   *
   * This test requires that at least one system wide block is enabled.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \ReflectionException
   */
  public function testPermissions() {

    // Set a fixed time for testing to 2013-10-07 20:00:00 UTC.
    $config = \Drupal::configFactory()->getEditable('weather.settings');
    $config->set('weather_time_for_testing', 1381176000)->save();
    // Fill database with a test data.
    $this->weatherFillWeatherSchema('geonames_703448.xml');

    // This user is allowed to view the system block.
    $normal_user = $this->drupalCreateUser([
      'access content',
    ]);
    // This user is allowed to administer a custom weather block.
    $weather_user_1 = $this->drupalCreateUser([
      'access content', 'administer blocks',
    ]);
    // This user is also allowed to administer a custom weather block,
    // like weather_user_1. However, he's not allowed to edit the
    // custom block of weather_user_1.
    $weather_user_2 = $this->drupalCreateUser([
      'access content', 'administer blocks',
    ]);
    // This user may setup a system-wide weather block.
    $admin_user = $this->drupalCreateUser([
      'access content',
      'administer site configuration', 'administer blocks',
    ]);

    // Test with admin user.
    $this->drupalLogin($admin_user);
    // Get different pages.
    $this->drupalGet('node');
    $this->drupalGet('admin/config/user-interface/weather');
    $this->assertText(t('Directory for custom images'));

    // Enable a system-wide weather block.
    $this->drupalPostForm('admin/config/user-interface/weather/system-wide/add',
      [], t('Save'));

    // Make sure that the weather block is not
    // displayed without a configured place.
    $this->drupalGet('node');
    $this->assertNoRaw('<div class="weather">');
    $this->assertNoLink('Kyiv');
    $this->assertNoLinkByHref('weather/Ukraine/Kiev/Kyiv/1');
    // Configure the default place.
    $this->drupalPostForm('admin/config/user-interface/weather/system-wide/1/add',
      [], t('Save'));
    // Enable & place block.
    $this->drupalGet('admin/structure/block/add/weather_system_display_block:1/bartik');
    $this->drupalPostForm('admin/structure/block/add/weather_system_display_block:1/bartik',
      ['region' => 'sidebar_first'], t('Save block'));

    $this->drupalGet('admin/config/user-interface/weather');
    $this->assertText(t('Directory for custom images'));
    $this->assertText('Kyiv');
    $this->assertText('Add location to this display');
    // Make sure that the weather block is displayed now.
    $this->drupalGet('node');
    $this->assertRaw('<div class="weather">');
    $this->assertLink('Kyiv');
    $this->assertLinkByHref('weather/Ukraine/Kiev/Kyiv/1');
    // Logout current user.
    $this->drupalLogout();

    // Test with normal user.
    $this->drupalLogin($normal_user);
    // Get front page.
    $this->drupalGet('node');
    $this->assertText(t('Weather'));
    $this->assertRaw('<div class="weather">');
    $this->assertLink('Kyiv');
    $this->assertLinkByHref('weather/Ukraine/Kiev/Kyiv/1');

    // Administration of weather module should be forbidden.
    $this->drupalGet('admin/config/user-interface/weather');
    $this->assertResponse(403);
    $this->assertText(t('You are not authorized to access this page'));
    // Search page should be forbidden.
    $this->drupalGet('weather');
    $this->assertResponse(404);
    $this->assertText(t('The requested page could not be found'));
    // The user may view the page with the detailed forecast of the
    // system-wide display.
    $this->drupalGet('weather/Ukraine/Kiev/Kyiv/1');
    $this->assertResponse(200);
    $this->assertText(t('Weather forecast'));
    $this->assertText('Kyiv');
    // Logout current user.
    $this->drupalLogout();

    // Test with weather user 1.
    $this->drupalLogin($weather_user_1);
    // Get front page.
    $this->drupalGet('node');
    $this->assertText(t('Weather'));
    $this->assertRaw('<div class="weather">');
    $this->assertLink('Kyiv');
    $this->assertLinkByHref('weather/Ukraine/Kiev/Kyiv/1');

    // Administration of weather module should be forbidden.
    $this->drupalGet('admin/config/user-interface/weather');
    $this->assertResponse(403);
    $this->assertText(t('You are not authorized to access this page'));
    // Search page should be forbidden.
    $this->drupalGet('weather');
    $this->assertResponse(404);
    $this->assertText(t('The requested page could not be found'));
    // Using the direct search URL should be forbidden.
    $this->drupalGet('weather/zollenspieker');
    $this->assertResponse(404);
    $this->assertText(t('The requested page could not be found'));
    // The user may view the page with the detailed forecast of the
    // system-wide display.
    $this->drupalGet('weather/Ukraine/Kiev/Kyiv/1');
    $this->assertResponse(200);
    // But the user may not view any other detailed forecasts.
    // This needs the permission to access the search page.
    $this->drupalGet('weather/Germany/Hamburg/Zollenspieker');
    $this->assertResponse(404);
    $this->assertNoText('Zollenspieker');
    // Logout current user.
    $this->drupalLogout();

    // Test with weather user 2.
    $this->drupalLogin($weather_user_2);
    // Get front page.
    $this->drupalGet('node');
    $this->assertText(t('Weather'));
    $this->assertRaw('<div class="weather">');
    $this->assertLink('Kyiv');
    $this->assertLinkByHref('weather/Ukraine/Kiev/Kyiv/1');

    // Administration of weather module should be forbidden.
    $this->drupalGet('admin/config/user-interface/weather');
    $this->assertResponse(403);
    $this->assertText(t('You are not authorized to access this page'));
    // Search page should be forbidden.
    $this->drupalGet('weather');
    $this->assertResponse(404);
    $this->assertText(t('The requested page could not be found'));
    // Using the direct search URL should be forbidden.
    $this->drupalGet('weather/zollenspieker');
    $this->assertResponse(404);
    $this->assertText(t('The requested page could not be found'));
    // The user may view the page with the detailed forecast of the
    // system-wide display.
    $this->drupalGet('weather/Ukraine/Kiev/Kyiv/1');
    $this->assertResponse(200);
    // But the user may not view any other detailed forecasts.
    $this->drupalGet('weather/Germany/Hamburg/Zollenspieker');
    $this->assertResponse(404);
    $this->assertNoText('Zollenspieker');

    // Logout current user.
    $this->drupalLogout();
  }

}
