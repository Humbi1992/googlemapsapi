<?php

/**
 * GoogleMapsApi plugin for Craft CMS 3.x
 *
 * GoogleMapsApi model settings
 *
 * @link      https://www.webtie.ch
 * @copyright Copyright (c) 2024 Humbi1992.
 * @author Sven Humbel
 */

namespace humbi1992\googlemapsapi\models;

class GoogleMapsApiSettings extends \craft\base\Model
{
    public $googleMapsApiKey = '';

    public function rules()
    {
        return [
            [['googleMapsApiKey'], 'required']
        ];
    }
}
