<?php

/**
 * GoogleMapsApi plugin for Craft CMS 3.x
 *
 * GoogleMapsApi Plugin
 *
 * @link      https://www.webtie.ch
 * @copyright Copyright (c) 2024 Sven Humbel
 * @author Sven Humbel
 */

namespace humbi1992\googlemapsapi;


use Craft;
use craft\base\Plugin;
use craft\web\Response;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Elements;
use craft\web\UrlManager;
use craft\helpers\FileHelper;
use craft\helpers\UrlHelper;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;
use craft\elements\db\ElementQuery;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Asset;
use craft\elements\User;
use craft\elements\GlobalSet;
use craft\web\twig\variables\CraftVariable;
use humbi1992\googlemapsapi\models\GoogleMapsApiSettings;
use humbi1992\googlemapsapi\services\GoogleMapsApiService;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Sven Humbel
 * @package   Weather
 * @since     0.0.1
 *
 */
class GoogleMapsApi extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Weather::$plugin
     *
     * @var Weather
     */
    public static $plugin;
    public string $schemaVersion = '1.0.0';
    public bool $allowAnonymous = true;
    public bool $hasCpSettings = true;

    // Public Methods
    // =========================================================================

    /**
     * Returns whether the plugin should get its own tab in the CP header.
     *
     * @return bool
     */
    public function hasCpSection(): bool
    {
        return false;
    }

    public function hasSettings(): bool
    {
        return true;
    }

    /**
     * @return GoogleMapsApiSettings
     */
    protected function createSettingsModel(): GoogleMapsApiSettings
    {
        return new GoogleMapsApiSettings();
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     * @throws \Twig_Error_Loader
     * @throws \RuntimeException
     */
    protected function settingsHtml() : string
    {
        return \Craft::$app->getView()->renderTemplate(
            'google-maps-api/_settings',
            [
                'settings' => $this->getSettings(),
            ]
        );
    }

    /**
     * Init plugin and initiate events
     */
    public function init(): void
    {
        parent::init();
        $this->setComponents(
            [
                'googleMapsApiService' => GoogleMapsApiService::class,
            ]
        );
        self::$plugin = $this;
        if ($this->isInstalled) {
            // setup url endpoints
            Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES, function (RegisterUrlRulesEvent $event) {
                $event->rules['api/googleMaps/geocode/<city:.*?>'] = 'google-maps-api/default/geocode';
                $event->rules['api/googleMaps/city/<city:.*?>'] = 'google-maps-api/default/city';
                $event->rules['api/googleMaps/timezone/<lat:[+-]?([0-9]*[.])?[0-9]+>,<lon:[+-]?([0-9]*[.])?[0-9]+>,<timestamp:\d+>'] = 'google-maps-api/default/timezone';
                $event->rules['api/googleMaps/place/autocomplete/<input:.*?>'] = 'google-maps-api/default/place-autocomplete';
                $event->rules['api/googleMaps/place/text/<input:.*?>/<fields:.*?>'] = 'google-maps-api/default/place-from-text';
                $event->rules['api/googleMaps/place/details/<placeId:.*?>'] = 'google-maps-api/default/place-details';
                $event->rules['api/googleMaps/elevation/<lat:[+-]?([0-9]*[.])?[0-9]+>,<lon:[+-]?([0-9]*[.])?[0-9]+>'] = 'google-maps-api/default/elevation';
                $event->rules['api/googleMaps/distance/<originLat:[+-]?([0-9]*[.])?[0-9]+>,<originLon:[+-]?([0-9]*[.])?[0-9]+>,<destinationLat:[+-]?([0-9]*[.])?[0-9]+>,<destinationLon:[+-]?([0-9]*[.])?[0-9]+>,?<mode:(driving|bicycling|walking|transit)?>'] = 'google-maps-api/default/distance';
                $event->rules['api/googleMaps/distancematrix/<origin:.*?>,<destination:.*?>,?<mode:(driving|bicycling|walking|transit)?>'] = 'google-maps-api/default/distancematrix';
                $event->rules['api/googleMaps/range/<origin:.*?>,<destination:.*?>,<radius:.*?>,?<mode:(driving|bicycling|walking|transit)?>'] = 'google-maps-api/default/range';
            });
        }
    }
    
    // Protected Methods
    // =========================================================================

}
            
            