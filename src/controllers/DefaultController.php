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

namespace humbi1992\googlemapsapi\controllers;

use Craft;
use craft\web\Controller;
use humbi1992\googlemapsapi\GoogleMapsApi;

/**
 * GoogleMapsApi Controller
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var array
     */
    protected array|int|bool $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    /**
     * Makes a request to geocode given latitude longitude and timestamp
     *
     * @param string $city
     * @return void
     */
    public function actionGeocode($city): void
    {
        $result = GoogleMapsApi::getInstance()->googleMapsApiService->geocode($city);
        if ($result['status']) {
            $this->asJson($result['data']);
        } else {
            $this->asErrorJson($result['error']);
        }
    }


    /**
     * Makes a request to geocode given latitude longitude and timestamp
     *
     * @param string $zip
     * @return void
     */
    public function actionCity($city): void
    {
        $result = GoogleMapsApi::getInstance()->googleMapsApiService->city($city);
        if ($result['status']) {
            $this->asJson($result['data']);
        } else {
            $this->asErrorJson($result['error']);
        }
    }

    /**
     * Makes a request to distance given latitude longitude from origin and destination
     *
     * @param float $originLat
     * @param float $originLon
     * @param float $destinationLat
     * @param float $destinationLon
     * @return void
     */
    public function actionDistance($originLat, $originLon, $destinationLat, $destinationLon, $mode): void
    {
        $result = GoogleMapsApi::getInstance()->googleMapsApiService->distance($originLat, $originLon, $destinationLat, $destinationLon, $mode);
        if ($result['status']) {
            $this->asJson($result['data']);
        } else {
            $this->asErrorJson($result['error']);
        }
    }

    /**
     * Makes a request to distance given latitude longitude from origin and destination
     *
     * @param float $origin
     * @param float $destination
     * @return void
     */
    public function actionDistancematrix($origin, $destination, $mode): void
    {
        $result = GoogleMapsApi::getInstance()->googleMapsApiService->distanceMatrix($origin, $destination, $mode);
        if ($result['status']) {
            $this->asJson($result['data']);
        } else {
            $this->asErrorJson($result['error']);
        }
    }

    /**
     * Makes a request to distance given latitude longitude from origin and destination
     *
     * @param float $originLat
     * @param float $originLon
     * @param string $radius
     * @return void
     */
    public function actionRange($origin, $destination, $radius, $mode): void
    {
        $result = GoogleMapsApi::getInstance()->googleMapsApiService->range($origin, $destination, $radius, $mode);
        if ($result['status']) {
            $this->asJson($result['data']);
        } else {
            $this->asErrorJson($result['error']);
        }
    }

    /**
     * Makes a request to elevation given latitude longitude and timestamp
     *
     * @param float $lat
     * @param float $lon
     * @return void
     */
    public function actionElevation($lat, $lon)
    {
        $result = GoogleMapsApi::getInstance()->googleMapsApiService->elevation($lat, $lon);
        if ($result['status']) {
            $this->asJson($result['data']);
        } else {
            $this->asErrorJson($result['error']);
        }
    }

    /**
     * Makes a request to get place given text
     *
     * @param string $input
     * @return void
     */
    public function actionPlaceFromText($input, $fields = '')
    {
        $result = GoogleMapsApi::getInstance()->googleMapsApiService->placeFromText($input, $fields);
        if ($result['status']) {
            $this->asJson($result['data']);
        } else {
            $this->asErrorJson($result['error']);
        }
    }

    /**
     * Makes a request to get place details given place id
     *
     * @param string $input
     * @return void
     */
    public function actionPlaceDetails($placeId)
    {
        $result = GoogleMapsApi::getInstance()->googleMapsApiService->placeDetails($placeId);
        if ($result['status']) {
            $this->asJson($result['data']);
        } else {
            $this->asErrorJson($result['error']);
        }
    }

    /**
     * Makes a request to get place results given text input
     *
     * @param string $input
     * @return void
     */
    public function actionPlaceAutocomplete($input)
    {
        $result = GoogleMapsApi::getInstance()->googleMapsApiService->placeAutocomplete($input);
        if ($result['status']) {
            $this->asJson($result['data']);
        } else {
            $this->asErrorJson($result['error']);
        }
    }

    /**
     * Makes a request to geocode given latitude longitude and timestamp
     *
     * @param float $lat
     * @param float $lon
     * @param integer $timestamp
     * @return void
     */
    public function actionTimezone($lat, $lon, $timestamp)
    {
        $result = GoogleMapsApi::getInstance()->googleMapsApiService->timezone($lat, $lon, $timestamp);
        if ($result['status']) {
            $this->asJson($result['data']);
        } else {
            $this->asErrorJson($result['error']);
        }
    }
}
