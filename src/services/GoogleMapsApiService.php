<?php

/**
 * GoogleMapsApi plugin for Craft CMS 3.x
 *
 * GoogleMapsApi Service
 *
 * @link      https://www.webtie.ch
 * @copyright Copyright (c) 2024 Sven Humbel
 * @author Sven Humbel
 */

namespace humbi1992\googlemapsapi\services;

use Craft;
use craft\base\Component;
use craft\helpers\FileHelper;
use craft\elements\Entry;
use craft\services\Elements;
use yii\base\Event;
use craft\elements\db\ElementQuery;
use yii\db\IntegrityException;
use humbi1992\googlemapsapi\GoogleMapsApi;
use function GuzzleHttp\json_decode;

/**
 * GoogleMapsApi Service
 */
class GoogleMapsApiService extends Component
{
    private $params = [];
    private $settings = [];

    public function __construct()
    {
        $this->settings = GoogleMapsApi::getInstance()->getSettings();
        $this->params['baseUrl'] = 'https://maps.googleapis.com/maps/api/';
        $this->params['format'] = 'json';
        parent::__construct();
    }

    public function timezone($lat, $lon, $timestamp)
    {
        $result = $this->request('timezone', 'location=' . $lat . ',' . $lon . '&timestamp=' . $timestamp . '&key=' . $this->settings->googleMapsApiKey);
        if ($result['status']) {
            return [
                'status' => true,
                'data' => $result['data']
            ];
        } else {
            return [
                'status' => false,
                'error' => $result['error']
            ];
        }
    }

    public function elevation($lat, $lon)
    {
        $result = $this->request('elevation', 'locations=' . $lat . ',' . $lon . '&key=' . $this->settings->googleMapsApiKey);
        if ($result['status']) {
            return [
                'status' => true,
                'data' => $result['data']['results']
            ];
        } else {
            return [
                'status' => false,
                'error' => $result['error']
            ];
        }
    }

    public function distance($originLat, $originLon, $destinationLat, $destinationLon, $mode)
    {
        $result = $this->request('distancematrix', 'origins=' . $originLat . ',' . $originLon . '&destinations=' . $destinationLat . ',' . $destinationLon . '&mode=' . $mode . '&key=' . $this->settings->googleMapsApiKey);
        if ($result['status']) {
            return [
                'status' => true,
                'data' => $result['data']['rows']
            ];
        } else {
            return [
                'status' => false,
                'error' => $result['error']
            ];
        }
    }

    public function distanceMatrix($origin, $destination, $mode)
    {
        $result = $this->request('distancematrix', 'origins=' . $origin . ',Schweiz' . '&destinations=' . $destination . ',Schweiz' . '&mode=' . $mode . '&key=' . $this->settings->googleMapsApiKey);
        if ($result['status']) {
            return [
                'status' => true,
                'data' => $result['data']['rows']
            ];
        } else {
            return [
                'status' => false,
                'error' => $result['error']
            ];
        }
    }

    public function range($origin, $destination, $radius, $mode)
    {
        $result = $this->request('distancematrix', 'origins=' . $origin . ',Schweiz' . '&destinations=' . $destination . ',Schweiz' . '&mode=' . $mode . '&key=' . $this->settings->googleMapsApiKey);
        if ($result['status']) {
            $isInRange = $result['data']['rows'][0]['elements'][0]['distance']['value']/1000 < $radius;
            return [
                'status' => true,
                'data' => $isInRange
            ];
        } else {
            return [
                'status' => false,
                'error' => $result['error']
            ];
        }
    }

    public function geocode($city)
    {
        $result = $this->request('geocode', 'address=' . $city . '&key=' . $this->settings->googleMapsApiKey);
        if ($result['status']) {
            return [
                'status' => true,
                'data' => $result['data']['results']
            ];
        } else {
            return [
                'status' => false,
                'error' => $result['error']
            ];
        }
    }

    public function city($city)
    {
        $result = $this->request('geocode', 'address=' . $city . ',Schweiz' . '&key=' . $this->settings->googleMapsApiKey);
        if ($result['status']) {
            return [
                'status' => true,
                'data' => $result['data']['results']
            ];
        } else {
            return [
                'status' => false,
                'error' => $result['error']
            ];
        }
    }

    public function placeFromText($input, $fields)
    {
        $parameters = 'input=' . $input . '&inputtype=textquery&key=' . $this->settings->googleMapsApiKey;
        if (!empty($fields)) {
            $parameters .= '&fields=' . $fields;
        }
        $result = $this->request('place/findplacefromtext', $parameters);
        if ($result['status']) {
            return [
                'status' => true,
                'data' => $result['data']['candidates']
            ];
        } else {
            return [
                'status' => false,
                'error' => $result['error']
            ];
        }
    }

    public function placeAutocomplete($input)
    {
        $result = $this->request('place/autocomplete', 'input=' . $input . '&key=' . $this->settings->googleMapsApiKey);
        if ($result['status']) {
            return [
                'status' => true,
                'data' => $result['data']['predictions']
            ];
        } else {
            return [
                'status' => false,
                'error' => $result['error']
            ];
        }
    }

    public function placeDetails($placeId)
    {
        $result = $this->request('place/details', 'placeid=' . $placeId . '&key=' . $this->settings->googleMapsApiKey);
        if ($result['status']) {
            return [
                'status' => true,
                'data' => $result['data']['result']
            ];
        } else {
            return [
                'status' => false,
                'error' => $result['error']
            ];
        }
    }

    private function request($type, $params)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $url = $this->params['baseUrl'] . $type . '/' . $this->params['format'] . '?' . $params;
            $response = $client->request('GET', $url);
            if ($response->getStatusCode() === 200) {
                $body = $response->getBody()->getContents();
                $data = json_decode($body, true);
                return [
                    "status" => true,
                    "data" => $data
                ];
            }
            return [
                "status" => false,
                "error" => "Server Error"
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "error" => $e->getMessage()
            ];
        }
    }

}
