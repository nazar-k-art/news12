INTRODUCTION
------------

This module uses free weather data from yr.no to display current weather
conditions from anywhere in the world. Data for more than 14.000 places
worldwide is included for easy weather display.

 * For a full description of the module, visit the project page:
   https://www.drupal.org/project/weather

 * To submit bug reports and feature suggestions, or track changes:
   https://www.drupal.org/project/issues/weather


REQUIREMENTS
------------

No special requirements.

INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. Visit
   https://www.drupal.org/documentation/install/modules-themes/modules-7
   for further information.

 * Base setup:
    - Go to /admin/config/user-interface/weather:
    - Import all weather places to the database (one-time action). This operation can take some time, depending on your system's performance, as more than 14 000 records must be inserted into Database.
      If you will click this button again in the future, it will remove all places already imported and perform new 'clean' import of all places.
    - (optionally) Configure default weather display settings, this settings will be inherited by all newly created displays
    - Add and configure your first system display
    - Add new places to the display
    - Clear cache and you will be able to see new Block in the system, created for each weather display.

 * Add custom places:
    If you can't find the place you need in initially imported list - go to https://yr.no site and find the place. Copy link to the page,
    It should look like: https://www.yr.no/place/Ukraine/Kiev/Kyiv/. Always use only link to English variant of the page.
    After it go to module's settings: /admin/config/user-interface/weather/places and add this place with a form you see.
    Now you can use the place in any display.

MAINTAINERS
-----------

Current maintainers:
 * Dr. Tobias Quathamer (toddy) - https://www.drupal.org/user/62716
 * Yaroslav Samoilenko (ysamoylenko) - https://www.drupal.org/u/ysamoylenko
 * Taras Kyryliuk (tarasich) - https://www.drupal.org/u/tarasich
